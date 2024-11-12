<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Attribute extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=[
        'name',
        'type',
        'admin_id',
        'list',
    ];

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($attribute) {
            if (Auth::guard('admin')->check()) {
                $attribute->admin_id = admin()->id;
            }
        });
        static::creating(function ($attribute) {
            if (Auth::guard('admin')->check()) {
                $attribute->admin_id = admin()->id;
            }
        });
        
    }
    public $translatable = ['name'];
    protected $appends=[
        'translated_name',
        // 'options'
    ];

    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }

    public function options(){
        return $this->hasMany(Option::class,'attribute_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
}
