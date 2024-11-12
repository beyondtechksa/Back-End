<?php

namespace App\Models;

use App\Http\Enums\CacheEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;
class Color extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=['name','name_tr','color_code','color_id','image','status'];
    protected $translatable=['name'];
    protected $appends=['translated_name'];

    protected static function boot()
    {
        parent::boot();
        static::created(function () {
            Cache::forget(CacheEnums::COLORS);
        });
        static::updated(function () {
            Cache::forget(CacheEnums::COLORS);
        });
        static::deleted(function () {
            Cache::forget(CacheEnums::COLORS);
        });
    }
    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }

    public function color(){
        return $this->belongsTo(Color::class,'color_id');
    }
    public function colors(){
        return $this->hasMany(Color::class,'color_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
