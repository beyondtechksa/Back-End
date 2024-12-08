<?php

namespace App\Http\Controllers\Admin;

use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Collection;
use App\Models\MobileBanner;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\DomainCheck;


class SettingsController extends Controller
{
    public function index($type){
        if($type=='web'){
            $settings=Settings::whereIn('type',['mobile_web','web'])->get();
        }elseif($type=='mobile'){
            $settings=Settings::whereIn('type',['mobile_web','mobile'])->get();
        }
        return inertia('Settings/Index',['settings'=>$settings,'type'=>$type])->with(['page_name'=>__('settings')]);
    }
    public function mobile_banners(){
        $categories=Category::where('category_id',null)->whereIn('id',[16,17,18])->get();
        return inertia('Settings/MobileIndex',['categories'=>$categories])->with(['page_name'=>__('settings')]);

    }
    public function general_settings(){
        $settings=Settings::whereType('general')->get();
        return inertia('Settings/Index',['settings'=>$settings])->with(['page_name'=>__('settings')]);
    }
    public function shipping(){
        $shipping=Shipping::firstOrCreate([],[
            'price'=>10
        ]);
        return inertia('Shipping/Edit',['shipping'=>$shipping])->with(['page_name'=>__('shipping')]);
    }

    public function edit($type,$id){
        $setting=Settings::findOrFail($id);
        $collections=Collection::get();
        return inertia('Settings/Edit',['setting'=>$setting,'collections'=>$collections,'type'=>$type])->with(['page_name'=>__('edit settings')]);
    }
    public function mobile_edit($slug,$category_id=null){
        $mobile_banners=MobileBanner::with('collection')->where('slug',$slug)->get();
        $collections=Collection::get();
        $categories=Category::where('category_id',null)->get();
        return inertia('Settings/MobileEdit',['mobile_banners'=>$mobile_banners,'collections'=>$collections,'slug'=>$slug,'categories'=>$categories,'category_id'=>$category_id])->with(['page_name'=>__('edit settings')]);
    }
    public function mobile_delete(Request $request){
        $mobile_banner=MobileBanner::find($request->id);
        $mobile_banner->delete();
    }
    protected function validation(Request $request){
        if($request->slug=='top_slider' || $request->slug=='single_banner1' || $request->slug=='single_banner2' || $request->slug=='single_banner3' || $request->slug=='banners1'){
            $data=$request->validate([
                'images'=>'required|array',
                'collection_id'=>'required|numeric',
                'category_id'=>'nullable|numeric',
                'slug'=>'required|string',
            ]);
        }
        if($request->slug=='small_banners'|| $request->slug=='category_banners'){
            $data=$request->validate([
                'single_image'=>'required|string',
                'collection_id'=>'required|numeric',
                'category_id'=>'nullable|numeric',
                'slug'=>'required|string',
            ]);
        }
        if($request->slug=='shop_by_brand' ||$request->slug=='shop_by' || $request->slug=='category_banners'||$request->slug=='search_banners'|| $request->slug=='banners2'){
            $data=$request->validate([
                'text'=>'required|array',
                'single_image'=>'required|string',
                'collection_id'=>'required|numeric',
                'category_id'=>'nullable|numeric',
                'slug'=>'required|string',
            ]);
        }

        return $data;
    }
    public function mobile_store(Request $request){
        $data=$this->validation($request);
        $mobile_banner=MobileBanner::create($data);
        return MobileBanner::with('collection')->find($mobile_banner->id);
    }
    public function mobile_update(Request $request){
        $data=$this->validation($request);
        $mobile_banner=MobileBanner::find($request->id);
        $mobile_banner->update($data);
        return MobileBanner::with('collection')->find($mobile_banner->id);
    }
    public function update(Request $request){

        $allowedDomains = ['riya.com.sa', 'uat.riya.com.sa','localhost:8000','beyond-fix.applaiteknoloji.online'];
        
        $setting=Settings::findOrFail($request->id);
        if($setting->slug=='news_bar'){
            $data=$request->validate([
                'key'=>'required|array'
            ]);
        }
        if($setting->slug=='top_slider' || $setting->slug=='top_banner'|| $setting->slug=='banners2'|| $setting->slug=='small_banners' || $setting->slug=='social'|| $setting->slug=='category_banners'){
            $data=$request->validate([
                'value'=>'required|array'
            ]);
        }
        if($setting->slug=='latest_collection' || $setting->slug=='our_collection' || $setting->slug=='shop_by_brand' || $setting->slug=='banners3'|| $setting->slug=='shop_by_section'){
            $data=$request->validate([
                // 'link'=>['nullable','string',new DomainCheck($allowedDomains)],
                'key'=>'required|array',
                'value'=>'required|array'
            ]);
        }

        if($setting->slug=='single_banner' || $setting->slug=='single_banner2' || $setting->slug=='single_banner3'|| $setting->slug=='single_banner4'|| $setting->slug=='single_banner5'|| $setting->slug=='single_banner6'){
            $data=$request->validate([
                'link'=>['nullable','string',new DomainCheck($allowedDomains)],
                'value'=>'required|array'
            ]);
        }
        if($setting->slug=='shipping'){
            $data=$request->validate([
                'value'=>'required|numeric|min:0'
            ]);
        }
        if($setting->slug=='logo' || $setting->slug=='favicon'){
            $data=$request->validate([
                'value'=>'required|string'
            ]);
        }
        if($setting->slug=='popup'){
            $data=$request->validate([
                'value'=>'required|Array',
                'link'=>['nullable','string',new DomainCheck($allowedDomains)],
                'status'=>'nullable'
            ]);
        }
        if($setting->slug=='vat'){
            $data=$request->validate([
                'value' => 'required|numeric|min:0|max:100'
            ]);
        }
        $setting->update($data);

        return redirect()->back();
    }

    public function shipping_update(Request $request){
        $data=$request->validate([
            'price'=>'required|numeric|min:0',
            'free_shipping_start_at'=>'nullable|required_with:free_shipping_end_at|date',
            'free_shipping_end_at'=>'nullable|required_with:free_shipping_start_at|date',
            'free_shipping_start_at_amount'=>'nullable|numeric'
        ]);
        $shipping=Shipping::find($request->id);
        $shipping->update($data);
        return redirect()->back();
    }

    public function update_setting_status($id){
        $setting=Settings::find($id);
        $setting->update([
            'status'=>!$setting->status
        ]);
    }

}
