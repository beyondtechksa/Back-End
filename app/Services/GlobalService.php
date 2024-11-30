<?php

namespace App\Services;


use App\Http\Enums\CacheEnums;
use App\Http\Resources\ProductResourceApiCollection;
use App\Http\Resources\ProductResourceCollection;
use App\Models\Cart;
use App\Models\CartDiscount;
use App\Models\Category;
use App\Models\MobileBanner;
use App\Models\Order;
use App\Models\Product;
use App\Models\Rates;
use App\Models\Settings;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Cache;


class GlobalService
{
    public function useCoupon($user_id, $coupon)
    {
        return CartDiscount::create([
            'user_id' => $user_id,
            'discount_percentage' => $coupon->discount_amount,
            'coupon_code' => $coupon->code,
        ]);
    }

    //new news
    public function news()
    {
        return Cache::remember(CacheEnums::NEWS, CacheEnums::CACHE_TIME, function () {
            return Settings::whereSlug('news_bar')
//                ->where('type', 'mobile_web')
                ->select('id', 'key', 'slug')->first();
        });
    }

    public function mobileBanners($category_id = null)
    {
//        $cache_key = CacheEnums::ALL_MOBILE_BANNER . '_' . $category_id;
//        return Cache::remember($cache_key, CacheEnums::CACHE_TIME, function () use ($category_id) {
        return MobileBanner::with(['collection:id,name,slug'])
            ->where('category_id', $category_id)
            ->select('id', 'slug', 'text', 'single_image', 'images', 'collection_id', 'category_id')
            ->get()
            ->groupBy('slug')
            ->map(function ($group) {
                return $group->map(function ($item) {
                    if ($item->images) {
                        $images = [
                            'en' => url($item->images['en']),
                            'ar' => url($item->images['ar']),
                        ];
                        $item->images = $images;
                    }
                    if ($item->single_image) {
                        $item->single_image = url($item->single_image);
                    }
                    return $item;
                });
            });
//        });
    }

    //banners

    //featured , new arrival and trending products
    public function homeProducts($currency, $type, $category_id = null)
    {
        $leafCategories = collect();
        $products = Product::with('brand:id,name,image,slug')
            ->where('status', 1)
            ->where($type, 1)
            ->latest()
            ->limit(24)
            ->when($category_id, function ($q, $category_id) use ($leafCategories) {
                $category = Category::find($category_id);
                $getLeafCategoriesRecursive = function ($category) use (&$getLeafCategoriesRecursive, &$leafCategories) {
                    if ($category->children()->count() === 0) {
                        $leafCategories->push($category->id);
                    } else {
                        foreach ($category->children as $child) {
                            $getLeafCategoriesRecursive($child);
                        }
                    }
                };
                $getLeafCategoriesRecursive($category);
                $q->whereIn('category_id', $leafCategories);
            })
            ->orderBy('ontop', 'desc')
//            ->orderByRaw('RAND() * id')
            ->get();
        return new ProductResourceCollection($products, $currency);
    }

    //user can only rate once and rate only ordered product
    public function checkIfCanRate($id, $canRate = false)
    {
        $purchased = (boolean)Order::where('user_id', user()->id)
            ->whereHas('order_items.product', function ($q) use ($id) {
                $q->where('id', $id);
            })->exists();
        if ($purchased) {
            $rate = Rates::where('product_id', $id)
                ->where('user_id', user()->id)->first();
            if (!$rate) $canRate = true;
        }
        return $canRate;
    }

    public function addToCart($request)
    {
        return Cart::create([
            'product_id' => $request['product_id'],
            'user_id' => user()->id,
            'quantity' => 1,
            'size' => $request['size'] ?? null,
            'color' => $request['color'] ?? null,
        ]);
    }

    public function get_user_cart(){
        $currencyService = new currencyService();
        $carts = Cart::with('product.brand','product.sizes','product.colors')->where('user_id', user()->id)->get();
        $carts->each(function ($cart) use ($currencyService){
            $cart->product->final_selling_price = $currencyService->convertPrice($cart->product,$cart->product->final_selling_price);
            $cart->product->old_price = $currencyService->convertPrice($cart->product,$cart->product->old_price);
        });
        return $carts;
    }
    public function get_user_selected_cart($user_id){
        $currencyService=new CurrencyService();
            $carts = Cart::with(['product' => function ($query) {
                $query->select('id', 'final_selling_price','tax_percentage', 'currency_id');
            }])
            ->where('user_id', $user_id)
            ->where('selected', 1)
            ->get();

            $carts->each(function ($cart) use ($currencyService){
                $cart->product->final_selling_price = $currencyService->convertPrice($cart->product,$cart->product->final_selling_price);
            });

        return $carts;
    }
}
