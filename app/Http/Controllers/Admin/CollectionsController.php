<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;


class CollectionsController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('collections');
        $this->create_page_name=__('create collection');
        $this->edit_page_name=__('edit collection');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections=collection::with('admin')->latest()->paginate(15);
        if(admin()->hasPermissionTo('view collection')){
        return inertia('Collections/Index',['collections'=>$collections])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Collection $collection)
    {
        return '';
    }


    public function create()
    {
        if(admin()->hasPermissionTo('add collection')){
        return inertia('Collections/Create')->with(['page_name'=>$this->create_page_name]);
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
            'image' => 'required|array',
            'status' => 'nullable',
        ]);
        if($request->slug){
            $slug=unique_slug($request->slug,'Collection');
        }else{
            $slug=unique_slug($request->name['en'],'Collection');
        }
        $data['slug']=$slug;
        $collection=collection::create($data);
        return redirect()->route('collections.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        if(admin()->hasPermissionTo('edit collection')){
        return inertia('Collections/Edit',compact('collection'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, collection $collection)
    {
        $data=$request->validate([
            'name' => 'required|array',
            'slug' => 'nullable|string|max:255',
            'image' => 'required|array',
            'status' => 'nullable',
        ]);
        if($request->slug){
            $slug=unique_slug($request->slug,'Collection');
        }else{
            $slug=unique_slug($request->name['en'],'Collection');
        }
        $data['slug']=$slug;
        $collection->update($data);
        return redirect()->route('collections.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('collections.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            collection::destroy($request->checked);
            return redirect()->route('collections.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('collections.index');
        }
    }

    public function filter(Request $request){
        $collections=collection::with('admin')->latest();
        if($request->search){
            $collections->where('name','like','%'.$request->search.'%');
        }
        $data=$collections->paginate(15);
        return inertia('Collections/Index',['collections'=>$data])->with(['page_name'=>__('filter')]);
    }

    

    
}
