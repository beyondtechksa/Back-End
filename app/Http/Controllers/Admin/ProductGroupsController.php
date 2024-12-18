<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ProductGroupsController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('product groups');
        $this->create_page_name=__('create product group');
        $this->edit_page_name=__('edit product group');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product_groups=ProductGroup::latest()->when(request()->has('search'),function($query){
            $query->where('name','like','%'.request()->search.'%');
        })->paginate(15);
        if(admin()->hasPermissionTo('view product')){
        return inertia('ProductGroups/Index',['product_groups'=>$product_groups])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add product')){
        return inertia('ProductGroups/Create')->with(['page_name'=>$this->create_page_name]);
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
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
        ]);
        $product_group=ProductGroup::create($data);
        return redirect()->route('product_groups.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductGroup $product_group)
    {
        if(admin()->hasPermissionTo('edit product')){
        return inertia('ProductGroups/Edit',compact('product_group'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductGroup $product_group)
    {
        $data=$request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
        ]);
        $product_group->update($data);
        return redirect()->route('product_groups.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductGroup $product_group)
    {
        $product_group->delete();
        return redirect()->route('product_groups.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            ProductGroup::destroy($request->checked);
            return redirect()->route('product_groups.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('product_groups.index');
        }
    }





}
