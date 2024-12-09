<?php

namespace App\Services;

use App\Models\Address;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ClickPayService
{
    private $cart_id = null;
    private $total = 0;
    private $user = null;
    private $address = null;
    private $currency = 'SAR'; // Set default currency

    // Load ClickPay payment page
    public function loadClickpayPaymentPage($user_id)
    {
        $callback_url = route('payment.callback');
        $return_url = route('payment.return');
        $this->user = User::find($user_id);
        $this->address = Address::where('user_id', $user_id)
            ->where('favourite', 1)->first();
        $carts = (new GlobalService())->get_user_selected_cart($user_id);
        $this->modifyCart($carts);
        $response = Http::withHeaders([
            'authorization' => config('clickpay.server_key'),
        ])->post('https://secure.clickpay.com.sa/payment/request', [
            'profile_id' => config('clickpay.profile_id'),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'cart_id' => $this->cart_id,
            'cart_currency' => $this->currency,
            'cart_amount' => $this->total,
            'cart_description' => 'Payment for order ' . $this->user->name,
            'paypage_lang' => 'en',
            'customer_details' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'street1' => $this->address->details,
                'city' => $this->address->city,
                'state' => $this->address->postal_code,
                'country' => 'AE',
                'zip' => '12345',
                'ip' => request()->ip(),
            ],
            'shipping_details' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'street1' => $this->address->details,
                'city' => $this->address->city,
                'state' => $this->address->postal_code,
                'country' => 'AE',
                'zip' => '12345',
                'ip' => request()->ip(),
            ],
            'callback' => $callback_url,
            'return' => $return_url,
        ]);

        $result = $response->json();
        $trans =  $this->storeTransaction($result);
        return $result;
    }

    // Modify cart and calculate totals
    protected function modifyCart($carts)
    {
        $orderData = (new OrderService())->calculateOrder($carts);
        $this->total = $orderData['total'];
        $this->cart_id = json_encode($carts->pluck('id')->toArray());
    }

    // Store transaction details in DB
    protected function storeTransaction($res)
    {
        if (isset($res) && isset($res['tran_ref'])) {
            $payment =  PaymentTransaction::create([
                'total' => $this->total,
                'uuid' => $res['tran_ref'],
                'user_id' => $this->user->id,
                'address_id' => $this->address->id,
                'status' => 0,
            ]);
        }
        //          TODO undefined $payment outside the if
        return $payment;
    }

    // Check the payment status
    public function checkPayment($transaction_ref, $status)
    {
        //        TODO just update the specific coloumn not the the raw  $paymentTransactions->update(['status'=>$status == 'A' ? 'active' : 'close']);
        $paymentTransactions = PaymentTransaction::where('uuid', $transaction_ref)->latest()->fisrt();
        $paymentTransactions->status =  $status == 'A' ? 'active' : 'close';
        $paymentTransactions->update();
    }


    public function storeMobileTransaction($res)
    {
        if (isset($res) && isset($res['transactionReference'])) {
            $payment =  PaymentTransaction::create([
                'total' => $res['tranTotal'],
                'uuid' => $res['transactionReference'],
                'user_id' => auth()->user()->id,
                'address_id' => $res['address_id'],
                'status' => 0,
            ]);
        }
        return $payment;
    }

    public function processPaymentDetails($paymentDetails)
    {

        $transactionReference = $paymentDetails['transactionReference'];
        $paymentResult = $paymentDetails['paymentResult'];
        $responseStatus = $paymentResult['responseStatus'] ?? null;
        $tranCurrency = $paymentResult['tranCurrency'] ?? 'SAR';
        $responseMessage = $paymentResult['responseMessage'] ?? 'No response message';

        $transaction = PaymentTransaction::where('uuid', $transactionReference)->first();

        if (!$transaction) {
            return [
                'status' => 'error',
                'message' => 'Transaction not found.',
            ];
        }

        // Verify the payment with ClickPay for added security
        $isValid = $this->verifyPaymentWithClickPay($transactionReference);

        if (!$isValid) {
            return [
                'status' => 'error',
                'message' => 'Payment verification failed with ClickPay.',
            ];
        }

        // Update transaction status based on the response status
        if ($responseStatus === 'A') { // 'A' for Authorized
            $user_id = auth()->user()->id;
            $user = User::find($user_id);
            $transaction =  $user->payment_transactions()->latest()->first();
            $transaction->status = 1;
            $payment =  $transaction;
            $order = (new OrderService())->order($user->id, $payment->id, $tranCurrency);
            $transaction->response = $order;
            $transaction->update();

            DB::table(table: 'payment_transactions')
                ->where('user_id',  $user->id)
                ->where('status', 0)
                ->whereNull('response')
                ->delete();



            return [
                'status' => 'success',
                'data' => $transaction,
            ];
        } else {
            $transaction->status = 0;
            $transaction->save();

            return [
                'status' => 'error',
                'message' => "Payment failed: $responseMessage",
            ];
        }
    }

    protected function verifyPaymentWithClickPay($transactionReference)
    {
        $response = Http::withHeaders([
            'authorization' => config('clickpay.server_key'),
        ])->post('https://secure.clickpay.com.sa/payment/query', [
            'profile_id' => config('clickpay.profile_id'),
            'tran_ref' => $transactionReference,
        ]);

        $result = $response->json();

        if (
            isset($result['payment_result']['response_status']) &&
            $result['payment_result']['response_status'] == 'A'
        ) {
            return true;
        } else {

            return false;
        }
    }

    public function getVerifyPaymentWithClickPay($transactionReference)
    {
        $response = Http::withHeaders([
            'authorization' => config('clickpay.server_key'),
        ])->post('https://secure.clickpay.com.sa/payment/query', [
            'profile_id' => config('clickpay.profile_id'),
            'tran_ref' => $transactionReference,
        ]);
        $responseresult = false;
        $result = $response->json();
        if (
            isset($result['payment_result']['response_status']) &&
            $result['payment_result']['response_status'] == 'A'
        ) {
            $responseresult = true;
        } else {

            $responseresult = false;
        }
        if (!$responseresult) {
            return [
                'status' => 'error',
                'message' => 'Payment verification failed with ClickPay.'.$responseresult,
            ];
        }else {
            return [
                'status' => 'success',
                'message' => 'Payment verification completed.'.$responseresult,
            ];
        }
    }
}
