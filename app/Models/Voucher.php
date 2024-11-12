<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'code',
        'reference',
        'reference_details',
        'currency_id',
        'amount',
        'valid_untill',
        'status',
    ];

    protected $appends=['currency'];
    
    public function getCurrencyAttribute(){
        return Currency::find($this->currency_id);
    }
    
}
