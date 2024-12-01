<?php

namespace App\Models;

use App\Http\Enums\CacheEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'single_image',
        'images',
        'collection_id',
        'category_id',
        'slug'
    ];

    protected $casts = [
        'text' => 'json',
        'images' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(fn() => clearGlobalCache(CacheEnums::CACHE_MOBILE_BANNERS()));
        static::updated(fn() => clearGlobalCache(CacheEnums::CACHE_MOBILE_BANNERS()));
        static::deleted(fn() => clearGlobalCache(CacheEnums::CACHE_MOBILE_BANNERS()));
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }
}
