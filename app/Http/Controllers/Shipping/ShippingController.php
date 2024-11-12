<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\CompanyRequest;
use App\Models\ExternalShippingCompany;

use App\Exports\OrderRequestExport;
use Maatwebsite\Excel\Facades\Excel;

class ShippingController extends Controller
{
    public function index()
    {
        $total_orders=Order::where('status','>=',2)->count();
        $pending_orders=Order::where('status','>',2)->where('status','<',5)->count();
        $new_orders=Order::where('status',2)->count();
        $completed_orders=Order::where('status',5)->count();
        return inertia('ShippingCompany/Dashboard',[
            'total_orders'=>$total_orders,
            'pending_orders'=>$pending_orders,
            'new_orders'=>$new_orders,
            'completed_orders'=>$completed_orders,
        ])->with(['page_name'=>'Dashboard']);
    }
    public function orders(Request $request,$status=null)
    {   
        $orders=Order::with('user','company_requests')->where('status','>=',2)->latest();
        if($status){
            if($status=='pending'){
                $orders->where('status','>',2)->where('status','<',6);
            }else{
                $orders->where('status',$status);
            }
        }
        if($request->vendor_status){
            $orders->whereDoesntHave('company_requests', function ($query) {
                $query->where('vendor_status', '!=', 2);
            })->whereHas('company_requests', function ($query) {
                $query->where('vendor_status', 2);
            });
        }
        $orders=$orders->paginate(40);
        $orders->getCollection()->transform(function($order) {
            // Check if all company requests have vendor_status = 2
            $allVendorStatusIsTwo = $order->company_requests->every(function ($company_request) {
                return $company_request->vendor_status == 2;
            });

            // Set vendor_status to 2 if all company_requests have vendor_status 2, otherwise 0
            if($order->company_requests->count()==0){
                $order->vendor_status = 0;
            }else{
                $order->vendor_status = $allVendorStatusIsTwo ? 2 : 0;
            }

    return $order;
        });
        return inertia('ShippingCompany/Orders',['orders'=>$orders])->with(['page_name'=>'Orders']);
    }

    public function show_order($id){
        $order=Order::with('user')->where('id',$id)->firstOrFail();
        $company_requests=CompanyRequest::with('order_item.product','external_shipping_company')->where('order_id',$id)->get();
        $shipping_companies=ExternalShippingCompany::where('shipping_company_id',shipping()->id)->get();
        return inertia('ShippingCompany/ShowOrder',['order'=>$order,'company_requests'=>$company_requests,'shipping_companies'=>$shipping_companies])->with(['page_name'=>'View Order']);;
    }

    public function update_order_request_status(Request $request){
        $order_requests=CompanyRequest::whereIn('id',$request->checked)->where('vendor_status',0)->update([
            'vendor_status'=>$request->status
        ]);

        return redirect()->back();
    }
    public function update_traking_code(Request $request){
        $request->validate([
            'traking_code'=>'required|string|max:255',
            'external_shipping_company_id'=>'required|numeric'
        ]);
        $order=Order::find($request->id);
        $order->update([
            'traking_code'=>$request->traking_code,
            'external_shipping_company_id'=>$request->external_shipping_company_id
        ]);

        return 'ok';
    }
    public function update_shipment(Request $request){
        $request->validate([
            'company_shipping'=>'required|numeric|min:0',
            'weight'=>'required|string|max:255'
        ]);
        $order=Order::find($request->id);
        $order->update([
            'company_shipping'=>$request->company_shipping,
            'weight'=>$request->weight
        ]);

        return 'ok';
    }


    public function notifications(){

        return shipping()->unReadNotifications;
    }
    public function markReadNotification($id){
        $notification = shipping()->unReadNotifications()->find($id);
        $notification->markAsRead();
        return response()->json(['success' => 'Notification marked as read.', 'notification' => $notification]);
    }

    public function get_pending_orders(){
        return Order::where('status',2)->count(); 
    }
   
}
