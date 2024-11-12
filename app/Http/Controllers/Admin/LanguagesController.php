<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Word;
use Illuminate\Validation\Rules;
use File;

class LanguagesController extends Controller
{
    public function __construct(){
        $this->index_page_name=__('languages');
        $this->create_page_name=__('create language');
        $this->edit_page_name=__('edit language');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages=Language::latest()->paginate(5);
        if(admin()->hasPermissionTo('view language')){
        return inertia('Languages/Index',['languages'=>$languages])->with(['page_name'=>$this->index_page_name]);
        }else{
            return  no_permission_redirect();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Language $language)
    {
        $words=Word::where('language_id',$language->id)->latest()->paginate(50);
        if($language->is_default){
            return inertia('Words/Index',['words'=>$words,'language'=>$language])->with(['page_name'=>__('words')]);
        }else{
            $main_lang=Language::where('is_default',1)->first();
            $main_keys=Word::where('language_id',$main_lang->id)->pluck('key')->toArray();
            $keys=Word::where('language_id',$language->id)->pluck('key')->toArray();
            $diff=array_diff($main_keys,$keys);  // keys exist in the main lang and not exist in the selected lang
            foreach($diff as $dif){
                Word::create([
                    'key'=>$dif,
                    'language_id'=>$language->id,
                ]);
            }
            //delete unexisting keys in the main lang
            $diff2=array_diff($keys,$main_keys); // keys exist in the main lang and not exist in the selected lang
            $deleted_ids=Word::where('language_id',$language->id)->whereIn('key',$diff2)->pluck('id');
            Word::destroy($deleted_ids);
            //delete unexisting keys in the main lang
            $words=Word::where('language_id',$language->id)->latest()->paginate(50);
            $keys_with_url=Word::where("language_id",$main_lang->id)->select('key','url')->get();
            return inertia('Words/Index2',['words'=>$words,'language'=>$language,'keys_with_url'=>$keys_with_url])->with(['page_name'=>__('words')]);

        }
    }

    public function create()
    {
        if(admin()->hasPermissionTo('add language')){
        return inertia('Languages/Create')->with(['page_name'=>$this->create_page_name]);
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
            'symbol' => 'required|string|max:255|unique:languages',
            'logo' => 'required|string|max:255',
        ]);
        if($request->logo){
            $data['logo']=$request->logo;
        }
        Language::create($data);
        $path=resource_path('lang/'.$request->symbol.'.json');
        if(!file_exists($path)){
            fopen($path,'w');
            file_put_contents($path,'{}');
        }
        return redirect()->route('languages.index')->with('success',__('item created successfully'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language)
    {
        if(admin()->hasPermissionTo('edit language')){
        return inertia('Languages/Edit',compact('language'))->with(['page_name'=>$this->edit_page_name]);
        }else{
         return  no_permission_redirect();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:255|unique:languages,symbol,'.$language->id,
            'logo' => 'nullable|string|max:255',
        ]);
        $data=[];
        $data['name']=$request->name;
        $data['symbol']=$request->symbol;
        if($request->status!==null){
            $data['status']=$request->status;
        }
        if($request->logo){
            $data['logo']=$request->logo;
        }
        
        $language->update($data);
        return redirect()->route('languages.index')->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language)
    {
        $language->delete();
        return redirect()->route('languages.index')->with('success',__('item deleted successfully'));
    }
    public function multi_destroy(Request $request)
    {
        if(count($request->checked)>0){
            Language::destroy($request->checked);
            return redirect()->route('languages.index')->with('success',__('item deleted successfully'));
        }else{
            return redirect()->route('languages.index');
        }
    }

    public function filter(Request $request){
        $languages=Language::latest();
        if($request->search){
            $languages->where('name','like','%'.$request->search.'%');
        }
        $data=$languages->paginate(5);
        return inertia('Languages/Index',['languages'=>$data]);
    }
}
