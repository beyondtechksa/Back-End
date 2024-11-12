<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'attribute_id','option_id'];
    protected $appends=[
        // 'product_options',
        // 'attribute'
    ];
    // public function getProductOptionsAttribute()
    // {
    //     return ProductOption::where('product_attribute_id',$this->id)->get();
    // }
    public function product_options(){
        return $this->hasMany(ProductOption::class,'product_attribute_id');
    }
    public function attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id');
    }
    // public function getAttributeAttribute()
    // {
    //     return Attribute::find($this->attribute_id);
    // }

}
