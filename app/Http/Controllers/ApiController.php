<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Page;
use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Settings;
use App\Models\Favourite;
use App\Models\TempProduct;
use Illuminate\Support\Str;
use App\Models\CartDiscount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Enums\CacheEnums;
use App\Services\OrderService;
use App\Services\GlobalService;
use App\Services\CurrencyService;
use App\Http\Enums\CompanyEnums;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SingleProductResource;
use App\Http\Resources\ProductResourceCollection;

class ApiController extends Controller
{
    protected function success($data = [], $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'data' => $data,
            'success' => true
        ], $statusCode);
    }

    protected function check_currency($currency_prefix)
    {
        return (bool)Currency::wherePrefix($currency_prefix)->first();
    }

    public function get_all_categories()
    {
        $categories = categories_with_parents()
            ->map(function ($category) {
                $updateImageUrls = function ($category) use (&$updateImageUrls) {
                    $category->image = url($category->image);
                    if ($category->children) {
                        $category->children = $category->children->map(function ($child) use ($updateImageUrls) {
                            return $updateImageUrls($child);
                        });
                    }
                    return $category;
                };

                return $updateImageUrls($category);
            });
        return $this->success($categories);
    }

    public function brands()
    {
        $brands = active_brands()->map(function ($brand) {
            $brand->image = url($brand->image);
            return $brand;
        });
        return $this->success($brands);
    }

    public function get_main_categories()
    {
//        $categories = Cache::remember(CacheEnums::MAIN_CATEGORIES(), CacheEnums::CACHE_TIME, function () {
        $categories = Category::whereNull('category_id')
//                ->whereStatus(1)
            ->get()
            ->map(function ($cat) {
                $cat->image = url($cat->image);
                return $cat;
            });
//        });
        return returnSuccess('categories', $categories, 'success');
    }

    public function categoriesBanners()
    {
        $setting = Cache::remember(CacheEnums::CATEGORIES_SETTING(), CacheEnums::CACHE_TIME, function () {
            $setting = Settings::whereSlug('category_banners')
                ->select('id', 'value', 'slug')
                ->first();
            if ($setting) {
                $setting->value = array_map(function ($item) {
                    if (isset($item['image']))
                        $item['image']['en'] = url($item['image']['en']);
                    $item['image']['ar'] = url($item['image']['ar']);
                    return $item;
                }, $setting->value);
            }
            return $setting;
        });
        return returnSuccess('categories-banners', $setting, 'success');

    }


    public function colors()
    {

        $colors = Cache::remember(CacheEnums::COLORS(), CacheEnums::CACHE_TIME, function () {
            return Color::whereNull('color_id')->orderBy('id', 'DESC')->get();
        })->map(function ($color) {
            $color->image = url($color->image);
            return $color;
        });
        return returnSuccess('colors', $colors, 'success');

    }

    public function sizes()
    {
        $sizes = Cache::remember(CacheEnums::SIZES(), CacheEnums::CACHE_TIME, function () {
            return Size::whereNull('size_id')->orderBy('id', 'DESC')->get();
        });
        return returnSuccess('sizes', $sizes, 'success');

    }

    public function home(GlobalService $globalService, Request $request)
    {
        $currency = $request->header('currency') ?? 'SAR';
        $category_id = request()->has('category_id') ? request()->category_id : null;

        $allBanners = $globalService->mobileBanners($category_id);

        $data = [
//            'news_bar' => $globalService->news(),
            'banners' => [
                'top_sliders' => $allBanners->get('top_slider'),
                'single_banner_1' => $allBanners->get('single_banner1'),
                'single_banner_2' => $allBanners->get('single_banner2'),
                'shop_brands' => $allBanners->get('shop_by_brand'),
                'shop_sections' => $allBanners->get('shop_by'),
                'search_banners' => $allBanners->get('search_banners'),
                'banners_1' => $allBanners->get('banners1'),
                'banners2' => $allBanners->get('banners2'),
            ],
            'top_categories' => mobile_top_categories()
                ->map(function ($category) {
                    $category->image = url($category->image);
                    return $category;
                })->take(8)->map(function ($category) {
                    return collect($category)->only(['id', 'name', 'image']);
                })
            ,
            'new_arrival_products' => $globalService->homeProducts($currency, 'new_arrival', $category_id),
            'featured_products' => $globalService->homeProducts($currency, 'featured', $category_id),
            'trending_products' => $globalService->homeProducts($currency, 'trending', $category_id)
        ];
        return $this->success($data);
    }

    public function news_bar()
    {
        $news_bar = Settings::whereSlug('news_bar')->select('id', 'key')->first();
        return returnSuccess('news_bar', $news_bar, 'success');
    }

    public function top_slider()
    {
        $top_slider = Settings::whereSlug('top_slider')->select('id', 'value')->first();
        $returned_values = [];
        foreach ($top_slider->value as $val) {
            $val['en'] = url($val['en']);
            $val['ar'] = url($val['ar']);
            array_push($returned_values, $val);
        }
        $top_slider['value'] = $returned_values;
        return returnSuccess('top_slider', $top_slider, 'success');
    }

    public function top_categories(Request $request)
    {
        // return Category::where('category_id',$request->category_id)->where('status',1)->get();
        $top_categories = Category::latest()->where('status', 1)->where('mark_as_top_category', 1)
            ->when($request->has('category_id') && $request->category_id != null, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })->get()->map(function ($category) {
                $category->image = url($category->image);
                return $category;
            });
        return returnSuccess('top_categories', $top_categories, 'success');
    }

    public function single_banner()
    {
        $single_banner = Settings::whereSlug('single_banner')->select('id', 'value', 'link')->first();
        $value = [
            'en' => url($single_banner->value['en']),
            'ar' => url($single_banner->value['ar']),
        ];
        $single_banner->value = $value;
        return returnSuccess('single_banner', $single_banner, 'success');
    }

    public function new_in(Request $request)
    {
        $currency_prefix = $request->header('currency');
        if (!$this->check_currency($currency_prefix))
            return response()->json(['errors' => 'this currency is not exist'], 400);

        $new_in = Product::with('brand')->where('new_arrival', 1)
            ->when($request->has('category_id') && $request->category_id != null, function ($query) use ($request) {
                $category = Category::find($request->category_id);
                if (count($category->getAllChildCategories()) > 0) {
                    $allCategories = $category->getAllChildCategories();
                    $categoryIds = $allCategories->pluck('id')->toArray();
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $request->category_id);
                }
            })->latest()->limit(4)->get();
        $new_in = new ProductResourceCollection($new_in, $currency_prefix);
        return returnSuccess('new_in', $new_in, 'success');
    }

    public function single_banner2()
    {
        $single_banner2 = Settings::whereSlug('single_banner2')->select('id', 'value', 'link')->first();
        $value = [
            'en' => url($single_banner2->value['en']),
            'ar' => url($single_banner2->value['ar']),
        ];
        $single_banner2->value = $value;
        return returnSuccess('single_banner2', $single_banner2, 'success');
    }

    public function single_banner3()
    {
        $single_banner3 = Settings::whereSlug('single_banner3')->first();
        return returnSuccess('single_banner3', $single_banner3, 'success');
    }

    public function single_banner4()
    {
        $single_banner4 = Settings::whereSlug('single_banner4')->first();
        return returnSuccess('single_banner4', $single_banner4, 'success');
    }

    public function featured(Request $request)
    {
        $currency_prefix = $request->header('currency');
        if (!$this->check_currency($currency_prefix)) {
            return response()->json(['errors' => 'this currency is not exist'], 400);
        }
        $featured = Product::with('brand')->where('featured', 1)
            ->when($request->has('category_id') && $request->category_id != null, function ($query) use ($request) {
                $category = Category::find($request->category_id);
                if (count($category->getAllChildCategories()) > 0) {
                    $allCategories = $category->getAllChildCategories();
                    $categoryIds = $allCategories->pluck('id')->toArray();
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $request->category_id);
                }
            })->latest()->limit(4)->get();
        $featured = new ProductResourceCollection($featured, $currency_prefix);
        return returnSuccess('featured', $featured, 'success');
    }

    public function shop_by_brand()
    {
        $shop_by_brand = Settings::whereSlug('shop_by_brand')->select('id', 'key', 'value')->first();
        $returned_values = [];
        foreach ($shop_by_brand->value as $val) {
            $value = [
                'image' => url($val['image']),
                'name' => $val['name'],
                'link' => $val['link'],
            ];
            array_push($returned_values, $value);
        }
        $shop_by_brand['value'] = $returned_values;
        return returnSuccess('shop_by_brand', $shop_by_brand, 'success');
    }

    public function banners2()
    {
        $banners2 = Settings::whereSlug('banners2')->first();
        $returned_values = [];
        foreach ($banners2->value as $val) {
            $value = [
                "image" => [
                    'en' => url($val['image']['en']),
                    'ar' => url($val['image']['ar'])
                ],
                "link" => $val['link']
            ];
            array_push($returned_values, $value);
        }
        $banners2['value'] = $returned_values;
        return returnSuccess('banners2', $banners2, 'success');
    }

    public function home_forniture(Request $request)
    {
        $currency_prefix = $request->header('currency');
        if (!$this->check_currency($currency_prefix)) {
            return response()->json(['errors' => 'this currency is not exist'], 400);
        }
        $home_forniture = new ProductResourceCollection(Product::with('brand:id,name')->where('home_forniture', 1)->latest()->limit(4)->get(), $currency_prefix);
        return returnSuccess('home_forniture', $home_forniture, 'success');
    }

    public function test_xml()
    {
        $xml = file_get_contents("https://cdn1.xmlbankasi.com/p1/bigdartr/image/data/xml/standart.xml");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $products = $images = $attributes = [];
        $productIds = TempProduct::where('type', 'company')->where('company_name', CompanyEnums::BIGDART)->pluck('product_id')->toArray();
        foreach ($productsData->Product as $product) {
            if (!in_array($product->Product_id, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids = [];
                $size_name = [];
                $images = [];
                if (!empty($product->Image1)) {
                    $images[] = (string)$product->Image1;
                }
                if (!empty($product->Image2)) {
                    $images[] = (string)$product->Image2;
                }
                if (!empty($product->Image3)) {
                    $images[] = (string)$product->Image3;
                }
                if (!empty($product->Image4)) {
                    $images[] = (string)$product->Image4;
                }
                if (!empty($product->Image5)) {
                    $images[] = (string)$product->Image5;
                }

                if (isset($images) && is_array($images)) {
                    $images = json_encode(array_values($images));
                }
                if (isset($attributes) && is_array($attributes)) {
                    $attributes = json_encode([]);
                }
                if ($product->variants) {
                    $i = 0;
                    foreach ($product->variants->variant as $variant_data) {
                        if (!empty($variant_data->spec)) {
                            if ($variant_data->spec[1]) {
                                $size_name['or'] = (string)$variant_data->spec[1];
                                $size = Size::firstOrCreate(['name_tr' => (string)$variant_data->spec[1]], [
                                    'name' => $size_name,
                                    'name_tr' => $variant_data->spec[1],
                                ]);
                                $sizes_ids[$i] = $size->id;
                            }
                            if ($variant_data->spec[0]) {
                                $color_name['or'] = (string)$variant_data->spec[0];
                                $color = Color::where('name_tr', $color_name['or'])->first();
                                if ($color) {
                                    $colors_ids[$i] = $color->id;

                                } else {
                                    $color = Color::create([
                                        'name' => $color_name,
                                        'name_tr' => $color_name['or'],
                                        'color_code' => '#ffffff',
                                    ]);
                                    $colors_ids[$i] = $color->id;
                                }
                            }
                            ++$i;

                        }
                    }

                    // /dd();
                }
                $products[] = [
                    'product_id' => (string)$product->Product_id,
                    'admin_id' => 1,//admin()->id,
                    'sku' => 'BD-' . rand(100000, 999999),
                    'name' => (string)$product->Name,
                    'desc' => strip_tags((string)$product->Description),
                    'price' => (float)$product->Price6kdvli,
                    'discount_price' => ($product->Price6kdvli * 35) / 100,
                    'final_price' => (float)$product->Price6kdvli,
                    'discount_percentage' => 35,
                    'link' => "https://www.bigdart.com.tr/" . $this->formatName($product->Name),
                    'group_link' => '',
                    'images' => $images,
                    'attributes' => json_encode([]),
                    'type' => 'company',
                    'company_name' => CompanyEnums::BIGDART,
                    'sizes_ids' => json_encode($sizes_ids),
                    'colors_ids' => json_encode($colors_ids),
                ];
            }
        }
        //ScrapeProductsCompany::dispatch($products);
        // return $products;

        return response()->json($products);
    }

    private function formatName($name)
    {
        // Step 1: Remove any dashes
        $step1 = str_replace('-', ' ', $name);

        // Step 2: Replace spaces with hyphens
        $step2 = Str::slug($step1, '-');

        return $step2;
    }

    public function typed_products(Request $request, $type)
    {
        $currency_prefix = $request->header('currency');
        if (!$this->check_currency($currency_prefix)) {
            return response()->json(['errors' => 'this currency is not exist'], 400);
        }
        $products = new ProductResourceCollection(Product::with('brand:id,name')->where($type, 1)->latest()->paginate(20), $currency_prefix);
        return returnSuccess('products', $products, 'success');
    }


    public function single_product(Request $request)
    {
        $currency = $request->header('currency', 'SAR');
        $headerValidator = Validator::make(['currency' => $currency], [
            'currency' => 'nullable|in:USD,SAR,TRY'
        ]);
        if ($headerValidator->fails())
            return response()->json(['errors' => $headerValidator->errors()], 400);
        $productQuery = Product::withAttributesAndOptions()
            ->with(['brand:id,name', 'files', 'rates'
            ])->where('status', 1)
            ->find($request->get('product_id'));
        if (!$productQuery)
            return response()->json(['errors' => ['product_id' => 'Product not found']], 404);
        if ($productQuery->brand && $productQuery->brand->image)
            $productQuery->brand->image = url($productQuery->brand->image);
        if ($productQuery->files) {
            $productQuery->files->each(function ($file) {
                $file->image = url($file->image);
            });
        }
        if ($productQuery->rates) {
            $productQuery->rates->each(function ($rate) {
                $rate->file = url($rate->file);
            });
        }
        $product = new SingleProductResource($productQuery, $currency);
        $relatedProductsQuery = Product::with('brand')
            ->where('status', 1)
            ->where('category_id', $productQuery->category_id)
            ->orderByRaw('RAND() * id')
            ->limit(3)
            ->get();
        $related_products = new ProductResourceCollection($relatedProductsQuery, $currency);
        $canRate = false;
        if (Auth::check())
            $canRate = (new GlobalService())->checkIfCanRate($product->id, false);
        $data = [
            'product' => $product,
            'related_products' => $related_products,
            'can_rate' => $canRate
        ];
        return returnSuccess('data', $data, 'success');
    }

    public function related_products(Request $request)
    {
        $related_products = ProductResource::collection(
            Product::with('brand:id,name')
                ->where('category_id', $request->category_id)
                ->where('status', 1)
                ->take(4)->get());
        return returnSuccess('related_products', $related_products, 'success');
    }

    public function get_currencies()
    {
        $currencies = currencies();
        return returnSuccess('currencies', $currencies, 'success');
    }

    public function add_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:App\Models\Product,id',
            'size'=>'string',
            'color'=>'string',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        $cart = Cart::where('product_id', $request->product_id)->where('user_id', user()->id)->first();
        if (!$cart)
            (new GlobalService())->addToCart($request->all());
        return $this->success('success', '200');
    }

    public function add_delete_favourite(Request $request)
    {
        $favourite = Favourite::where('product_id', $request->product_id)->where('user_id', Auth::id())->first();
        if ($favourite) {
            $favourite->delete();
            return returnSuccess('data', 'deleted successfully', 'success');
        } else {
            $favourite = Favourite::create([
                'product_id' => $request->product_id,
                'user_id' => user()->id,
            ]);
            return returnSuccess('favourite', $favourite, 'success');
        }
    }

    public function delete_cart_item(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:App\Models\Cart,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $cart = Cart::where('user_id', Auth::id())->where('id', $request->id)->first();
        $cart->delete();
        return returnSuccess('data', 'deleted successfully', 'success');
    }

    public function update_quantity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:App\Models\Cart,id',
            'quantity' => 'required|numeric|min:1',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        DB::table('carts')
            ->where('id', $request->id)
            ->update(['quantity' => $request->quantity]);
        return $this->success('success', '200');
    }

    public function update_size(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:App\Models\Cart,id',
            'size_id' => 'required|exists:App\Models\Size,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $size = \DB::table('sizes')->where('id', $request->size_id)->first();
        DB::table('carts')
            ->where('id', $request->id)
            ->update(['size' => $size->name]);
        return $this->success('success', '200');
    }

    public function update_color(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:App\Models\Cart,id',
            'color_id' => 'required|exists:App\Models\Color,id'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        $size = \DB::table('colors')->where('id', $request->color_id)->first();
        DB::table('carts')->where('id', $request->id)->update(['color' => $size->name]);
        return $this->success('success', '200');
    }

    public function my_cart(Request $request, OrderService $orderService, CurrencyService $currencyService)
    {

        $currency = $request->header('currency', 'SAR');
        $validator = Validator::make(['currency' => $currency], [
            'currency' => 'nullable|in:USD,SAR,TRY'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);

        $cart_items = Cart::with([
            'product' => function ($query) {
                $query->with(['sizes','colors'])->select('id', 'name_en', 'name_ar', 'sku',
                    'brand_id', 'image', 'final_selling_price',
                    'discount_percentage_selling_price as discount_percentage','tax_percentage');
            }, 'product.brand' => function ($query) {
                $query->select('id', 'name');
            }
        ])->where('user_id', Auth::id())->get();
        if ($cart_items->isEmpty())
            return returnSuccess('cart', 'empty cart', 'success');
        $cart_items->transform(function ($cartItem) use ($currency,$currencyService) {
            $cartItem->product->final_selling_price = $currencyService->convertPrice($cartItem->product,$cartItem->product->final_selling_price);
            return $cartItem;
        });

        $orderData =$orderService->calculateOrder($cart_items);
        $cart = [
            'shipping' => $orderData['shipping'],
            'discountPercentage' => $orderData['cart_discount_coupon'],
            'subTotal' => $orderData['subtotal'],
            'total' => $orderData['total'],
            'vat' => $orderData['vat'],
            'cart' => $cart_items,
        ];
        return returnSuccess('cart', $cart, 'success');
    }

    public function clearCart()
    {
        Cart::where('user_id', Auth::id())->delete();
        return $this->success('success', '200');
    }

    public function my_orders()
    {
        $orders = Order::with('order_items.product:id,name_en,name_ar,brand_id,final_selling_price,discount_percentage_selling_price,image')
            ->where('user_id', Auth::id())->get();
//        $orders->each(function ($order) {
//            $order->payment = DB::table('payment_transactions')->find($order->payment_id);
//        });
        return returnSuccess('orders', $orders, 'success');
    }

    public function order($id, Request $request)
    {
        $currency = $request->header('currency', 'SAR');
        $order = Order::with(['order_items.product:id,name_en,name_ar,brand_id,final_selling_price,discount_percentage_selling_price,image,category_id'])->find($id);
        if ($order)
            $order->payment = DB::table('payment_transactions')->find($order->payment_id);
        $relatedProductsQuery = Product::with('brand')
            ->where('status', 1)
            ->where('category_id', $order->order_items[0]->product->category_id)
            ->where('id', '!=', $order->order_items[0]->product->id)
            ->orderByRaw('RAND() * id')
            ->limit(3)->get();
        $related_products = new ProductResourceCollection($relatedProductsQuery, $currency);
        $data = [
            'order' => $order,
            'related_products' => $related_products
        ];
        return returnSuccess('data', $data, 'success');

    }

    public function my_favourites()
    {
        $favourites = user_favourites();
        return returnSuccess('favourites', $favourites, 'success');
    }

    public function languages()
    {
        $languages = locales()->map(function ($lang) {
            $lang->logo = url($lang->logo);
            return $lang;
        });
        return returnSuccess('languages', $languages, 'success');
    }

    public function currencies()
    {
        $currencies = currencies()->map(function ($currency) {
            $currency->flag = url($currency->flag);
            return $currency;
        });
        return returnSuccess('currencies', $currencies, 'success');
    }

    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:App\Models\Order,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $order = Order::find($request->order_id);
        return returnSuccess('order', $order, 'success');


    }

    public function pages()
    {
        $pages = Page::get();
        return returnSuccess('pages', $pages, 'success');
    }

}
