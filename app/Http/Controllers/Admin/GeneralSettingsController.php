<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;


class GeneralSettingsController extends Controller
{
    public function index(){
        if (admin()->hasPermissionTo('edit settings')) {
            $settings=GeneralSetting::get();
            return inertia('GeneralSettings/Index',['settings'=>$settings])->with('page_name',__('general settings'));
        } else {
            return no_permission_redirect();
        }

        
    }
    public function edit($id){
        $setting=GeneralSetting::findOrFail($id);
        return inertia('GeneralSettings/Edit',['setting'=>$setting])->with('page_name',__('general settings'));
    }

    public function update(Request $request,$id){
        $setting=GeneralSetting::findOrFail($id);
        $data=$request->validate([
            'value'=>'required|string|max:20000'
        ]);
        $setting->update($data);
        return redirect()->route('general_settings.index')->with('success','updated successfully');
    }
}
