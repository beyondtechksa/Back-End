<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ColorsController extends Controller
{
    public function product_colors(){
        $colors=Color::with('colors')->where('color_id',null)->latest()->get();
        return inertia('Products/Colors',['colors'=>$colors])->with(['page_name'=>__('product colors')]);
    }

    public function create_color(Request $request){
        $request->validate([
            'name'=>'required|array',
            'color_code'=>'nullable',
            'color_id'=>'nullable',
            'image'=>'nullable',
        ]);
        $name_tr=$request->name['or'];
        $color=Color::create([
            'name'=>$request->name,
            'name_tr'=>$name_tr,
            'color_code'=>$request->color_code,
            'color_id'=>$request->color_id,
            'image'=>$request->image,
            'status'=>1,
        ]);
        Artisan::call('cache:clear');
        return Color::with('colors')->find($color->id);
    }
    public function edit_color(Request $request){
        $request->validate([
            'name'=>'required|array',
            'color_code'=>'nullable',
            'color_id'=>'nullable',
        ]);
        $name_tr=$request->name['or'];
        $color=Color::find($request->id);
        $color->update([
            'name'=>$request->name,
            'name_tr'=>$name_tr,
            'color_code'=>$request->color_code,
            'color_id'=>$request->color_id,
            'image'=>$request->image,
            'status'=>$request->status,
        ]);
        Artisan::call('cache:clear');

        return Color::with('colors')->find($color->id);
    }

    public function delete_color($id){
        $color=Color::find($id);
        $color->delete();
        Artisan::call('cache:clear');
    }

    public function multi_delete_color(Request $request){
        $colors=Color::destroy($request->checked);
    }

}
