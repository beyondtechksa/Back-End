<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=[
        'name',
        'title',
        'desc',
        'slug',
        'show_in_footer_bar',
    ];

    public $translatable = ['name','title','desc'];
    protected $appends = ['translated_name', 'translated_title' , 'translated_desc'];

    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }
    public function getTranslatedTitleAttribute($value)
    {
        return @$this->title;
    }

    public function getTranslatedDescAttribute($value)
    {
        return @$this->desc;
    }
}
