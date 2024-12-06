<?php

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Brand;
use App\Models\Media;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\Favourite;
use Illuminate\Support\Str;
use App\Models\CartDiscount;
use App\Models\ProductOption;
use App\Http\Enums\CacheEnums;
use App\Models\ProductAttribute;
use App\Models\PriceFormula;
use App\Models\Bill;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cookie;
use App\Services\CurrencyService;


if (!function_exists('admin')) {
    function admin()
    {
        $admin = auth()->guard('admin')->user();
        // $admin['permissions']=$admin->getPermissionsViaRoles()->pluck('name');
        return $admin;
    }
}
if (!function_exists('vendor')) {
    function vendor()
    {
        $vendor = auth()->guard('vendor')->user();
        // $vendor['permissions']=$vendor->getPermissionsViaRoles()->pluck('name');
        return $vendor;
    }
}

if (!function_exists('shipping')) {
    function shipping()
    {
        $shipping = auth()->guard('shipping')->user();
        // $shipping['permissions']=$shipping->getPermissionsViaRoles()->pluck('name');
        return $shipping;
    }
}

if (!function_exists('user')) {
    function user()
    {
        if (auth()->check()) {
            // $currencyService=new currencyService();
            $user = User::with('favourites')->find(auth()->user()->id);
            // $user['carts']=$user->carts->map(function($cart) use($currencyService){
            //     $cart->product->final_selling_price = $currencyService->convertPrice($cart->product,$cart->product->final_selling_price);
            //     return $cart;
            // });
            $user['cart_discount'] = CartDiscount::where('user_id', $user->id)->where('status', 0)->first();
            return $user;
        } else {
            return '';
        }
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($file, $path = null)
    {
        //Move Uploaded File
        $destinationPath = $path == null ? 'uploads' : 'uploads/' . $path;
        $filename = $file->getClientOriginalName();
        $random = Str::random(30);
        $file->move($destinationPath, $random . $filename);
        return '/' . $destinationPath . '/' . $random . $filename;
    }
}

if (!function_exists('get_main_path')) {
    function get_main_path($file)
    {
        return public_path($file); // in local
        // return base_path($file);   // in server
    }
}

if (!function_exists('translations')) {

    function translations($json)
    {
        if (!file_exists($json)) {
            return [];
        }

        return json_decode(file_get_contents($json), true);
    }
}

if (!function_exists('locales')) {
    function locales()
    {
        return Cache::remember(CacheEnums::ACTIVE_LANGUAGES(), CacheEnums::CACHE_TIME, function () {
            return Language::where('status', 1)->get();
        });
    }
}
if (!function_exists('languages')) {
    function languages()
    {
        return Cache::remember(CacheEnums::ACTIVE_LANGUAGES(), CacheEnums::CACHE_TIME, function () {
            return Language::where('status', 1)->get();
        });
    }
}
if (!function_exists('locale')) {
    function locale()
    {
        $cacheKey = CacheEnums::LOCAL_LANGUAGES() . app()->getLocale();

        return Cache::remember($cacheKey, CacheEnums::CACHE_TIME, function () {
            $language = Language::where('symbol', app()->getLocale())->first();
            return $language ? $language->symbol : null;
        });
    }
}
if (!function_exists('image_validation')) {
    function image_validation()
    {
        return 'image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:max_width=3000,max_height=3000';
    }
}
if (!function_exists('no_permission_redirect')) {
    function no_permission_redirect()
    {
        return redirect()->back();
    }
}
if (!function_exists('music')) {
    function music()
    {
        return Music::get();
    }
}
if (!function_exists('get_media')) {
    function get_media($category_id = null)
    {
        if ($category_id) {
            return Media::where('media_category_id', $category_id)->get();
        } else {
            return Media::get();
        }
    }
}

if (!function_exists('decimal_validation')) {
    function decimal_validation()
    {
        return 'regex:/^\d+(\.\d{1,2})?$/';
    }
}

if (!function_exists('getAllParents')) {
    function getAllParents(Category $category)
    {
        $parents = $category->allParents()->get();

        $allParents = collect();
        foreach ($parents as $parent) {
            $allParents->push($parent);
            if ($parent->parent) {
                $allParents = $allParents->merge(getAllParents($parent));
            }
        }

        return $allParents;
    }
}
if (!function_exists('getAllChildren')) {
    function getAllChildren(Category $category)
    {
        $children = $category->allChildren()->orderBy('list', 'asc')->get();

        $allChildren = collect();
        foreach ($children as $child) {
            $allChildren->push($child);
            if ($child->children->isNotEmpty()) {
                $allChildren = $allChildren->merge(getAllChildren($child));
            }
        }

        return $allChildren;
    }
}
if (!function_exists('categories_with_parents')) {
    function categories_with_parents()
    {
        return Cache::remember(CacheEnums::CATEGORIES_WITH_PARENTS(), CacheEnums::CACHE_TIME, function () {
            return Category::with([
                'children' => function ($query) {
                    $query->with('children')->where('status',true);
                }
            ])->whereNull('category_id')->where('status', true)->orderBy('list', 'asc')->get();
        });
    }
}
// if(!function_exists('categories_with_parents')){
// function  ()
// {
//     $catgeoires = [];
//     $parents = Category::with('children', 'children.children')->whereNull('category_id')->orderBy('list', 'asc')->get();

//     foreach ($parents as $parent) {
//         // push parent
//         $parent['parents_name'] = get_parents_name($parent);
//         array_push($catgeoires, $parent);

//         // push children
//         foreach ($parent->children as $child) {
//             $child['parents_name'] = get_parents_name($child);
//             array_push($catgeoires, $child);

//             foreach ($child->children as $child2) {
//                 $child2['parents_name'] = get_parents_name($child2);
//                 array_push($catgeoires, $child2);
//             }
//         }
//     }
//     return $catgeoires;
// }
// }

if (!function_exists('get_parents_name')) {
    function get_parents_name(Category $category)
    {
        $parents_name = $category->translated_name;
        foreach (getAllParents($category) as $parent) {
            $parents_name .= '/' . $parent->translated_name;
        }
        return $parents_name;
    }
}


if (!function_exists('create_product_attributes')) {
    function create_product_attributes($attributes_ids, $product_id)
    {
        // $existed_attributes=ProductAttribute::where('product_id',$product_id)->pluck('id');
        // if(count($existed_attributes)>0){
        //     ProductAttribute::destroy($existed_attributes);
        // }

        if (!empty($attributes_ids)) {
            foreach ($attributes_ids as $attribute) {
                if (!empty($attribute['options'])) {
                    $attrib = ProductAttribute::where('attribute_id', $attribute['id'])->where('product_id', $product_id)->first();
                    if (!$attrib) {
                        $attrib = ProductAttribute::create([
                            'product_id' => $product_id,
                            'attribute_id' => $attribute['id'],
                        ]);
                    }

                    foreach ($attribute['options'] as $option) {
                        if (array_key_exists('status', $option)) {
                            if ($option['status']) {
                                ProductOption::create([
                                    'option_id' => $option['id'],
                                    'product_attribute_id' => $attrib->id,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}

if (!function_exists('returnSuccess')) {
    function returnSuccess($key, $value, $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $message,
            $key => $value
        ], 200);
    }
}
if (!function_exists('currencies')) {
    function currencies()
    {
        return Cache::remember(CacheEnums::CURRENCIES(), CacheEnums::CACHE_TIME, function () {
            return Currency::get();
        });
    }
}
if (!function_exists('mainCurrency')) {
    function mainCurrency()
    {
        return Cache::remember(CacheEnums::MAINCURRENCY(), CacheEnums::CACHE_TIME, function () {
            return Currency::where('main',1)->get();
        });
    }
}

if (!function_exists('active_brands')) {
    function active_brands()
    {
        return Cache::remember(CacheEnums::ACTIVE_BRANDS(), CacheEnums::CACHE_TIME, function () {
            return Brand::where('status', 1)->orderBy('name','asc')->get();
        });
    }
}

if (!function_exists('all_settings')) {
    function all_settings()
    {
        return Cache::remember(CacheEnums::SETTINGS(), CacheEnums::CACHE_TIME, function () {
            return Settings::get();
        });
    }
}

if (!function_exists('top_categories')) {
    function top_categories()
    {
        return Cache::remember(CacheEnums::TOP_CATEGORIES(), CacheEnums::CACHE_TIME, function () {
            return Category::where('status', 1)
                ->where('mark_as_top_category', 1)
                ->orderBy('top_list', 'asc')
                ->get();
        });

    }
}

if (!function_exists('mobile_top_categories')) {
    function mobile_top_categories()
    {
        return Cache::remember(CacheEnums::TOP_MOBILE_CATEGORIES(), CacheEnums::CACHE_TIME, function () {
            return Category::where('status', 1)
                ->where('mark_as_mobile_top_category', 1)
                ->orderBy('mobile_top_list', 'asc')
                ->get();
        });

    }
}

if (!function_exists('getCurrencies')) {
    function getCurrencies()
    {
        return Cache::remember(CacheEnums::CURRENCIES_SAR_USD_TRY(), CacheEnums::CACHE_TIME, function () {
            return Currency::whereIn('prefix', ['SAR', 'USD', 'TRY'])->get()->keyBy('prefix');
        });
    }
}

if (!function_exists('exchange_price')) {
    function exchange_price($price, $currency_prefix)
    {
        $currencies = getCurrencies();
        $try_currency = $currencies->get('TRY');
        $currency = $currencies->get($currency_prefix);
        if ($currency) {
            $exchanged_price = $currency->exchange_rate * $price / $try_currency->exchange_rate;
            return number_format($exchanged_price, 2, '.', '');
        } else {
            return $price;
        }
    }
}
if (!function_exists('get_shipping_price')) {
    function get_shipping_price()
    {
        $shipping = Shipping::first();
        if ($shipping) {
            $carts = Cart::with('product')->where('user_id', user()->id)->get();
            $totalPrice = $carts->sum(function ($cartItem) {
                if($cartItem->product){
                    return $cartItem->product->final_selling_price * $cartItem->quantity;
                }
                return 0;
            });

            if ($shipping->free_shipping_start_at) {
                $now = Carbon::now();
                $startDate = Carbon::parse($shipping->free_shipping_start_at);
                $endDate = Carbon::parse($shipping->free_shipping_end_at);
                if ($now->between($startDate, $endDate)) {
                    return 0;
                }
            } elseif ($shipping->free_shipping_start_at_amount > 0 && $shipping->free_shipping_start_at_amount <= exchange_price($totalPrice, 'SAR')) {
                return 0;
            }

            return $shipping->price;
        }
        return 0;
    }


}
if (!function_exists('user_favourites')) {
    function user_favourites()
    {
        $currencyService = new CurrencyService();

                $favourites = Favourite::with('product.brand')
                    ->where('user_id', user()->id)
                    ->get();

                $favourites->map(function ($favourite) use ($currencyService) {
                    if ($favourite->product) {
                        $product = clone $favourite->product;

                        $product->final_selling_price = $currencyService->convertPrice(
                            $product,
                            $product->final_selling_price
                        );
                        $product->old_price = number_format($product->final_selling_price / (1-($product->discount_percentage_selling_price/100)),2);

                        $favourite->setRelation('product', $product);
                    }
                    return $favourite;
                });

                return $favourites;
        
    }
}
if (!function_exists('calc_final_price')) {
    function calc_final_price($price, PriceFormula $formula)
    {
        $final_price = $price;
        $final_price += ($formula->packaging_shipping_fees * $price / 100);
        $final_price += ($formula->management_fees * $price / 100);
        $final_price += ($formula->profit_percentage * $price / 100);
        $final_price += ($formula->commercial_percentage * $price / 100); // shipping
        $final_price += $formula->manual_price_adjustment;
        $final_price+=($final_price*$formula->discount_percentage_selling_price/100);  //vat
        return $final_price;
    }
}
if (!function_exists('calc_product_finale_price')) {
    function calc_product_finale_price(Product $product, $discount)
    {
        $final_selling_price = $product->final_selling_price;
        $old_discount_percentage = $product->discount_percentage_selling_price;

        $new_final_selling_price = ($final_selling_price / (1 - ($old_discount_percentage / 100)));

        $new_final_selling_price -= ($new_final_selling_price * $discount / 100);
        return $new_final_selling_price;
    }
}
if (!function_exists('vat')) {
    function vat()
    {
        return Settings::whereSlug('vat')->first()->value ?? 0;
    }
}
if (!function_exists('favicon')) {
    function favicon()
    {
        return Settings::whereSlug('favicon')->first()->value ?? null;
    }
}

if (!function_exists('create_bill_id')) {
    function create_bill_id()
    {
        $bill = Bill::latest()->first();
        if ($bill) {
            $id = str_pad($bill->id, 4, '0', STR_PAD_LEFT);
        } else {
            $id = str_pad(1, 4, '0', STR_PAD_LEFT);
        }

        $month = Carbon::now()->month;
        $day = Carbon::now()->day;
        return 'BY-' . $month . $day . $id;

    }
}

if (!function_exists('fetchDataFromUrl')) {
    function fetchDataFromUrl($url)
    {
        ini_set('memory_limit', '512M'); // Increase memory limit to 512 MB
        $ch = curl_init();
        set_time_limit(300);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); // Timeout to connect
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // Increase timeout to 5 minutes
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 65536); // Set buffer size to 64 KB
        curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024); // Min download speed 1KB/s
        curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 60); // Max 60 seconds for low speed
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Referer: https://www.trendyol.com/',
            'Connection: keep-alive',
            'Cache-Control: max-age=0',
        ]);

        $data = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return $data;
    }
}
if (!function_exists('clearGlobalCache')) {
    function clearGlobalCache(array $cacheArray): void
    {
        foreach ($cacheArray as $cacheKey) {
            if (is_string($cacheKey)) {
                Cache::forget($cacheKey);
            }
        }
    }
}
if (!function_exists('unique_slug')) {
    function unique_slug($title = '', $model = 'Collection', $skip_id = 0){
        $slug = Str::slug($title);

        if (empty($slug)){
            $string = mb_strtolower($title, "UTF-8");;
            $string = preg_replace("/[\/\.]/", " ", $string);
            $string = preg_replace("/[\s-]+/", " ", $string);
            $slug = preg_replace("/[\s_]/", '-', $string);
        }

        //get unique slug...
        $nSlug = $slug;
        $i = 0;

        $model = str_replace(' ','',"\App\Models\ ".$model);

        if ($skip_id === 0) {
            while (($model::whereSlug($nSlug)->count()) > 0) {
                $i++;
                $nSlug = $slug . '-' . $i;
            }
        }else{
            while (($model::whereSlug($nSlug)->where('id', '!=', $skip_id)->count()) > 0) {
                $i++;
                $nSlug = $slug . '-' . $i;
            }
        }
        if($i > 0) {
            $newSlug = substr($nSlug, 0, strlen($slug)) . '-' . $i;
        } else {
            $newSlug = $slug;
        }
        return $newSlug;
    }
}

if (!function_exists('general_settings')) {
    function general_settings()
    {
        return Cache::remember(CacheEnums::GENERALSETTINGS(), CacheEnums::CACHE_TIME, function () {
            return GeneralSetting::get();
        });
    }
}

if (!function_exists('user_currency')) {
    function user_currency()
    {
        $selectedCurrency = Cookie::get('user_currency', 'SAR');
        return $selectedCurrency;
    }
}








