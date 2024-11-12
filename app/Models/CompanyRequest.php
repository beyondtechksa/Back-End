<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRequest extends Model
{
    use HasFactory;

    protected $fillable=[
        'vendor_id',
        'user_id',
        'order_id',
        'order_item_id',
        'company_product_id',
        'company_name',
        'status',
        'vendor_status',
        'traking_code',
        'external_shipping_company_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function order_item(){
        return $this->belongsTo(OrderItem::class,'order_item_id');
    }
    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }
    public function external_shipping_company(){
        return $this->belongsTo(ExternalShippingCompany::class,'external_shipping_company_id');
    }
}
