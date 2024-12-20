<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use App\Jobs\UpdateVendorProductsDiscount;
use App\Models\Product;

class VendorsController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('vendors');
        $this->create_page_name=__('create vendor');
        $this->edit_page_name=__('edit vendor');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors=Vendor::latest()->paginate(15);
        if(admin()->hasPermissionTo('view vendor')){
        return inertia('Vendors/Index',['vendors'=>$vendors])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Vendor $vendor)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add vendor')){
        return inertia('Vendors/Create')->with(['page_name'=>$this->create_page_name]);
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
            'email' => 'required|string|email|max:255|unique:vendors',
            'password' => 'required|string|max:255',
            'discount_percentage' => 'nullable|numeric|min:0',
            'phone' => 'required|string|max:255',
            'website' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
        ]);
           if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

        $vendor=Vendor::create($data);
        return redirect()->route('vendors.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        if(admin()->hasPermissionTo('edit vendor')){
        return inertia('Vendors/Edit',compact('vendor'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:vendors,email,'.$vendor->id,
            'discount_percentage' => 'nullable|numeric|min:0',
            'phone' => 'required|string|max:255',
            'website' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
        ]);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        if($request->discount_percentage!=$vendor->discount_percentage){
            // job
            $products=Product::where('company_name',$vendor->name)->get();
            collect($products)->chunk(100)->each(function ($batch) use($vendor) {
                UpdateVendorProductsDiscount::dispatch($batch->toArray(),$vendor->discount_percentage);
            });
        }
        $vendor->update($data);

        return redirect()->route('vendors.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Vendor::destroy($request->checked);
            return redirect()->route('vendors.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('vendors.index');
        }
    }

    public function filter(Request $request){
        $vendors=Vendor::latest();
        if($request->search){
            $vendors->where('name','like','%'.$request->search.'%');
        }
        $data=$vendors->paginate(15);
        return inertia('Vendors/Index',['vendors'=>$data])->with(['page_name'=>__('filter')]);
    }

    public function update_vendor_status(Request $request){
        $vendor=Vendor::find($request->vendor_id);
        $vendor->update([
            'status'=>!$vendor->status
        ]);
    }


}
