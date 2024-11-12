<?php

namespace App\Models;

use App\Http\Enums\CacheEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=['name','name_tr','size_id','status'];
    protected $translatable=['name'];
    protected $appends=['translated_name'];

    protected static function boot()
    {
        parent::boot();
        static::created(function () {
            Cache::forget(CacheEnums::SIZES);
        });
        static::updated(function () {
            Cache::forget(CacheEnums::SIZES);
        });
        static::deleted(function () {
            Cache::forget(CacheEnums::SIZES);
        });
    }
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
