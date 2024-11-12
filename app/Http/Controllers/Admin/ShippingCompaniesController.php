<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Hash;

class ShippingCompaniesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('ShippingsCompanies');
        $this->create_page_name=__('create shipping cpmpany');
        $this->edit_page_name=__('edit shipping cpmpany');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ShippingsCompanies=ShippingCompany::latest()->when($request->has('search'),function($query) use($request){
            $query->where('name','like','%'.$request->search.'%');
        })->paginate(15);
        if(admin()->hasPermissionTo('view shipping company')){
        return inertia('ShippingCompanies/Index',['shipping_companies'=>$ShippingsCompanies])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Vendor $ShippingCompany)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add shipping company')){
        return inertia('ShippingCompanies/Create')->with(['page_name'=>$this->create_page_name]);
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
            'email' => 'required|string|email|max:255|unique:shipping_companies',
            'password' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
        ]);
           if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }
        
        $ShippingCompany=ShippingCompany::create($data);
        return redirect()->route('shipping_companies.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingCompany $ShippingCompany)
    {
        if(admin()->hasPermissionTo('edit shipping company')){
        return inertia('ShippingCompanies/Edit',compact('ShippingCompany'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shippingcompany $ShippingCompany)
    {
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:shipping_companies,email,'.$ShippingCompany->id,
            'phone' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
        ]);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        $ShippingCompany->update($data);
        return redirect()->route('shipping_companies.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingCompany $ShippingCompany)
    {
        $ShippingCompany->delete();
        return redirect()->route('shipping_companies.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            ShippingCompany::destroy($request->checked);
            return redirect()->route('shipping_companies.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('shipping_companies.index');
        }
    }

    public function filter(Request $request){
        $ShippingsCompanies=ShippingCompany::latest();
        if($request->search){
            $ShippingsCompanies->where('name','like','%'.$request->search.'%');
        }
        $data=$ShippingsCompanies->paginate(15);
        return inertia('ShippingCompanies/Index',['ShippingsCompanies'=>$data])->with(['page_name'=>__('filter')]);
    }

    public function update_shipping_company_status(Request $request){
        $ShippingCompany=ShippingCompany::find($request->shipping_company_id);
        $ShippingCompany->update([
            'status'=>!$ShippingCompany->status
        ]);
    }

    
}
