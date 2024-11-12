<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceFormula extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'note',
        'discount_percentage_selling_price',  // vat
        'packaging_shipping_fees',
        'management_fees',
        'profit_percentage',
        'commercial_percentage',
        'manual_price_adjustment',
    ];
}
