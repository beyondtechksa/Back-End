<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'user_id', 'status', 'reason','return_reason_id','return_status_id','image'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function return_items()
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function return_reason(){
        return $this->belongsTo(ReturnReason::class);
    }
    public function return_status(){
        return $this->belongsTo(ReturnStatus::class);
    }


}
