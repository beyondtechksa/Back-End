<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'link',
        'image',
        'status',
    ];

    public $translatable = ['name'];
    protected $appends = ['translated_name',];

    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class)->where('status', 1);
    }


}
