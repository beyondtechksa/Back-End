<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable=[
        'first_name',
        'last_name',
        'company_name',
        'type',
        'details',
        'user_id',
        'favourite',
        'postal_code',
        'tax_id',
        'street',
        'city',
        'country',
    ];
}
