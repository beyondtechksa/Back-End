<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDiscount extends Model
{
    use HasFactory;

    protected $fillable=[
        'discount_percentage',
        'user_id',
        'coupon_code',
        'status',
    ];
}
