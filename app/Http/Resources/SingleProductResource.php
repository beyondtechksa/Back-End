<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Services\CurrencyService;

class SingleProductResource extends JsonResource
{
    protected $currency;

    public function __construct($resource, $currency = 'SAR')
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->currency = $currency;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->group_id == null) {
            $groups= Product::where('group_id', $this->id)->orWhere('id', $this->id)->select('image','id')->get();
        } else {
            $groups= Product::whereIn('id', [$this->group_id, $this->id])->orWhere('group_id', $this->group_id)->select('image','id')->get();
        }

        $currencyService=new CurrencyService();
        $final_selling_price = $currencyService->convertPrice($this,$this->final_selling_price);
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'slug' => $this->slug,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'name_tr' => $this->name_tr,
            'desc_en' => $this->desc_en,
            'desc_tr' => $this->desc_tr,
            'desc_ar' => $this->desc_ar,
            'image' => $this->image,
            'old_price' => number_format($final_selling_price / (1-($this->discount_percentage_selling_price/100)),2),
            'discount_percentage_selling_price' => $this->discount_percentage_selling_price,
            'final_selling_price' => $final_selling_price,
//            'final_selling_price'=>$this->final_selling_price,
            'discount_price' => $this->discount_percentage_selling_price,
            'files' => $this->files->select('id', 'image'),
            'brand' => $this->brand,
            'rates' => $this->rates,
            'group_id' => $this->group_id,
            'colors' => $this->colors,
            'sizes' => $this->sizes,
            'currency' => $this->currency,
            'groups' => $groups,
            'product_attributes' => $this->product_attributes,
            'rated' => $this->rated,
            'stock_status' => $this->stock_status,
        ];
    }
}
