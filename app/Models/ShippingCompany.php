<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ShippingCompany extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable=[
        'name',
        'phone',
        'email',
        'password',
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

    
}
