<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResourceApiCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($product) {
                if ($product->brand && $product->brand->image) {
                    $product->brand->image = url($product->brand->image); // Ensure the image URL is full
                }
                return (new ProductResource($product, $this->currency))->toArray(null);
            }),
            // Add pagination meta data
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ]
        ];
    }
}
