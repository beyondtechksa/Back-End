<?php

namespace App\Models;

use App\Http\Enums\CacheEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class Settings extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=[
    'name','slug','key','value','link','type','status'
    ];

    protected $casts=[
        'value'=>'json',
    ];
    public $translatable = ['key'];
    protected $appends=['translated_key'];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($setting) {
            self::clearCachedSettings();
        });
        static::updated(function ($setting) {
            self::clearCachedSettings();
        });

        static::deleted(function ($setting) {
            self::clearCachedSettings();
        });
    }
    public static function clearCachedSettings()
    {
        foreach (CacheEnums::CACHE_SETTINGS as $cacheKey) {
            Cache::forget($cacheKey);
        }
    }
    public function getTranslatedKeyAttribute($value)
    {
        return @$this->key;
    }
}
