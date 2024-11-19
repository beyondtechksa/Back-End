<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=['name','name_tr','size_id','status'];
    protected $translatable=['name'];
    protected $appends=['translated_name'];


    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }

    public function size(){
        return $this->belongsTo(Size::class,'size_id');
    }
    public function sizes(){
        return $this->hasMany(Size::class,'size_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('in_stock');
    }
}
