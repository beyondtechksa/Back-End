<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'name',
        'fullname',
        'phone',
        'email',
        'password',
        'website',
        'speciality',
        'origin',
        'discount_percentage',
        'address',
        'note',
        'logo',
        'status',
    ];
 

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
    
    
}
