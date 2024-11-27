<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\AddressesService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\ReturnReason;
use App\Models\OrderItem;
use App\Models\ReturnItem;
use App\Models\ReturnStatus;
use App\Services\ReturnRequestService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function basic_info(Request $request): Response
    {
        return Inertia::render('Profile/BasicInfo', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function update(Request $request){
        $user=Auth::user();
        $request->validate([
            'avatar'=>'nullable|file|image:jpg,png,jpeg'
        ]);
        $data=$request->validate([
            'name'=>'required|string|max:100',
            'phone'=>'required|string|max:9|min:9',
            'email'=>'required|string|email|unique:users,email,'.$user->id,
            'gender'=>'required|string|max:191',

        ]);
        if($request->avatar){
            $path=uploadImage($request->file('avatar'),'avatars');
            $data['avatar']=$path;
        }
        $user->update($data);
        return redirect()->route('welcome');
    }

    public function addresses()
    {
        $addresses = (new AddressesService())->getUserAddresses(user()->id);
        return inertia('Dashboard/Addresses', ['addresses' => $addresses])->with(['page_title' => 'addresses']);
    }
    public function my_orders(){
        $orders=Order::with('order_items.product.brand','return_request','order_items.return_items')->where('user_id',user()->id)->latest()->get();
        return inertia('Dashboard/Orders',['orders'=>$orders])->with(['page_title'=>'my orders']);
    }

    public function create_address(){
        return inertia('Dashboard/CreateAddress')->with(['page_title'=>'addresses']);
    }

    public function store_address(AddressRequest $request)
    {
        (new AddressesService())->createAddress(user()->id,$request->validated());
        if (session('redirect_url')) {
            $redirect_url = session('redirect_url');
            session()->forget('redirect_url');
            return redirect(url($redirect_url));
        } else {
            return redirect()->route('profile.addresses');
        }
    }

    public function edit_address($id)
    {
        $address = Address::findOrFail($id);
        return inertia('Dashboard/EditAddress', ['address' => $address])->with(['page_title' => 'edit address']);
    }

    public function update_address(AddressRequest $request)
    {
        (new AddressesService())->updateAddress($request->id,user()->id,$request->validated());
        return redirect()->route('profile.addresses');
    }

    public function track_order($order_id=null){
        return inertia('Dashboard/TrackOrder',['order_id'=>$order_id]);
    }
    public function ProfileController($order_id=null){
        return inertia('Dashboard/TrackOrder',['order_id'=>$order_id]);
    }
    public function track_order_post(Request $request){
        $request->validate([
            'order_id'=>'required|numeric|exists:orders,id'
        ]);

        $order=Order::with('external_shipping_company')->findOrFail($request->order_id);
        return  inertia('Dashboard/TrackOrder',['order_id'=>$order->id,'order'=>$order]);
    }

    public function return_order($order_id){
        // $order=Order::with('order_items.product.return_policy','order_items.product.brand')->findOrFail($order_id);
        $order = Order::with([
            'order_items' => function($query) {
                $query->whereDoesntHave('return_items');
            },
            'order_items.product' => function($query) {
                $query->select('id', 'name_en', 'name_ar', 'brand_id', 'return_policy_id', 'image');
            },
            'order_items.product.brand',
            'order_items.product.return_policy',
            'return_request' => function($query) {
                $query->with(['return_items.order_item.product'=>function($query){
                    $query->select('id', 'name_en', 'name_ar', 'brand_id', 'image');
                },
                'return_items.order_item.product.brand',
                'return_items.return_status'
            ]);
            }
        ])
        ->findOrFail($order_id);
        $return_reasons=ReturnReason::get();
        return inertia('Dashboard/ReturnOrder',['order'=>$order,'return_reasons'=>$return_reasons]);
    }
    public function return_order_item_details($order_item_id){
        $order_item = OrderItem::with('product:id,slug')
        ->findOrFail($order_item_id);
        $return_item=ReturnItem::where('order_item_id',$order_item_id)->first();
        $return_statuses=ReturnStatus::get();
        return inertia('Dashboard/ReturnOrderItemDetails',['order_item'=>$order_item,'return_item'=>$return_item,'return_statuses'=>$return_statuses]);
    }
    public function return_order_details($order_id){
        $order = Order::with([
            'order_items.product:id,name_en,name_ar,image',
            'return_request.return_items',
            'return_request.return_reason',
            'return_request.return_status',
            ])
        ->findOrFail($order_id);

        return inertia('Dashboard/ReturnOrderDetails',['order'=>$order]);
    }

    public function store_return_request(Request $request){
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'return_reason_id' => 'nullable|exists:return_reasons,id',
            'items' => 'required|array',
            'items.*' => 'required|exists:order_items,id',
            'reason' => 'nullable|string',
            'image'=>'required|file|mimes:jpg,jpeg,png,gif'
        ]);
        $data['image']=uploadImage($request->file('image'),'return-items');
        $data['user_id'] = auth()->id(); // Get the authenticated user ID
        $returnRequestService=new ReturnRequestService();
        $returnRequest = $returnRequestService->createReturnRequest($data);

        return redirect()->route('profile.orders')->with('success','your request is under review');

    }

    public function cancel_return_item(Request $request){
        $return_item=ReturnItem::find($request->return_item_id);
        $return_item->update([
            'status'=>'canceled by user'
        ]);
        return redirect()->route('profile.orders');
        // return redirect()->route('profile.');
    }

}
