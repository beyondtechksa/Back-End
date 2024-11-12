<?php

namespace App\Services;


use App\Models\Address;
use App\Models\CartDiscount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use TelrGateway\TelrManager;


class PaymentService
{
    private $cart_id = null;
    private $total = 0;
    private $user = null;
    private $address = null;
    private $url = null;

    public function loadTelrIframe($user_id, $carts, $currency = 'SAR')
    {
        $this->url = url('order_success');
        $this->user = User::find($user_id);
        $this->address = Address::where('user_id', $user_id)
            ->where('favourite', 1)->first();
        $this->modifyCart($carts, $currency);
        $response = Http::post('https://secure.telr.com/gateway/order.json', [
            'method' => "create",
            'store' => config('app.store'),
            'authkey' => config('app.authKey'),
            'order' => [
                'test' => 1,
                'cartid' => $this->cart_id,
                'amount' => $this->total,
                'currency' => 'SAR',//$currency but USD NOT IN IT MOSTLY
                'description' => 'Create new Order for ' . $this->user->name
            ],
            'return' => [
                'authorised' => $this->url,
                'declined' => $this->url,
                'cancelled' => $this->url,
            ],
//            'return_auth' => '/cart/checkout/payment',
//            'return_can' => '/cart/checkout/payment',
//            'return_decl' => '/cart/checkout/payment',
            'lang' => 'en',
            'framed' => 2,
            'customer' => [
                "email" => $this->user->email,
                "mobile" => $this->user->phone,
                "name" => [
                    "title" => $this->user->name,
                    "forenames" => $this->user->name,
                    "surname" => $this->user->name,
                ],
                "address" => [
                    "line1" => $this->address->details,
//                    "line2" => "aaa",
//                    "line3" => "acc",
                    "city" => $this->address->city,
                    "state" => $this->address->postal_code,
                    "country" => "sa", //sudia
//                    "areacode" => "acc",
                ],
            ]
        ]);
        $result = $response->json();
        $this->storeTransaction($result);
//        $this->storeTransactionPackage(); //to run package redirect from button
        return $result;
    }

    protected function modifyCart($carts, $currency)
    {
        $subtotal = (new OrderService())->calculateSubtotal($carts, $currency);
        $shipping = get_shipping_price();
        $cartDiscount = CartDiscount::where('user_id', $this->user->id)->where('status', 0)->first();
        $discountPercentage = $cartDiscount ? $cartDiscount->discount_percentage : 0;
        $total = (new OrderService())->calculateTotal($subtotal, $shipping, $discountPercentage);
        $vat = (new OrderService())->calculateVat($carts);
        $this->total = number_format($total + $vat, 2, '.');
        $this->cart_id = json_encode($carts->pluck('id')->toArray());
    }

    //incase we will use the package
    public function storeTransactionPackage()
    {
        $telrManager = new TelrManager();
        config('telr.create.ivp_cart', $this->cart_id);
        $billingParams = [
            'first_name' => $this->user->name,
            'sur_name' => $this->user->name,
            'address_1' => $this->address->details,
            'address_2' => null,
            'city' => $this->address->city,
//            'region' => 'Saudi Arabia',
            'zip' => $this->address->postal_code,
            'country' => 'SA',
            'email' => $this->user->email,
        ];
        $telrManager->pay('123', $this->total, 'pay with telr', $billingParams);
//        ->redirectURL()
    }

    public function storeTransaction($res)
    {
        if (isset($res) && $res['order'] && $res['order']['url']) {
            DB::table('payment_transactions')->insert([
                'total' => $this->total,
                'uuid' => $res['order']['ref'],
                'user_id' => $this->user->id,
                'address_id' => $this->address->id,
            ]);
        }
    }

    public function checkPayment($user_id)
    {
        $transaction = DB::table('payment_transactions')
            ->where('user_id', $user_id)
            ->where('status', 0)
            ->orderBy('id', 'DESC')
            ->first();
        if (!$transaction)
            return false;
        $response = Http::post('https://secure.telr.com/gateway/order.json', [
            'method' => 'check',
            'store' => config('app.store'),
            'authkey' => config('app.authKey'),
            'order' => [
                'ref' => $transaction->uuid
            ]
        ]);
        return $response->json();
    }

}
