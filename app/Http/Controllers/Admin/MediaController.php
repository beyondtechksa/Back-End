<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaCategory;
use App\Models\Media;
use Illuminate\Support\Str;
class MediaController extends Controller
{
    public function get_media_categories($type){
        return MediaCategory::where('media_type',$type)->latest()->get();
    }
    public function get_media(Request $request,$type,$media_category_id=null){
        $limit = $request->input('limit', 20);  // Default to 20 records
        $offset = $request->input('offset', 0); // Default to 0 offset
        $media=Media::where('media_type',$type)->latest();
        if($media_category_id){
            $media->where('media_category_id',$media_category_id);
        }
        $media=$media->offset($offset)->limit($limit)->get();
        return $media;
    }

    public function create_media_category(Request $request){
        $data=$request->validate([
            'name'=>'required|string|max:40',
            'media_type'=>'nullable|string|max:40'
        ]);
        $media=MediaCategory::create($data);
        return MediaCategory::find($media->id);
    }
    public function update_media_category(Request $request){
        $data=$request->validate([
            'name'=>'required|string|max:40',
        ]);
        $media=MediaCategory::find($request->id)->update($data);
        return MediaCategory::find($request->id);
    }

    public function delete_media_category(Request $request){
        $category=MediaCategory::find($request->id);
        Media::destroy(get_media($category->id)->pluck('id'));
        foreach(get_media($category->id) as $file){
            \File::delete(get_main_path($file->path));
        }
        $category->delete();
        return $request->id;
    }


    public function upload_file(Request $request){
        // return $request->media_type;
        $request->validate([
            'media_category_id'=>'nullable|numeric',
            'file'=>'required|file|image|max:102400'
        ]);
        $category=$request->media_category_id!=null?MediaCategory::find($request->media_category_id)->name:null;
        $destinationPath= $category==null?'uploads':'uploads/'.$category;
        $file=$request->file('file');
        $size=$file->getSize();
        $extension=$file->getClientOriginalExtension();
        $type=$file->getMimeType();
        $filename=$file->getClientOriginalName();
        $random=Str::random(30);
        $file->move($destinationPath,$random.$filename);
        $data_file= '/'.$destinationPath.'/'.$random.$filename;
        $media=Media::create([
            'name'=>$filename,
            'path'=>$data_file,
            'media_category_id'=>$request->media_category_id,
            'size'=>$size,
            'extension'=>$extension,
            'type'=>$type,
            'media_type'=>$request->media_type
        ]);
        return Media::find($media->id);
    }

    public function delete_media(Request $request){
        $media=Media::find($request->id);
        \File::delete(get_main_path($media->path));
        $media->delete();
        return $request->id;
    }
}
