<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'subtotal_amount',
        'shipping',
        'discount',
        'total_amount',
        'address',
        'status',
        'payment_id',
        'vat',
        'currency',
        'traking_code',
        'external_shipping_company_id',
        'weight',
        'company_shipping',

    ];
    protected $casts=[
        'address'=>'json'
    ];

    // 0 => pending
    // 1 => refused
    // 2 => accepted
    // 3 => in house
    // 4 => processing
    // 5 => shipping
    // 6 => delivered


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class,'order_id');
    }
    public function company_requests(){
        return $this->hasMany(CompanyRequest::class,'order_id');
    }

    public function external_shipping_company(){
        return $this->belongsTo(ExternalShippingCompany::class,'external_shipping_company_id');
    }

    public function return_requests()
    {
        return $this->hasMany(ReturnRequest::class);
    }



}
