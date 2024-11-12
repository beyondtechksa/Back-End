<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ReturnStatus extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable=['name'];

    public $translatable = ['name'];
    protected $appends=['translated_name'];

    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }

}
