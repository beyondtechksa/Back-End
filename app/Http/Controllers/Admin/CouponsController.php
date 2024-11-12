<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponsController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('coupons');
        $this->create_page_name=__('create coupon');
        $this->edit_page_name=__('edit coupon');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons=Coupon::latest()->paginate(15);
        if(admin()->hasPermissionTo('view coupon')){
        return inertia('Coupons/Index',['coupons'=>$coupons])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Coupon $coupon)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add coupon')){
        return inertia('Coupons/Create')->with(['page_name'=>$this->create_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:coupons',
            'reference' => 'required|string|max:255',
            'reference_details' => 'nullable|string|max:255',
            'discount_type' => 'required|string|max:255',
            'coupon_type' => 'required|string|max:255',
            'discount_amount' => 'required|numeric|'.decimal_validation(),
            'valid_untill' => 'nullable|date',
            'status' => 'nullable',
        ]);
        $coupon=Coupon::create($data);
        return redirect()->route('coupons.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        if(admin()->hasPermissionTo('edit coupon')){
        return inertia('Coupons/Edit',compact('coupon'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, coupon $coupon)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:coupons,code,'.$coupon->id,
            'reference' => 'required|string|max:255',
            'reference_details' => 'nullable|string|max:255',
            'discount_type' => 'required|string|max:255',
            'coupon_type' => 'required|string|max:255',
            'discount_amount' => 'required|numeric|'.decimal_validation(),
            'valid_untill' => 'nullable|date',
            'status' => 'nullable',
        ]);
        $coupon->update($data);
        return redirect()->route('coupons.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Coupon::destroy($request->checked);
            return redirect()->route('coupons.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('coupons.index');
        }
    }

    public function filter(Request $request){
        $coupons=Coupon::latest();
        if($request->search){
            $coupons->where('name','like','%'.$request->search.'%');
        }
        $data=$coupons->paginate(15);
        return inertia('Coupons/Index',['coupons'=>$data])->with(['page_name'=>__('filter')]);
    }

    

    
}
