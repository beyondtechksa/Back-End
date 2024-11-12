<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Collection extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=[
        'name',
        'slug',
        'image',
        'status',
        'admin_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($collection) {
            if (Auth::guard('admin')->check()) {
                $collection->admin_id = admin()->id;
            }
        });
        static::updating(function ($collection) {
            if (Auth::guard('admin')->check()) {
                $collection->admin_id = admin()->id;
            }
        });

    }
    

    public $translatable = ['name','image'];
    protected $appends=['translated_name','translated_image'];

    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }
    public function getTranslatedImageAttribute($value)
    {
        return @$this->image;
    }
    
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }

}
