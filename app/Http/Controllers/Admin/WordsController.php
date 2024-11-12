<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Word;
use File;

class WordsController extends Controller
{
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $data = $request->validate([
			'key' => 'required|string|max:255',
			'value' => 'required|string|max:255',
			'language_id' => 'required|numeric',
			'url' => 'nullable|string|max:255'
		]);

		Word::create($data);
		$language = Language::find($request->language_id);
		$path = resource_path('lang/' . $language->symbol . '.json');

		$inp = file_get_contents($path);
		$tempArray = json_decode($inp, true);
		$added_element = [$request->key => $request->value];
		$tempArray = array_merge($tempArray, $added_element); // Merge the arrays
		$jsonData = json_encode($tempArray);
		file_put_contents($path, $jsonData);
		return redirect()->back()->with('success', __('item created successfully'));

    }


    
    public function update(Request $request, Word $word)
    {
        $data=$request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'language_id' => 'required|numeric',
            'url'=>'nullable|string|max:255'
        ]);
        $word->update($data);
        $words=Word::where("language_id",$request->language_id)->pluck('value','key')->toArray();
        $language=Language::find($request->language_id);
        $path = resource_path('lang/' . $language->symbol . '.json');
		$jsonData = json_encode($words);
		file_put_contents($path, $jsonData);
        return redirect()->back()->with('success',__('item updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word $word)
    {
        $word->delete();
        return redirect()->back()->with('success',__('item deleted successfully'));
    }
   

    public function filter(Request $request){
        $words=Word::latest();
        if($request->search){
            $words->where('key','like','%'.$request->search.'%');
        }
        $data=$words->where('language_id',$request->language_id)->paginate(50);
        $language=Language::find($request->language_id);
        if($language->is_default){
            return inertia('Words/Index',['words'=>$data]);
        }else{
            return inertia('Words/Index2',['words'=>$data]);
        }
    }
}
