<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Collection;


class FilterService
{
    public function filter($filterData)
    {
        $query = Product::withRated()->where('status', 1);

        if (!empty($filterData['subCategories'])) {
            $query->whereIn('category_id', $filterData['subCategories']);
        }

        if (!empty($filterData['brands'])) {
            $query->whereIn('brand_id', $filterData['brands']);
        }

//        if (isset($filterData['stock'])) {
//            $query->where('stock_status', $filterData['stock']);
//        }

        if (!empty($filterData['price']) && is_array($filterData['price'])) {
            $query->whereBetween('final_selling_price', $filterData['price']);
        }

        if (!empty($filterData['discount']) && is_array($filterData['discount'])) {
            $query->whereBetween('discount_percentage_selling_price', $filterData['discount']);
        }

        if (!empty($filterData['size'])) {
            $sizeId=$filterData['size'];
            $query->whereHas('sizes', function ($q) use ($sizeId) {
                $q->where('sizes.id', $sizeId);
            });
        }

        if (!empty($filterData['color'])) {
            // $query->whereJsonContains('colors_ids', $filterData['color']);
            $colorId=$filterData['color'];
            $query->whereHas('colors', function($q) use ($colorId) {
                $q->where('color_product.color_id', $colorId);
            });
        }

        if (isset($filterData['slug'])) {
            $collection = Collection::where('slug', 'like', '%' . $filterData['slug'] . '%')->firstOrFail();
            $query->where('collection_id', $collection->id);
        }


        if (isset($filterData['sort'])) {
            $this->sortData($filterData['sort'], $query);
        }
        if (isset($filterData['rate'])) {
            $query->orderBy('rated', 'desc');
        }
        if (isset($filterData['product_type'])) {
            if ($filterData['product_type'] == 'trending')
                $query->where('trending', 1);
            if ($filterData['product_type'] == 'featured')
                $query->where('featured', 1);
            if ($filterData['product_type'] == 'new_arrival')
                $query->where('new_arrival', 1);
        }


        $limit = $filterData['limit'] ?? 12;
        $offset = $filterData['offset'] ?? 0;

        return $query->with(['brand:id,name'])
            ->limit($limit)
            ->orderBy('ontop', 'desc')
            ->offset($offset)
//            ->inRandomOrder()
            ->get();

    }

    public function sortData($sort, $query): void
    {
        switch ($sort) {
            case 'asc':
                $query->orderBy('id', 'ASC');
                break;
            case 'desc':
                $query->orderBy('id', 'DESC');
                break;
            case 'high_discount':
                $query->orderBy('discount_percentage_selling_price', 'DESC');
                break;
            case 'low_price':
                $query->orderBy('final_selling_price', 'ASC');
                break;
            case 'high_price':
                $query->orderBy('final_selling_price', 'DESC');
                break;
            default:
                $query->orderBy('id', 'ASC');
        }
    }

    public function search($searchData)
    {
        $limit = $searchData['limit'] ?? 12;
        $offset = $searchData['offset'] ?? 0;
        $search = $searchData['search'];
        return Product::with('brand')
            ->where('status', 1)
            ->where('name_en', 'LIKE', "%{$search}%")
            ->orWhere('name_ar', 'LIKE', "%{$search}%")
            ->orderBy('ontop', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

    }
}
