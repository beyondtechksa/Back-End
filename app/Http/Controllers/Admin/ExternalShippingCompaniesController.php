<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExternalShippingCompany;

class ExternalShippingCompaniesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('companies');
        $this->create_page_name=__('create company');
        $this->edit_page_name=__('edit company');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $external_shipping_companies=ExternalShippingCompany::latest();
        if($request->search){
            $external_shipping_companies->where('name','like','%'.$request->search.'%');
        }
        if(vendor()){
            $external_shipping_companies->where('vendor_id',vendor()->id);
        }
        $external_shipping_companies=$external_shipping_companies->paginate(15);
        return inertia('Vendor/Companies/Index',['external_shipping_companies'=>$external_shipping_companies])->with(['page_name'=>$this->index_page_name]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(ExternalShippingCompany $external_shipping_company)
    {
        return '';
    }


    public function create()
    {

        return inertia('Vendor/Companies/Create')->with(['page_name'=>$this->create_page_name]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $data=$request->validate([
            'name' => 'required|string',
            'link' => 'required|string',
            'image' => 'nullable|string',
        ]);
        if(vendor()){
            $data['vendor_id']=vendor()->id;
        }
        
        $external_shipping_company=ExternalShippingCompany::create($data);
        return redirect()->route('external_shipping_companies.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExternalShippingCompany $external_shipping_company)
    {
  
        return inertia('Vendor/Companies/Edit',compact('external_shipping_company'))->with(['page_name'=>$this->edit_page_name]);
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, collection $external_shipping_company)
    {
        $data=$request->validate([
            'name' => 'required|string',
            'link' => 'required|string',
            'image' => 'nullable|string',
        ]);
        $external_shipping_company->update($data);
        return redirect()->route('external_shipping_companies.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExternalShippingCompany $external_shipping_company)
    {
        $external_shipping_company->delete();
        return redirect()->route('external_shipping_companies.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            ExternalShippingCompany::destroy($request->checked);
            return redirect()->route('external_shipping_companies.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('external_shipping_companies.index');
        }
    }

    public function filter(Request $request){
        $shipping_companies=ExternalShippingCompany::latest();
        if($request->search){
            $shipping_companies->where('name','like','%'.$request->search.'%');
        }
        $data=$shipping_companies->paginate(15);
        return inertia('shipping_companies/Index',['shipping_companies'=>$data])->with(['page_name'=>__('filter')]);
    }

    

    
}
