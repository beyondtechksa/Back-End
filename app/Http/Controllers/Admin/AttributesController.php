<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\Option;

class AttributesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('attributes');
        $this->create_page_name=__('create attribute');
        $this->edit_page_name=__('edit attribute');
        $this->attributes=Attribute::get();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes=Attribute::with('admin')->orderBy('list','asc')->get();
        if(admin()->hasPermissionTo('view product attribute')){
        return inertia('Attributes/Index',['attributes'=>$attributes])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    

    public function update_attributes_list(Request $request){
        foreach($request->checked as $key=>$value){
            Attribute::find($value['id'])->update(['list'=>$key+1]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Attribute $attribute)
    {
        return '';
    }

    public function create()
    {
        if(admin()->hasPermissionTo('add product attribute')){
        return inertia('Attributes/Create')->with(['page_name'=>$this->create_page_name]);
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
            'name' => 'required',
            'type' => 'required',
        ]);
        $attribute=Attribute::create($data);
        $options=$request->options;
        if(count($options)>0){
            foreach($options as $option){
                Option::create([
                    'attribute_id'=>$attribute->id,
                    'name'=>$option['name'],
                    'image'=>$option['image'],
                ]);
            }
        }
        return redirect()->route('attributes.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($attribute_id)
    {
        if(admin()->hasPermissionTo('edit product attribute')){
            $attribute=Attribute::with('options')->findOrFail($attribute_id);
        return inertia('Attributes/Edit',compact('attribute'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, attribute $attribute)
    {
        $data=$request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);
        $attribute->update($data);
        $options=$request->options;
        $undeleted_ids=[];
        if(count($options)>0){
            foreach($options as $option){
                if($option['id']==null){
                    $created=Option::create([
                        'attribute_id'=>$attribute->id,
                        'name'=>$option['name'],
                        'image'=>$option['image'],
                    ]);
                    array_push($undeleted_ids,$created->id);
                }else{
                    $edited=Option::find($option['id']);
                    array_push($undeleted_ids,$option['id']);
                    if($edited){
                        $edited->update([
                        'name'=>$option['name'],
                        'image'=>$option['image'],
                        ]);
                    }
                }
            }
        }
        $deleted_ids=Option::whereNotIn('id',$undeleted_ids)->where('attribute_id',$attribute->id)->pluck('id');
        Option::destroy($deleted_ids);
        return redirect()->route('attributes.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('attributes.index')->with('success',__('item deleted successfully'));
    }

    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Attribute::destroy($request->checked);
            return redirect()->route('attributes.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('attributes.index');
        }
    }

    public function filter(Request $request){
        $attributes=Attribute::with('admin')->latest();
        if($request->search){
            $attributes->where('name','like','%'.$request->search.'%');
        }
        $data=$attributes->paginate(15);
        return inertia('Attributes/Index',['attributes'=>$data])->with(['page_name'=>__('filter')]);
    }

    

    
}
