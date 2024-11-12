<?php

namespace App\Services;

use App\Exceptions\OrderCreationException;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\CartDiscount;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Bill;
use App\Models\CompanyRequest;
use App\Models\Vendor;
use App\Models\ShippingCompany;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\OrderNotifications;
use App\Notifications\VendorNotification;
use Illuminate\Support\Facades\DB;


class OrderService
{

    /**
     * @throws OrderCreationException
     */
    public function order($user_id, $payment_id, $currency = 'SAR')
    {
        try {
            DB::beginTransaction();
            $carts = Cart::where('user_id', $user_id)->where('selected',1)->get();
            if ($carts->isEmpty())
                throw new OrderCreationException('there is no products in cart');
            $subtotal = $this->calculateSubtotal($carts, $currency);
            $subtotal_before_vat = $this->calculateSubtotalBeforeVat($carts);
            $address = Address::where('user_id', $user_id)->where('favourite', 1)->first();
            if (!$address)
                throw new OrderCreationException('address not found');
            $shipping = get_shipping_price();
            $cartDiscount = CartDiscount::where('user_id', $user_id)->where('status', 0)->first();
            $discountPercentage = $cartDiscount ? $cartDiscount->discount_percentage : 0;
            $total = $this->calculateTotal($subtotal, $shipping, $discountPercentage);
            $vat = $this->calculateVat($carts);
            $total_amount = $total;
            $order = $this->createOrder($user_id, $address, $subtotal_before_vat, $shipping, $discountPercentage, $total_amount, $vat, $currency, $payment_id);
            $this->createOrderItems($order, $carts);
            $this->updateCartDiscountStatus($cartDiscount);
            // try{
                $user=User::find($user_id);
                $text='There is a new order from '.$user->name;
                $admin_link='/admin/view-order/'.$order->id;
                $company_link='/shipping/show-order/'.$order->id;
                $admin = Admin::first();
                $admin->notify(new OrderNotifications($text,$admin_link,$user));
                $shipping_company = ShippingCompany::first();
                $shipping_company->notify(new OrderNotifications($text,$company_link,$user));
            // }catch(\Exception $e){

            // }

            $this->createBill($order);
            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new OrderCreationException("Failed to create order: " . $e->getMessage(), 500, $e);

        }
    }

    public function calculateSubtotal($carts, $currency = 'SAR')
    {
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->product->final_selling_price * $cart->quantity;
        }
        return exchange_price($subtotal, $currency);
    }
    public function calculateSubtotalBeforeVat($carts)
    {
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += $this->calculatePriceBeforeVat(exchange_price($cart->product->final_selling_price, 'SAR'),$cart->product->tax_percentage) * $cart->quantity;
        }
        return round($subtotal, 2);
    }

    public function calculateTotal($subtotal, $shipping, $discountPercentage)
    {
        $totalBeforrDiscount = $subtotal + $shipping;
        $total = $totalBeforrDiscount - ($discountPercentage * $totalBeforrDiscount / 100);
       return number_format($total, 2, '.', '');
    }

    public function calculateVat($carts)
    {
        $vat=0;
        foreach($carts as $cart){
            $vat += $this->calculateVatAmount(exchange_price($cart->product->final_selling_price, 'SAR'),$cart->product->tax_percentage);
        }
        return $vat;
    }

    private function createOrder($userId, $address, $subtotal, $shipping, $discountPercentage, $total, $vat, $currency, $payment_id)
    {
        return Order::create([
            'user_id' => $userId,
            'address' => $address,
            'subtotal_amount' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discountPercentage,
            'total_amount' => $total,
            'status' => 0,
            'payment_id' => $payment_id,
            'vat' => $vat,
            'currency' => $currency,
            'status'=>2
        ]);
    }

    private function createOrderItems($order, $carts)
    {
        $orderItems = $carts->map(function ($cart) use ($order) {
            return [
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'color' => $cart->color,
                'size' => $cart->size,
                'tax_percentage' => $cart->product->tax_percentage,
                'tax_amount' => $this->calculateVatAmount(exchange_price($cart->product->final_selling_price, 'SAR'),$cart->product->tax_percentage),
                'unit_price' => $this->calculatePriceBeforeVat(exchange_price($cart->product->final_selling_price, 'SAR'),$cart->product->tax_percentage),
                'taxable_amount'=>$this->calculatePriceBeforeVat(exchange_price($cart->product->final_selling_price, 'SAR'),$cart->product->tax_percentage) * $cart->quantity,
                'price' => exchange_price($cart->product->final_selling_price, 'SAR') * $cart->quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();
        DB::table('order_items')->insert($orderItems);
        $this->createCompanyRequest($order);
        Cart::where('user_id', $order->user_id)->where('selected',1)->delete();
    }

    private function calculatePriceBeforeVat($price,$tax_percentage){
        return $price/(1+($tax_percentage/100));
    }
    private function calculateVatAmount($price,$tax_percentage){
        return $price - $this->calculatePriceBeforeVat($price,$tax_percentage);
    }

    private function createCompanyRequest(Order $order){
        $order_items=OrderItem::with('product')->where('order_id',$order->id)->get();
        foreach($order_items as $order_item){
            $company_name=$order_item->product->company_name;
            if($company_name){
                $vendor=Vendor::where('name',$company_name)->first();
                CompanyRequest::firstOrCreate([
                    'order_id'=>$order->id,
                    'order_item_id'=>$order_item->id,
                ],[
                    'vendor_id'=>$vendor?$vendor->id:null,
                    'user_id'=>$order->user_id,
                    'company_name'=>$company_name,
                    'company_product_id'=>$order_item->product->company_product_id,
                ]);
                if($vendor){
                // notify vendor
                try{
                    $text="there is some products need to be arranged";
                    $link=url('vendor/order-requests');
                    $vendor->notify(new VendorNotification($vendor,$text,$link));
                }catch(\Exception $e){

                }

                }
            }
        }
    }

    private function updateCartDiscountStatus($cartDiscount)
    {
        if ($cartDiscount) {
            $cartDiscount->update(['status' => 1]);
        }
    }

    private function createBill(Order $order){
        return Bill::create([
            'bill_id'=>create_bill_id(),
            'order_id'=>$order->id,
            'user_id'=>$order->user_id,
            'paid_status'=>1,
        ]);
    }

}
