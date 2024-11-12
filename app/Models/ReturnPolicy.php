<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ReturnPolicy extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=['name','desc','status','period'];

    public $translatable = ['name','desc'];
    protected $appends=['translated_name','translated_desc'];

    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }
    public function getTranslatedDescAttribute($value)
    {
        return @$this->desc;
    }
}
