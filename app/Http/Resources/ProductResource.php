<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    protected $currency;

    public function __construct($resource,$currency='SAR'){

        parent::__construct($resource);
        // Assign the currency
        $this->currency = $currency;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'sku'=>$this->sku,
            'name_en'=>$this->name_en,
            'name_ar'=>$this->name_ar,
            'name_tr'=>$this->name_tr,
            'desc_en'=>$this->desc_en,
            'desc_tr'=>$this->desc_tr,
            'desc_ar'=>$this->desc_ar,
            'final_selling_price'=>exchange_price($this->final_selling_price,$this->currency),
            'old_price'=>exchange_price($this->old_price,$this->currency),
            'discount_percentage'=>$this->discount_percentage_selling_price,
            'image'=>$this->image,
            'brand'=>$this->brand,
            'discount_percentage_selling_price'=>$this->discount_percentage_selling_price,
            'category_id' => $this->category_id,
            'rate'=>$this->rate
        ];
    }
}
