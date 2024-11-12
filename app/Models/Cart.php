<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Cart extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'product_id',
        'quantity',
        'size',
        'color',
        'selected',
    ];

    protected $casts=[
        'attributes'=>'json'
    ];


    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
