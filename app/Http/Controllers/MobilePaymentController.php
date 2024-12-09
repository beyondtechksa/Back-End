<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Services\ClickPayService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MobilePaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {

        $paymentService = new ClickPayService();
        $paymentResponse = $paymentService->storeMobileTransaction(  $request);
        $createOrder = $paymentService->processPaymentDetails($request);
        return response()->json([ 'data' => $createOrder]);
    }
    public function testInitiatePayment(Request $request)
    {

        $res=$request->all();
        $paymentService = new ClickPayService();
        $paymentResponse = $paymentService->getVerifyPaymentWithClickPay(   $res['transactionReference']);
         return response()->json([ 'data' => $paymentResponse]);
    }
}
