<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'provider_id',
        'provider',
        'uuid',
        'gender',
        'apple_id',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'carts' => 'json',
        'favourites' => 'json',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id');
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class, 'user_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'user_id', 'id')
            ->where('favourite', 1);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id', 'id')->orderBy('favourite', 'desc');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function payment_transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
}
