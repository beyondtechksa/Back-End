<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResourceCollection extends ResourceCollection
{
    protected $currency;

    public function __construct($resource, $currency = 'SAR')
    {

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
            $this->collection->transform(function ($product) {
                if ($product->brand && $product->brand->image)
                    $product->brand->image = url($product->brand->image);
                return (new ProductResource($product, $this->currency))->toArray(null);
            })
        ];
    }
}
