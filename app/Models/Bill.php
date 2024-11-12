<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable=[
        'bill_id',
        'user_name',
        'user_phone',
        'user_email',
        'user_id',
        'order_id',
        'paid_status',

    ];

    protected $appends=['date'];

    public function order(){
        
        return $this->belongsTo(Order::class,'order_id');
    }
    public function user(){
        
        return $this->belongsTo(User::class,'user_id');
    }

    public function getDateAttribute(){
        return $this->created_at->format('d, M Y');
    }
    
}
