<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaCategory extends Model
{
    use HasFactory;
    protected $fillable=['name','media_type'];

    protected $appends=['media_count'];

    public function getMediaCountAttribute(){
        return Media::where('media_category_id',$this->id)->count();
    }
}
