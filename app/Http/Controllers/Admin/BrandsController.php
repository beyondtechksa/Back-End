<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Enums\CacheEnums;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class BrandsController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('brands');
        $this->create_page_name=__('create brand');
        $this->edit_page_name=__('edit brand');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands=Brand::latest()->paginate(15);
        if(admin()->hasPermissionTo('view product brand')){
        return inertia('Brands/Index',['brands'=>$brands])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Brand $brand)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add product brand')){
        return inertia('Brands/Create')->with(['page_name'=>$this->create_page_name]);
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
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'status' => 'nullable',
        ]);
        if($request->slug){
            $slug=unique_slug($request->slug,'Brand');
        }else{
            $slug=unique_slug($request->name['en'],'Brand');
        }
        $data['slug']=$slug;
        $brand=Brand::create($data);
        Artisan::call('cache:clear');
        return redirect()->route('brands.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        if(admin()->hasPermissionTo('edit product brand')){
        return inertia('Brands/Edit',compact('brand'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, brand $brand)
    {
        $data=$request->validate([
            'name' => 'required|array',
            'slug' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'status' => 'nullable',
        ]);
        if($request->slug){
            $slug=unique_slug($request->slug,'Brand');
        }else{
            $slug=unique_slug($request->name['en'],'Brand');
        }
        $data['slug']=$slug;
        $brand->update($data);
        Artisan::call('cache:clear');
        return redirect()->route('brands.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        Artisan::call('cache:clear');

        return redirect()->route('brands.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Brand::destroy($request->checked);
            Artisan::call('cache:clear');
            return redirect()->route('brands.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('brands.index');
        }
    }

    public function filter(Request $request){
        $brands=Brand::latest();
        if($request->search){
            $brands->where('name','like','%'.$request->search.'%');
        }
        $data=$brands->paginate(15);
        return inertia('Brands/Index',['brands'=>$data])->with(['page_name'=>__('filter')]);
    }




}
