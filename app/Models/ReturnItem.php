<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_request_id', 'order_item_id', 'quantity', 'status','return_status_id'
    ];

    public function return_request()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class);
    }
    public function return_status()
    {
        return $this->belongsTo(ReturnStatus::class);
    }

}
