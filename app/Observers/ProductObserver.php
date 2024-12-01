<?php

namespace App\Observers;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\SkuGenerator;
use App\Http\Enums\CacheEnums;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function creating(Product $product)
        {
            $product->sku = SkuGenerator::generateUniqueSku();
            if (Auth::guard('admin')->check()) {
                $product->admin_id = Auth::guard('admin')->id();
            }
            $product->slug=unique_slug($product->name_tr??$product->name_en,'Product');
        }

    public function updating(Product $product)
        {
            if (preg_match('/^BD-\d+$/', $product->sku)) {
                $product->sku = SkuGenerator::generateUniqueSku();
            }
            if (Auth::guard('admin')->check()) {
                $product->admin_id = Auth::guard('admin')->id();
            }
            if(!$product->slug){
                $product->slug=unique_slug($product->name_tr,'Product');
            }
        }

    public function saving(Product $product)
    {
        // Calculate the old price if discount is set
        if ($product->discount_percentage_selling_price > 0) {
            $product->old_price = $product->final_selling_price / (1 - ($product->discount_percentage_selling_price / 100));
        } else {
            $product->old_price = $product->final_selling_price; // No discount applied
        }
    }

    public function created(){
        $this->clearCache();
    }
    public function updated(){
        $this->clearCache();
    }

    private function clearCache()
    {
        clearGlobalCache(CacheEnums::CACHE_PRODUCTS());
        Cache::forget(CacheEnums::HOMEPAGECACHE());
    }


}
