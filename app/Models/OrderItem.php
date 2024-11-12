<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable=[
        'order_id',
        'product_id',
        'unit_price',
        'quantity',
        'taxable_amount',
        'tax_percentage',
        'tax_amount',
        'price',
        'size',
        'color',
    ];
    protected $translatable=['size','color'];
    protected $appends=['translated_size','translated_color'];

    public function getTranslatedSizeAttribute($value)
    {
        return @$this->size;
    }
    public function getTranslatedColorAttribute($value)
    {
        return @$this->color;
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id')->withTrashed();
    }
    public function company_request()
    {
        return $this->hasOne(CompanyRequest::class, 'order_item_id');
    }
    public function return_items()
    {
        return $this->hasMany(ReturnItem::class);
    }

}
