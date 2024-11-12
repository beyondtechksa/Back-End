<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;

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

        $colors_ids=$this->colors_ids!=null?(array)$this->colors_ids:[];
        $sizes_ids=$this->sizes_ids!=null?$this->sizes_ids:[];


        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'name_tr' => $this->name_tr,
            'desc_en' => $this->desc_en,
            'desc_tr' => $this->desc_tr,
            'desc_ar' => $this->desc_ar,
            'image' => $this->image,
            'old_price' => exchange_price($this->old_price, $this->currency),
            'discount_percentage_selling_price' => $this->discount_percentage_selling_price,
            'final_selling_price' => exchange_price($this->final_selling_price, $this->currency),
//            'final_selling_price'=>$this->final_selling_price,
            'discount_price' => $this->discount_percentage_selling_price,
            'files' => $this->files->select('id', 'image'),
            'brand' => $this->brand,
            'rates' => $this->rates,
            'group_id' => $this->group_id,
            'colors_ids' => $colors_ids,
            'sizes_ids' => $sizes_ids,
            'colors' => $this->colors,
            'sizes' => $this->sizes,
            'groups' => $groups,
            'product_attributes' => $this->product_attributes,
            'rated' => $this->rated,
            'stock_status' => $this->stock_status,
        ];
    }
}
