<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempProduct extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_id',
        'admin_id',
        "vendor_id",
        'sku',
        'name',
        'name_ar',
        'desc',
        'desc_ar',
        'discount_price',
        'discount_percentage',
        'final_price',
        'price',
        'link',
        'links_in_url',
        'images',
        'attributes',
        'type',
        'group_link',
        'status',
        'stock_status',
        "company_name",
        'sizes_ids',
        'colors_ids',
        'currency_id',
    ];

    protected $appends=['admin'];
    protected $casts = [
        'attributes' => 'json',
        'images' => 'json',
        'colors_ids' => 'json',
        'sizes_ids' => 'json'
    ];
    public function getAdminAttribute(){
        return Admin::find($this->admin_id);
    }


}
