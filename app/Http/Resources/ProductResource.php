<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
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
        $final_selling_price = $this->final_selling_price;
        return [
            'id'=>$this->id,
            'sku'=>$this->sku,
            'name_en'=>$this->name_en,
            'name_ar'=>$this->name_ar,
            'name_tr'=>$this->name_tr,
            'desc_en'=>$this->desc_en,
            'desc_tr'=>$this->desc_tr,
            'desc_ar'=>$this->desc_ar,
            'old_price' => number_format($final_selling_price / (1-($this->discount_percentage_selling_price/100)),2),
            'discount_percentage_selling_price' => $this->discount_percentage_selling_price,
            'final_selling_price' => $final_selling_price,
            'image'=>$this->image,
            'brand'=>$this->brand,
            'discount_percentage_selling_price'=>$this->discount_percentage_selling_price,
            'category_id' => $this->category_id,
            'rate'=>$this->rate
        ];
    }
}
