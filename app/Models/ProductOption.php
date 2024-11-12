<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    protected $fillable = ['option_id', 'product_attribute_id', 'price', 'quantity'];

    // protected $appends=['option'];

    // public function getOptionAttribute(){
    //     return Option::find($this->option_id);
    // }


    public function option(){
        return $this->belongsTo(Option::class,'option_id');
    }
}
