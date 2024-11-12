<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'code',
        'reference',
        'reference_details',
        'discount_type',
        'coupon_type',
        'discount_amount',
        'valid_untill',
        'status',
    ];
}
