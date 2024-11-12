<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrakingProduct extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_id','company_product_id'
    ];
    protected $casts = [
        'images' => 'json',
        'colors_ids' => 'json',
        'sizes_ids' => 'json'
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id')->withTrashed();
    }

}
