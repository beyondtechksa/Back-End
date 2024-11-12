<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Currency extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'note',
        'flag',
        'prefix',
        'exchange_rate',
        // 'shipping_fees',
        // 'free_shipping_amount',
        // 'country_tax',
        // 'country_tax_prefix',
        'main',
        'admin_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($currency) {
            if (Auth::guard('admin')->check()) {
                $currency->admin_id = admin()->id;
            }
        });
        static::updating(function ($currency) {
            if (Auth::guard('admin')->check()) {
                $currency->admin_id = admin()->id;
            }
        });

    }

    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
    

}
