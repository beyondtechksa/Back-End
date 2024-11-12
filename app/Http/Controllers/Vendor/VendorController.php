<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyRequest;
use App\Models\ExternalShippingCompany;

use App\Exports\OrderRequestExport;
use Maatwebsite\Excel\Facades\Excel;

class VendorController extends Controller
{
    public function index()
    {
        $total_orders=CompanyRequest::where('vendor_id',vendor()->id)->count();
        $pending_orders=CompanyRequest::where('vendor_id',vendor()->id)->where('vendor_status',0)->count();
        $refused_orders=CompanyRequest::where('vendor_id',vendor()->id)->where('vendor_status',1)->count();
        $accepted_orders=CompanyRequest::where('vendor_id',vendor()->id)->where('vendor_status',2)->count();
        return inertia('Vendor/Dashboard',compact('total_orders','pending_orders','refused_orders','accepted_orders'))->with(['page_name'=>'Dashboard']);
    }
    public function order_requests($status=null)
    {   
        $order_requests=CompanyRequest::with('user','order_item.product')->where('vendor_id',vendor()->id)->latest();
        if($status || $status=='0'){
            $order_requests->where('vendor_status',$status);
        }
        $order_requests=$order_requests->paginate(40);
        $shipping_companies=ExternalShippingCompany::where('vendor_id',vendor()->id)->get();
        return inertia('Vendor/OrderRequest',['order_requests'=>$order_requests,'shipping_companies'=>$shipping_companies])->with(['page_name'=>'Order Request']);
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
            'external_shipping_company_id'=>'required|numeric|max:255',
        ]);
        $order_requests=CompanyRequest::whereIn('id',$request->checked)->update([
            'traking_code'=>$request->traking_code,
            'external_shipping_company_id'=>$request->external_shipping_company_id,
        ]);

        return 'ok';
    }

    public function export_orders_session(Request $request){
        $request->checked;
        session()->put('checked',$request->checked);
    }
    public function export_orders(Request $request){
         $checked=session()->get('checked');
         return Excel::download(new OrderRequestExport($checked), 'data.xlsx');

    }

    public function notifications(){

        return vendor()->unReadNotifications;
    }
    public function markReadNotification($id){
        $notification = vendor()->unReadNotifications()->find($id);
        $notification->markAsRead();
        return response()->json(['success' => 'Notification marked as read.', 'notification' => $notification]);
    }

    public function get_pending_orders(){
        return CompanyRequest::where('vendor_status',0)->where('vendor_id',vendor()->id)->count(); 
    }
   
}
