<?php

namespace App\Services;


use App\Models\Address;
use App\Models\CartDiscount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class TamaraPaymentService
{
    private $cart_id = null;
    private $total = 0;
    private $user = null;
    private $address = null;
    private $url = null;
    public function loadTamaraPayment($user_id, $carts, $currency = 'SAR')
    {
         $this->url = url('order_success');
        $this->user = User::find($user_id);
        $this->address = Address::where('user_id', $user_id)
            ->where('favourite', 1)
            ->first(); // Get the user's default address

         $this->modifyCart($carts, $currency);

        // Tamara API URL
        $tamaraApiUrl = config('tamarapayment.url') . 'checkout';

        // Prepare the request payload dynamically
        // dd($this->total);
        $payload = $this->prepareTamaraPayload($carts);

        //dd($payload);
        // Make the request to Tamara API
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'authorization' => 'Bearer ' . config('tamarapayment.token'),
            'Content-Type' => 'application/json',
            'X-App-Locale' => 'en'
        ])->post($tamaraApiUrl, $payload);

        $result = $response->json();

        // Optionally store the transaction
        $this->storeTransaction($result);

        return $result;
    }


    protected function modifyCart($carts, $currency)
    {
        $subtotal = (new OrderService())->calculateSubtotal($carts, $currency);
        $shipping = get_shipping_price();
        $cartDiscount = CartDiscount::where('user_id', $this->user->id)->where('status', 0)->first();
        $discountPercentage = $cartDiscount ? $cartDiscount->discount_percentage : 0;
        $total = (new OrderService())->calculateTotal($subtotal, $shipping, $discountPercentage);
         $this->total = number_format($total  , 2, '.', '');
        $this->cart_id = json_encode($carts->pluck('id')->toArray());
     }

     protected function prepareTamaraPayload($carts)
     {
         return [
             "total_amount" => [
                 "amount" => $this->total,
                 "currency" => "SAR"
             ],
             "shipping_amount" => [
                 "amount" => get_shipping_price(),
                 "currency" => "SAR"
             ],
             "tax_amount" => [
                 "amount" =>  14,
                 "currency" => "SAR"
             ],
             "order_reference_id" => uniqid(), // Unique reference ID
             "order_number" => 'ORDER-' . now()->timestamp, // Dynamic order number
             "discount" => [
                 "name" => "Dynamic Voucher",
                 "amount" => [
                     "amount" => 0, // Adjust discount as required
                     "currency" => "SAR"
                 ]
             ],
             "items" => $this->prepareItems($carts ,$this->total),
             "consumer" => [
                 "email" => $this->user->email,
                 "first_name" => $this->user->name,
                 "last_name" => $this->user->name,
                 "phone_number" => $this->user->phone
             ],
             "country_code" => "SA",
             "description" => "Payment for order.",
             "merchant_url" => [
                 "cancel" => url('/cancel'),
                 "failure" => url(path: '/fail'),
                 "success" => $this->url,
                 "notification" => url('/payments/tamara-notifications')
             ],
             "payment_type" => "PAY_BY_INSTALMENTS",
             "instalments" => 3,
             "billing_address" => $this->prepareAddress($this->address),
             "shipping_address" => $this->prepareAddress($this->address),
             "platform" => "YourPlatform",
             "is_mobile" => false,
             "locale" => "ar_SA",
             "risk_assessment" => $this->prepareRiskAssessment(),
             "additional_data" => [
                 "delivery_method" => "Home Delivery",
                 "pickup_store" => "N/A",
                 "store_code" => "StoreCode123",
                 "vendor_amount" => 0,
                 "merchant_settlement_amount" => 0,
                 "vendor_reference_code" => "VENDOR-1234"
             ]
         ];
     }


    protected function prepareItems($carts , $total)
{
    $items = [];
    //dd($carts);
    foreach ($carts as $cart) {
        $items[] = [
            "name" => $cart->product->name_en, // Replace with actual cart product name
            "type" => "Physical", // Adjust as required
            "reference_id" => $cart->id,
            "sku" => $cart->product->sku,
            "quantity" => $cart->quantity,
            "discount_amount" => [
                "amount" => $cart->discount ?? 0,
                "currency" => "SAR"
            ],
            "tax_amount" => [
                "amount" => 14,
                "currency" => "SAR"
            ],
            "unit_price" => [
                "amount" => $cart->product->price,
                "currency" => "SAR"
            ],
            "total_amount" => [
                "amount" => $total,
                "currency" => "SAR"
            ]
        ];
    }

    return $items;
}

    protected function prepareAddress($address)
    {
        return [
            "city" => $address->city,
            "country_code" => "SA",
            "first_name" => $this->user->name,
            "last_name" => $this->user->name, // Add logic to get last name
            "line1" => $address->details,
            "line2" => "N/A",
            "phone_number" => $this->user->phone,
            "region" => "Region" // Add region if available
        ];
    }

    protected function prepareRiskAssessment()
    {
        return [
            "customer_age" => 21,
            "customer_dob" => "01-12-2000",
            "customer_gender" => "male",
            "customer_nationality" => "SA",
            "is_premium_customer" => false,
            "is_existing_customer" => false,
            "is_guest_user" => false,
            "account_creation_date" => "12-06-2020",
            "platform_account_creation_date" => "12-06-2020",
            "date_of_first_transaction" => "12-06-2020",
            "is_card_on_file" => false,
            "is_COD_customer" => false,
            "has_delivered_order" => true,
            "is_phone_verified" => false,
            "is_fraudulent_customer" => false,
            "total_ltv" => 200,
            "total_order_count" => 15,
            "order_amount_last3months" => 2000,
            "order_count_last3months" => 10,
            "last_order_date" => "12-06-2020",
            "last_order_amount" => 2000,
            "reward_program_enrolled" => false,
            "reward_program_points" => 2000
        ];
    }

    public function storeTransaction($res)
    {
        if (isset($res) && isset($res['order']['url'])) {
            DB::table('payment_transactions')->insert([
                'total' => $this->total,
                'uuid' => $res['order']['reference_id'],
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

        if (!$transaction) {
            return false;
        }

        $response = Http::post('https://api-sandbox.tamara.co/orders/check', [
            'order_reference_id' => $transaction->uuid
        ]);

        return $response->json();
    }
}
