<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable=['name','symbol','status','logo','is_default'];
    // public $translatable = ['name'];


    // public function getTranslatedNameAttribute($value)
    // {
    //     return @$this->name;
    // }
}
