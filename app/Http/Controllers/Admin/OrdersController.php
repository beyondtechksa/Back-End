<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomersExport;
use App\Exports\OrdersExport;
use App\Exports\CompanyRequestExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Bill;
use App\Models\Vendor;
use App\Models\ReturnItem;
use App\Models\ReturnStatus;

use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\OrderItem;
use App\Models\CompanyRequest;
use App\Models\User;
use App\Http\Enums\CompanyEnums;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Notifications\EmailNotifiable;
use App\Notifications\CompanyEmail;
use App\Notifications\VendorNotification;
use Illuminate\Support\Facades\Notification;


class OrdersController extends Controller
{
    public function index($status=null)
    {
        if (admin()->hasPermissionTo('view order')) {
            if($status==null){
                $orders = Order::with('user')->latest()->paginate(10);
                }else{
                    if(in_array($status,[0,5,1])){
                    $orders = Order::with('user')->where('status',$status)->latest()->paginate(10);
                    }else{
                        $orders = Order::with('user')->whereIn('status',[0,2,3,4])->latest()->paginate(10);
                    }
                }
            return inertia('Orders/Index', ['orders' => $orders])->with(['page_name' => 'Orders']);
        } else {
            return no_permission_redirect();
        }
    }
    public function latest_orders(){
        return Order::with('user')->withCount('order_items')->latest()->take(5)->get();
    }

    public function show($id){
        if(admin()->hasPermissionTo('view order')){
            $order=Order::with(['order_items.product','order_items.company_request.vendor','user'])->findOrFail($id);
            $products=Product::where('status',1)->limit(200)->get();
            $colors=Color::get();
            $sizes=Size::get();
            return inertia('Orders/Show',[
                'order'=>$order,
                'products'=>$products,
                'sizes'=>$sizes,
                'colors'=>$colors,
                ])->with(['page_name'=>'Orders']);
        }else{
            return  no_permission_redirect();
        }
    }


    public function update_order(Request $request)
    {
        $order = Order::with('order_items.product')->find($request->id);
        $order->update([
            'status' => $request->status
        ]);
        if($order->status==2){
            // create company request
        }
        return $order->status;
    }


