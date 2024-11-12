<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SizesController extends Controller
{
    public function product_sizes(){
        $sizes=Size::with('sizes')->where('size_id',null)->latest()->get();
        return inertia('Products/Sizes',['sizes'=>$sizes])->with(['page_name'=>__('product sizes')]);
    }

    public function create_size(Request $request){
        $request->validate([
            'name'=>'required|array',
            'size_id'=>'nullable|numeric'
        ]);
        $name_tr=$request->name['or'];
        $size=Size::create([
            'name'=>$request->name,
            'size_id'=>$request->size_id,
            'name_tr'=>$name_tr,
            'status'=>1
        ]);
        Artisan::call('cache:clear');

        return Size::with('sizes')->find($size->id);
    }
    public function edit_size(Request $request){
        $request->validate([
            'name'=>'required|array',
            'size_id'=>'nullable|numeric'
        ]);
        $name_tr=$request->name['or'];
        $size=Size::find($request->id);
        $size->update([
            'name'=>$request->name,
            'name_tr'=>$name_tr,
            'size_id'=>$request->size_id,
            'status'=>$request->status,
        ]);
        Artisan::call('cache:clear');

        return Size::with('sizes')->find($size->id);
    }

    public function delete_size($id){
        $size=Size::find($id);
        $size->delete();
        Artisan::call('cache:clear');

    }
    public function multi_delete_size(Request $request){
        $sizes=Size::destroy($request->checked);
    }

    
}
