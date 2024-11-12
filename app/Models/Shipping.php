<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable=[
    'price',
    'free_shipping_start_at',
    'free_shipping_end_at',
    'free_shipping_start_at_amount',
    'vat'
];

}