    public function create_order_item(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1'
        ]);
        $product = Product::find($request->product_id);
        $order_item = OrderItem::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->final_selling_price,
            'size' => $request->size,
            'color' => $request->color,
        ]);
        $order = Order::find($request->order_id);
        $price = exchange_price($product->final_selling_price,'SAR') * $request->quantity;
        $order->update([
            'subtotal_amount' => $order->subtotal_amount + $price,
            'total_amount' => $order->total_amount + $price,
        ]);
        return OrderItem::with('product')->find($order_item->id);
    }

    public function export()
    {
        return Excel::download(new OrdersExport(), 'orders.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function bill($order_id){
        $order=Order::findOrFail($order_id);
        $bill=Bill::where('order_id',$order_id)->first();
        if(!$bill){
            $bill=Bill::create([
                'user_id'=>$order->user_id,
                'order_id'=>$order->id,
                'bill_id'=>create_bill_id(),
            ]);
        }

        $returned_bill=Bill::with('user','order.order_items.product')->find($bill->id);

        return view('admin.pdfs.order_bill',['bill'=>$returned_bill]);
    }
    public function view_bill($bill_id){

        $returned_bill=Bill::with('user','order.order_items.product')->findOrFail($bill_id);

        return view('admin.pdfs.order_bill',['bill'=>$returned_bill]);
    }

    public function show_bill($bill_id){

        $returned_bill=Bill::with('user','order.order_items.product')->findOrFail($bill_id);

        return inertia('Orders/ShowBill',['bill'=>$returned_bill])->with(['page_name' => 'Show bill']);;
    }

    public function bills(){
        if(admin()->hasPermissionTo('view bill')){
            $bills=Bill::with('user','order.order_items.product')->latest()->paginate(20);
            $reported_bills=Bill::latest();
            $totalSumBills= Bill::join('orders', 'bills.order_id', '=', 'orders.id');
            $totalSum=$totalSumBills->sum('orders.total_amount');
            $paidSum=$totalSumBills->where('paid_status',1)->sum('orders.total_amount');
            $not_paidSum=Bill::join('orders', 'bills.order_id', '=', 'orders.id')->where('paid_status',0)->sum('orders.total_amount');
            $reports=[
                'all_bills'=>[
                    'count'=>$reported_bills->count(),
                    'amount'=>number_format($totalSum,2),
                ],
                'paid_bills'=>[
                    'count'=>$reported_bills->where('paid_status',1)->count(),
                    'amount'=>number_format($paidSum,2),
                ],
                'not_paid_bills'=>[
                    'count'=>Bill::where('paid_status',0)->count(),
                    'amount'=>number_format($not_paidSum,2),
                ],
            ];
            return inertia('Orders/Bills', ['bills' => $bills,'reports'=>$reports])->with(['page_name' => 'bills']);
        }else{
            return  no_permission_redirect();
        }
    }

    public function companies(){
        $companies=CompanyEnums::getCompaniesValues();
        return inertia('Orders/Companies',['companies'=>$companies])->with(['page_name' => 'companies requests']);;
    }

    public function company_requests(Request $request,$company_name){
        $company_requests=CompanyRequest::with('user','order_item.product')->where('company_name',$company_name)
        ->when($request->has('status'),function($query) use($request){
            return $query->where('status',$request->status);
        })->latest()->paginate(20);
            return inertia('Orders/CompaniesRequests',['company_requests'=>$company_requests,'company_name'=>$company_name])->with(['page_name' => 'companies requests']);
    }
    public function vendor_requests(Request $request,$id){
        $vendor=Vendor::findOrFail($id);
        $vendor_requests=CompanyRequest::with('user','order_item.product')->where('vendor_id',$id)
        ->when($request->has('status'),function($query) use($request){
            return $query->where('vendor_status',$request->status);
        })->latest()->paginate(20);
            return inertia('Vendors/VendorRequests',['vendor_requests'=>$vendor_requests,'vendor'=>$vendor])->with(['page_name' => 'Vendors requests']);
    }

    public function export_company_requests($company_name){
        return Excel::download(new CompanyRequestExport($company_name), 'data.xlsx');
    }

    public function send_company_email(Request $request){
        $email = CompanyEnums::getEmail($request->company);
        try {
            Notification::route('mail', $email)
                        ->notify(new CompanyEmail($request->company));
                        $company_requests=CompanyRequest::where('company_name',$request->company)->where('status',0)->update([
                            'status'=>1
                        ]);
        }catch(\Exception $e) {
            \Log::error('error: ' . $e->getMessage());
        }
    }

    public function return_requests(Request $request){
        $return_items=ReturnItem::with(['order_item.product'=>function($query){
            $query->select('id','name_en','name_ar','image');
        },
        'return_request.user:id,name',
        'return_status'
        ])->when($request->has('status_id'),function($query) use ($request){
            $query->where('return_status_id',$request->status_id);
        })->latest()->paginate(20);
        $return_statuses=ReturnStatus::get();

        return inertia('Orders/ReturnRequests',[
            'return_items'=>$return_items,
            'return_statuses'=>$return_statuses,
            'return_status_id'=>$request->status_id])->with('page_name','return requests');
    }
    public function update_status(Request $request){
        $return_item=ReturnItem::with('order_item.order')->findOrFail($request->id);
        $return_item->update([
            'return_status_id'=>$request->status_id
        ]);

        $lastStatus = ReturnStatus::latest('id')->value('id');

        $isLastRecord = $request->status_id == $lastStatus;

        if($isLastRecord){
            $user_id = $return_item->order_item->order->user_id;
            $user=User::find($user_id);
            if($user){
                if (!$user->wallet()->exists()) {
                    $user->wallet()->create([
                        'balance' => 0
                    ]);
                }

                $user->wallet->credit(($return_item->order_item->price * $return_item->quantity) , 'refund from order '.$return_item->order_item->order_id);
            }
        }

        return redirect()->back()->with('success','updated successfully');
    }

}
