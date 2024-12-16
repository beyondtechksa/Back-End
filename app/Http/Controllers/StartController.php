<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRateRequest;
use App\Models\Cart;
use App\Models\Page;
use App\Models\Size;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Order;
use App\Models\Rates;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\Settings;
use App\Models\Shipping;
use App\Models\Favourite;
use App\Models\OrderItem;
use App\Models\Collection;
use App\Models\Newsletters;
use App\Models\CartDiscount;
use App\Rules\ValidCoupon;
use App\Services\GlobalService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Http\Enums\CacheEnums;
use App\Services\CurrencyService;
use App\Services\OrderService;
use App\Services\FilterService;
use App\Services\AddressesService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\SingleProductResource;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\OrderCreationException;
use App\Services\ClickPayService;
use App\Services\TamaraPaymentService;


class StartController extends Controller
{
    public function dashboard()
    {
        return inertia('Dashboard/Index')->with(['page_title' => __('Dashboard')]);;
    }

    public function home(Category $category, Product $product)
    {
        // $cacheKey = CacheEnums::HOMEPAGECACHE();
    
        // return Cache::remember($cacheKey, CacheEnums::CACHE_TIME, function () use ($category, $product) {
            $topCategories = top_categories()->take(20);
            $currencyService = new CurrencyService();
    
            // Trending products
            $trendingProducts = Cache::remember(CacheEnums::TRENDINGPRODUCTS(), CacheEnums::CACHE_TIME, function () use ($product, $currencyService) {
                return $product->with('brand')
                    ->select('id', 'slug', 'name_en', 'name_ar', 'image', 'brand_id', 'final_selling_price', 'discount_percentage_selling_price', 'currency_id')
                    ->where('trending', 1)
                    ->where('status', 1)
                    ->latest()
                    ->paginate(15)
                    ->through(function ($product) use ($currencyService) {
                        $product->final_selling_price = $currencyService->convertPrice($product, $product->final_selling_price);
                        $product->old_price = $currencyService->convertPrice($product, $product->old_price);
                        return $product;
                    });
            });
    
            // Featured products
            $featuredProducts = Cache::remember(CacheEnums::FEATUREDPRODUCTS(), CacheEnums::CACHE_TIME, function () use ($product, $currencyService) {
                return $product->with('brand')
                    ->select('id', 'slug', 'name_en', 'name_ar', 'image', 'brand_id', 'final_selling_price', 'discount_percentage_selling_price', 'currency_id')
                    ->where('featured', 1)
                    ->where('status', 1)
                    ->latest()
                    ->paginate(15)
                    ->through(function ($product) use ($currencyService) {
                        $product->final_selling_price = $currencyService->convertPrice($product, $product->final_selling_price);
                        $product->old_price = $currencyService->convertPrice($product, $product->old_price);
                        return $product;
                    });
            });
    
            // New arrival products
            $newArrivalProducts = Cache::remember(CacheEnums::NEWARRIVALPRODUCTS(), CacheEnums::CACHE_TIME, function () use ($product, $currencyService) {
                return $product->with('brand')
                    ->select('id', 'slug', 'name_en', 'name_ar', 'image', 'brand_id', 'final_selling_price', 'discount_percentage_selling_price', 'currency_id')
                    ->where('new_arrival', 1)
                    ->where('status', 1)
                    ->latest()
                    ->paginate(15)
                    ->through(function ($product) use ($currencyService) {
                        $product->final_selling_price = $currencyService->convertPrice($product, $product->final_selling_price);
                        $product->old_price = $currencyService->convertPrice($product, $product->old_price);
                        return $product;
                    });
            });
    
            // Brands
            $brands = active_brands()->take(5);
    
            return inertia('Home/Index', [
                'top_categories' => $topCategories,
                'trending' => $trendingProducts,
                'brands' => $brands,
                'featured' => $featuredProducts,
                'new_arrival' => $newArrivalProducts,
            ])->with(['page_title' => __('Home')]);
        // });
    }

    public function categories()
    {
        $categories = categories_with_parents();
        return inertia('Home/Categories', ['categories' => $categories])->with(['page_title' => __('Categories')]);
    }

    public function shop_by_brand()
    {
        $setting = Settings::where('slug', 'shop_by_brand')->first();
        return inertia('Home/ShopByBrand', ['setting' => $setting])->with(['page_title' => __('Shop by brand')]);
    }

    public function get_children($id)
    {
        return Category::where('category_id', $id)->where('status', true)->get();
    }

    // shop
    public function shop(Request $request, FilterService $filterService)
    {

      $currencyService=new CurrencyService();
        $leafCategories = collect();
        $products = Product::with('brand')->where('status', 1)
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name_en', 'like', '%' . $request->search . '%');
                $q->orwhere('name_ar', 'like', '%' . $request->search . '%');
                $q->orwhere('name_tr', 'like', '%' . $request->search . '%');
            })
            ->when(\request()->has('category_id'), function ($q) use ($leafCategories) {
                //maged => function to check if has children push to $leafCategories else loop on children and call the function again
                $category = Category::find(\request('category_id'));
                if ($category) {
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
                }
                $q->whereIn('category_id', $leafCategories);
            })->when(\request()->has('product_type') && \request('product_type') != null, function ($q) {
                if (\request('product_type') === 'trending')
                    $q->where('trending', 1);
                if (\request('product_type') === 'featured')
                    $q->where('featured', 1);
                if (\request('product_type') === 'new_arrival')
                    $q->where('new_arrival', 1);
            })
            //            ->when(\request()->has('collection_id' && \request('collection_id') != null, function ($q) {
            //                $q->where('collection_id', \request('collection_id'));
            //            }))
            ->orderBy('ontop', 'desc')
            //            ->inRandomOrder()
            //            ->orderByRaw('RAND() * id')
            ->limit(12)
            ->get()->map(function($product) use($currencyService) {
                $product['final_selling_price'] = $currencyService->convertPrice($product,$product->final_selling_price);
                $product['old_price'] = number_format($product->final_selling_price / (1-($product->discount_percentage_selling_price/100)),2);
                return $product;
            });
        $categories = categories_with_parents();
        $brands = active_brands();
        //$colors = Color::orderBy('id', 'DESC')->get();
        //$sizes = Size::orderBy('id', 'DESC')->get();
        $colors = $this->getAllColors();
        $sizes = $this->getAllSizes();

        return inertia('Home/Shop', [
            'products' => $products,
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
            'brands' => $brands,
            'category_id' => \request()->has('category_id') ? $leafCategories : null,
            'collection_id' => \request()->has('collection_id') ? \request('collection_id') : null,
            'product_type' => request()->has('product_type') ? request()->product_type : null,
            'request' => $request->all()
        ])->with(['page_title' => __('Shop')]);
    }

    public function filterShopRequest(Request $request, FilterService $filterService)
    {
        $filterData = $request->filter_data;
        $filterData['offset'] = $filterData['offset'] ?? 0;
        return $filterService->filter($filterData);
    }

    public function loadMoreProducts(Request $request, CurrencyService $currencyService)
    {
        $type = $request->type;
        $collection_id = $request->collection_id;
        $brand_id = $request->brand_id;
        $collection = Collection::find($collection_id);
        $limit = $request->limit;
        return Product::with('brand')->where('status', 1)
            ->when($type == 'trending', function ($q) {
                $q->where('trending', 1);
            })->when($type == 'featured', function ($q) {
                $q->where('featured', 1);
            })->when($type == 'sale', function ($q) {
                $q->whereNotNull('discount_percentage_selling_price')->where('discount_percentage_selling_price', '>', 0.00);
            })->when($request->has('collection_id'), function ($q) use ($collection_id) {
                $q->where('collection_id', $collection_id);
            })->when($request->has('brand_id'), function ($q) use ($brand_id) {
                $q->where('brand_id', $brand_id);
            })
            ->orderBy('ontop', 'desc')->take($limit)->get()->map(function($product) use($currencyService) {
                $product['final_selling_price'] = $currencyService->convertPrice($product,$product->final_selling_price);
                $product['old_price'] = number_format($product->final_selling_price / (1-($product->discount_percentage_selling_price/100)),2);
                return $product;
            });
    }

    public function products(CurrencyService $currencyService)
    {
        if (!request()->has('type'))
            return redirect()->back();
        $products = Product::with('brand')->select('id', 'slug', 'name_en', 'name_ar', 'image', 'brand_id', 'final_selling_price', 'discount_percentage_selling_price', 'currency_id')
        ->where('status', 1)
            ->when(request()->has('type'), function ($q) {
                if (\request('type') == 'trending')
                    $q->where('trending', 1);
                if (\request('type') == 'featured')
                    $q->where('featured', 1);
                if (\request('type') == 'sale')
                    $q->where('discount_percentage_selling_price', '>', 0.00);
            })
            ->latest()->limit(8)->get()->map(function ($product) use ($currencyService) {
                $product->final_selling_price = $currencyService->convertPrice($product, $product->final_selling_price);
                $product->old_price = $currencyService->convertPrice($product, $product->old_price);
                return $product;
            });

            
                    

        return inertia('Home/Products', [
            'products' => $products,
        ])->with(['page_title' => __('Products'), 'page_type' => \request('type')]);
    }

    public function collections($slug, CurrencyService $currencyService)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();
            $products =  Product::with('brand')->select('id', 'slug', 'name_en', 'name_ar', 'image', 'brand_id', 'final_selling_price', 'discount_percentage_selling_price', 'currency_id')
                    ->where('collection_id', $collection->id)
                    ->where('status', 1)
                    ->orderBy('ontop', 'desc')
                    ->limit(12)
                    ->get()->map(function ($product) use ($currencyService) {
                        $product->final_selling_price = $currencyService->convertPrice($product, $product->final_selling_price);
                        $product->old_price = $currencyService->convertPrice($product, $product->old_price);
                        return $product;
                    });

        $brands = active_brands();
        $colors = $this->getAllColors();
        $sizes = $this->getAllSizes();
        return inertia('Home/Collection', [
            'collection' => $collection,
            'products' => $products,
            'colors' => $colors,
            'sizes' => $sizes,
            'brands' => $brands,
            'slug' => $slug
        ])->with(['page_title' => __('Products')]);
    }

    public function brands($slug, CurrencyService $currencyService)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
          $products =  Product::with('brand')->select('id', 'slug', 'name_en', 'name_ar', 'image', 'brand_id', 'final_selling_price', 'discount_percentage_selling_price', 'currency_id')
                    ->where('brand_id', $brand->id)
                    ->where('status', 1)
                    ->orderBy('ontop', 'desc')
                    ->limit(15)
                    ->get()->map(function ($product) use ($currencyService) {
                        $product->final_selling_price = $currencyService->convertPrice($product, $product->final_selling_price);
                        $product->old_price = $currencyService->convertPrice($product, $product->old_price);
                        return $product;
                    });
        return inertia('Home/Brand', [
            'brand' => $brand,
            'products' => $products,
        ])->with(['page_title' => __('Products')]);
    }

    public function product_details($id, $slug = null)
    {
        $product = Product::with('brand', 'files', 'rates','sizes','colors','currency')->withAttributesAndOptions()->withRated()->where('id', $id)->where('status', 1);
        if($slug){
            $product->where('slug',$slug);
        }
        $product = $product->firstOrFail();
        $product = new SingleProductResource($product);

        $category = Category::where('id', $product->category_id)->first();
        $parents = getAllParents($category);
        $currencyService=new CurrencyService();
        $related = Product::with('brand')->where('status', true)->where('category_id', $product->category_id)
        ->limit(5)->inRandomOrder()->get()->map(function($product) use($currencyService) {
            $product['final_selling_price'] = $currencyService->convertPrice($product,$product->final_selling_price);
            $product['old_price'] = number_format($product->final_selling_price / (1-($product->discount_percentage_selling_price/100)),2);
            return $product;
        });

        $colors = $this->getAllColors();
        $sizes = $this->getAllSizes();
        $canRate = false;
        if (Auth::check()) {
            $canRate = (new GlobalService())->checkIfCanRate($id, false);
            $product->update(['visit' => $product->visit += 1]);
        }
        return inertia('Home/Product', [
            'product' => $product,
            'category' => $category,
            'parents' => $parents,
            'related' => $related,
            'rate' => $canRate
        ])->with(['page_title' => __('Product Details')]);
    }

    protected function getAllColors()
    {
        return Cache::remember(CacheEnums::COLORS(), CacheEnums::CACHE_TIME, function () {
            return Color::whereNull('color_id')->where('status', 1)->orderBy('name->en', 'asc')->get();
        });
    }

    protected function getAllSizes()
    {
        return Cache::remember(CacheEnums::SIZES(), CacheEnums::CACHE_TIME, function () {
            return Size::whereNull('size_id')->where('status', 1)->orderBy('name->en', 'asc')->get();
        });
    }

    public function storeRate(StoreRateRequest $request)
    {
        $data = $request->validated();
        if ($request->file) {
            $path = uploadImage($request->file('file'), 'ratings');
            $data['file'] = $path;
        }
        $data['user_id'] = user()->id;
        Rates::create($data);
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function add_cart(Request $request)
    {

        if (auth()->check()) {
            (new GlobalService())->addToCart($request->all());
        } else {
            $carts = isset($_COOKIE['user_cart']) ? json_decode($_COOKIE['user_cart']) : [];
            $productExists = false;
            foreach ($carts as $cart) {
                if ((int)$cart->product_id == (int)$request->product_id) {
                    $productExists = true;
                    break;
                }
            }
            if (!$productExists) {
                $singlePro = new \stdClass();
                $singlePro->product_id = $request->product_id;
                $singlePro->quantity = 1;
                $singlePro->size = $request->size;
                $singlePro->color = $request->color;
                $singlePro->selected = true;
                $carts[] = $singlePro;
                setcookie('user_cart', json_encode($carts), time() + (3600 * 90), "/");
            }
        }
        return redirect()->back()->with('success', 'Form submitted successfully!');;
    }

    public function add_coupon(Request $request, GlobalService $globalService)
    {
        $validCouponRule = new ValidCoupon();
        $request->validate([
            'code' => ['required', 'string', 'max:255', $validCouponRule],
        ]);
        $coupon = $validCouponRule->getCoupon();
        $cart_discount = CartDiscount::where('user_id', user()->id)
            ->where('coupon_code', $coupon->code)->first();
        if (!$cart_discount) {
            $globalService->useCoupon(Auth::user()->id, $coupon);
            return redirect()->route('cart');
        }
        return redirect()->back()->withErrors(['code' => __('this coupon is used before')]);
    }

    public function deleteCartItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);
        if (\auth()->check()) {
            $userID=user()->id;
            $cart = Cart::where('user_id', $userID)->where('product_id', $request->product_id)->first();
            $cart->delete();
            $remainingCarts = Cart::where('user_id', $userID)->count();
            if ($remainingCarts === 0) {
                $discountCoupon = CartDiscount::where('user_id', $userID)->where('status',0)->latest()->first();
                if ($discountCoupon) {
                    $discountCoupon->delete();
                }
            }
            
        } else {
            if (isset($_COOKIE['user_cart'])) {
                $carts = json_decode($_COOKIE['user_cart'], true);
                foreach ($carts as $key => $cart) {
                    if ($cart['product_id'] == $request->product_id) {
                        unset($carts[$key]);
                    }
                }
                setcookie('user_cart', json_encode($carts), time() + (3600 * 90), "/");
            }
        }
    }

    public function add_favourite(Request $request)
    {
        $product_id = $request->product_id;
        $favourite = Favourite::where('product_id', $product_id)->where('user_id', user()->id)->first();
        if ($favourite) {
            $favourite->delete();
        } else {
            $favourite = Favourite::create([
                'product_id' => $product_id,
                'user_id' => user()->id,
            ]);
        }
        return $product_id;
    }

    public function update_quantity(Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1'
        ]);
        if (!auth()->check() && !isset($_COOKIE['user_cart']))
            return redirect('login');
        if (auth()->check()) {
            $cart = Cart::find($request->id);
            $cart->update([
                'quantity' => $request->quantity,
            ]);
        } else {
            if (isset($_COOKIE['user_cart'])) {
                $carts = json_decode($_COOKIE['user_cart'], true);
                foreach ($carts as $key => $cart) {
                    if ($cart['product_id'] == $request->product_id) {
                        $carts[$key]['quantity'] = $request->quantity;
                        break;
                    }
                }
                setcookie('user_cart', json_encode($carts), time() + (3600 * 90), "/");
            }
        }
        return redirect()->route('cart');
    }

    public function select_items(Request $request)
    {
        if (auth()->check()) {
            Cart::where('user_id', user()->id)->whereIn('id', $request->checked)->update([
                'selected' => 1
            ]);
            Cart::where('user_id', user()->id)->whereNotIn('id', $request->checked)->update([
                'selected' => 0
            ]);
            return redirect()->back();
        }
    }

    public function update_size_color(Request $request)
    {
        if (!auth()->check() && !isset($_COOKIE['user_cart']))
            return redirect('login');
        if (auth()->check()) {
            $cart = Cart::find($request->id);
            $cart->update([
                'size' => $request->size,
                'color' => $request->color,
            ]);
        } else {
            if (isset($_COOKIE['user_cart'])) {
                $carts = json_decode($_COOKIE['user_cart']);
                foreach ($carts as $key => $cart) {
                    if ((int)$cart->product_id == (int)$request->product_id) {
                        if ($request->size) {
                            $cart->size = $request->size;
                        }
                        if ($request->color) {
                            $cart->color = $request->color;
                        }
                        break;
                    }
                }
                setcookie('user_cart', json_encode($carts), time() + (3600 * 90), "/");
            }
        }
        return redirect()->back();
    }

    public function update_attribute(Request $request)
    {
        if (!auth()->check() && !isset($_COOKIE['user_cart']))
            return redirect('login');
        $attributes = [];
        if (auth()->check()) {
            $cart = Cart::find($request->id);
            $attributes = $cart->attributes;
        } else {
            if (isset($_COOKIE['user_cart'])) {
                $carts = json_decode($_COOKIE['user_cart'], true);
                foreach ($carts as $key => $cart) {
                    if ($cart['product_id'] == $request->product_id) {
                        $attributes = $carts[$key]['attributes'];
                        break;
                    }
                }
            }
        }

        $product_option = ProductOption::find($request->option_id);
        $attribute = [
            'id' => $product_option->product_attribute_id,
            'option_id' => $product_option->id,
        ];
        $idToCheck = $product_option->product_attribute_id;

        // Find the index of the existing item
        $existingItemIndex = array_search($idToCheck, array_column($attributes, 'id'));

        // If the existing item exists in the array, update its name
        if ($existingItemIndex !== false)
            $attributes[$existingItemIndex]['option_id'] = $request->option_id;
        else
            $attributes[] = $attribute;

        if (auth()->check()) {
            $cart = Cart::find($request->id);
            $cart->update([
                'attributes' => $attributes,
            ]);
        } else {
            if (isset($_COOKIE['user_cart'])) {
                $carts = json_decode($_COOKIE['user_cart'], true);
                foreach ($carts as $key => $cart) {
                    if ($cart['product_id'] == $request->product_id) {
                        $carts[$key]['attributes'] = $attributes;
                        break;
                    }
                }
                setcookie('user_cart', json_encode($carts), time() + (3600 * 90), "/");
            }
        }
        return redirect()->back();
    }

    public function cart()
    {
        if (!auth()->check() && !isset($_COOKIE['user_cart']))
            return redirect('login');
            $carts = $this->user_cart();
        return inertia('Home/Cart', ['carts' => $carts])->with(['page_title' => __('Cart')]);
    }

    private function user_cart(){
        if (auth()->check()) {
            $carts = (new GlobalService())->get_user_cart();
        } else {
            $cookieCart = isset($_COOKIE['user_cart']) ? json_decode($_COOKIE['user_cart']) : [];
            // $cookieCart = json_decode($_COOKIE['user_cart']);
            $carts = [];
            // if (empty($cookieCart)){
            //     return $carts;
            // }
            $currencyService=new CurrencyService();
            foreach ($cookieCart as $product) {
                $obj = new \stdClass();
                $obj->product_id = $product->product_id;
                $obj->quantity = $product->quantity;
                $obj->size = $product->size ?? null;
                $obj->color = $product->color ?? null;
                $obj->selected = $product->selected ?? true;
                $product2=Product::with(['brand'])->find($product->product_id);
                if($product2){
                    $product2['final_selling_price']=$currencyService->convertPrice($product2,$product2->final_selling_price);
                }
                
                $obj->product = $product2;
                $carts[] = $obj;
            }

        }
        return $carts;
    }

    public function getCart(){
       return  $this->user_cart();
    }
    public function getCookieCart()
    {
        $cookieCart = isset($_COOKIE['user_cart']) ? json_decode($_COOKIE['user_cart']) : [];
        // $cookieCart = json_decode($_COOKIE['user_cart']);
        $carts = [];
        // if (empty($cookieCart)){
        //     return $carts;
        // }
        $currencyService=new CurrencyService();
        foreach ($cookieCart as $product) {
            $obj = new \stdClass();
            $obj->product_id = $product->product_id;
            $obj->quantity = $product->quantity;
            $obj->size = $product->size ?? null;
            $obj->color = $product->color ?? null;
            $product2=Product::with(['brand'])->find($product->product_id);
            $product2['final_selling_price']=$currencyService->convertPrice($product2,$product2->final_selling_price);
            $obj->product = $product2;
            $carts[] = $obj;
        }

        return $carts;
    }

    public function calculate_order(Request $request){
        $carts = $this->user_cart();
        $data=(new OrderService())->calculateOrder($carts);
        return $data;
    }

    public function favourites()
    {
        $favourites = user_favourites();
        return inertia('Home/Favourites', ['favourites' => $favourites])->with(['page_title' => __('Favourites')]);
    }

    public function get_setting($slug)
    {
        return Cache::remember(CacheEnums::NAV_NEWSBAR(), CacheEnums::CACHE_TIME, function () use ($slug) {
            return Settings::whereSlug($slug)->first();
        });
    }

    public function get_nav_categories(Category $category)
    {
        return Cache::remember(CacheEnums::NAV_CATEGORIES(), CacheEnums::CACHE_TIME, function () use ($category) {
            return $category->navCategories()
                ->orderBy('list', 'asc')
                ->limit(9)
                ->get();
        });
    }

    public function checkout()
    {
        $carts = Cart::with('product.brand')->where('user_id', user()->id)->get();
        $addresses = Address::where('user_id', user()->id)->get();
        return inertia('Home/CheckoutAddress', ['carts' => $carts, 'addresses' => $addresses])->with(['page_title' => __('Checkout Address')]);
    }

    public function payment()
    {
        $orderService = new OrderService();
        $carts = (new GlobalService())->get_user_selected_cart(user()->id);
        // $res = (new PaymentService())->loadTelrIframe(user()->id);
        $frame = isset($res) && $res['order'] && $res['order']['url'] ? $res['order']['url'] : null;
        $order_data = $orderService->calculateOrder($carts);
        $total_after_wallet = $order_data['total_after_wallet'];
        return inertia('Home/CheckoutPayment', ['carts' => $carts, 'frame' => $frame,'total_after_wallet'=>$total_after_wallet])->with(['page_title' => __('Checkout Payment')]);
    }


    public function createClickpayPayment(Request $request)
    {
        $carts = Cart::with('product.brand')->where('user_id', user()->id)->get();
        $paymentService = new ClickPayService();
        $paymentResponse = $paymentService->loadClickpayPaymentPage(user()->id);
        $frame = isset($paymentResponse) && $paymentResponse['redirect_url'] ? $paymentResponse['redirect_url'] : null;
        return response()->json([
            'res' =>  $paymentResponse,
            'frame' => $frame
        ]);
    }
    public function tamaraPayment()
    {
        $carts = Cart::with('product.brand')->where('user_id', auth()->user()->id)->get();

        $res = (new TamaraPaymentService())->loadTamaraPayment(auth()->user()->id, $carts); // Adjust for Tamara
        $frame = isset($res) && isset($res['checkout_url'])  ? $res['checkout_url']: null;
        $order_id = isset($res) && isset($res['order_id'])  ? $res['order_id']: null;
        $token =  config('tamarapayment.token');
       // dd(`Bearer ${$token}`);
        return response()->json([
           'res' =>  $res  ,
           'frame' => $frame,
           'url' => 'https://api-sandbox.tamara.co/orders/'.$order_id.'/authorise',
           'token' => 'Bearer ' .$token,
           'user_id'    =>  auth()->user()->id
        ]);
    }


    public function handleReturn(Request $request)
    {
        // Capture the response data from the request
        $data = $request->all();
        //  dd( $data);
        // Retrieve essential data from the response
        $transaction_ref = $data['tranRef'] ?? null;
        $status = $data['respStatus'] ?? null;
        $responseMessage = $data['respMessage'] ?? 'No response message';

        // Redirect to the homepage if the transaction reference is missing
        if (!$transaction_ref) {
            return redirect()->route('home')->with('error', 'Transaction reference not found.');
        }

        // Redirect to the homepage if the response status is missing
        if (!$status) {
            return redirect()->route('home')->with('error', 'Payment status not found.');
        }

        // Check payment status and handle accordingly
        if ($status === 'A') { // Assuming 'A' means 'Authorized'
            // Update the transaction status to 'completed'
            $this->updateTransactionStatus($transaction_ref, 1);
            $user = User::where('email', $data['customerEmail'])->latest()->first();
            return redirect()->route('order.clickpay.success', $user->id)->with('success', 'Payment completed successfully!');
        } else {
            // Update the transaction status to 'failed' for non-authorized responses
            $this->updateTransactionStatus($transaction_ref, 0);

            return redirect()->route('cart.index')->with('error', "Payment failed: $responseMessage");
        }
    }




    // Update the status of the payment transaction in the database
    protected function updateTransactionStatus($transaction_ref, $status)
    {
        DB::table('payment_transactions')
            ->where('uuid', $transaction_ref)
            ->update(['status' => $status]);
    }

    public function handleCallback(Request $request)
    {
        $transaction_ref = $request->input('tran_ref');

        if (!$transaction_ref) {
            return response()->json(['error' => 'Transaction reference not found'], 400);
        }

        // Use the ClickpayPaymentService to check the payment status
        $paymentService = new \App\Services\ClickpayService();
//        TODO checkPayment defined as void function --> you need to return the payment in checkPayment after update
        $paymentStatus = $paymentService->checkPayment($transaction_ref ,0);

        // Handle payment result
        if (isset($paymentStatus['payment_result']['response_status']) && $paymentStatus['payment_result']['response_status'] == 'A') {
            // Payment authorized, update transaction to completed
            $this->updateTransactionStatus($transaction_ref, 'completed');

            // You can also trigger order fulfillment or other business logic here

            return response()->json(['success' => 'Payment completed successfully'], 200);
        } else {
            // Payment failed or declined
            $this->updateTransactionStatus($transaction_ref, 'failed');
            return response()->json(['error' => 'Payment failed or was declined'], 400);
        }
    }


    public function go_checkout(Request $request)
    {
        $request->validate([
            'favourite_address' => 'required'
        ]);
        (new AddressesService())->markAsFavourite($request->favourite_address, user()->id);
        return redirect()->route('cart.checkout.payment');
    }

    public function create_order(OrderService $orderService)
    {
        try {
            $order = $orderService->order(user()->id);
            return redirect()->route('order.success', $order->id);
        } catch (OrderCreationException $exception) {
            return back()->withErrors(['error' => 'Order creation failed']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong']);
        }
    }
    public function place_order(OrderService $orderService)
    {
        // try {
            $order = $orderService->order(user()->id);
            return redirect()->route('order.place_success', $order->id);
        // } catch (OrderCreationException $exception) {
        //     return back()->withErrors(['error' => 'Order creation failed']);
        // } catch (\Exception $e) {
        //     return back()->withErrors(['error' => 'Something went wrong']);
        // }
    }

    public function place_order_success($id){
        $order=Order::findOrFail($id);
        return inertia('Home/OrderSuccess', [
            'order' => $order
        ]);
    }

    public function order_success()
    {
        $result = (new PaymentService())->checkPayment(\user()->id);
        if (Cart::where('user_id', operator: \user()->id)->count() == 0 || !$result)
            return redirect()->route('welcome');
        if (isset($result['order']['status']['code']) && $result['order']['status']['code'] == 3) {
            DB::table('payment_transactions')
                ->where('uuid', $result['order']['ref'])
                ->update(['response' => $result, 'status' => 1]);
            $payment = DB::table('payment_transactions')
                ->where('uuid', $result['order']['ref'])->first();
            $order = (new OrderService())->order(\user()->id, $payment->id, 'SAR');
            DB::table('payment_transactions')
                ->where('user_id', user()->id)
                ->where('status', 0)
                ->whereNull('response')
                ->delete();
            return inertia('Home/OrderSuccess', [
                'order' => $order
            ]);
        } else {
            return redirect()->route('cart.checkout.payment')
                ->with(['fail' => 'payment failed']);
        }
    }


    public function order_success_click_pay($id = null)
    {
        $user = User::find($id);
        Auth::login($user);
        session(['user_id' => Auth::user()->id]);
        if (Cart::where('user_id', \user()->id)->count() == 0)
        return redirect()->route('welcome');

        $transaction =  $user->payment_transactions()->latest()->first();
        $transaction->status = 1;
        $transaction->update();
        $payment =  $transaction;
        $order = (new OrderService())->order($user->id, $payment->id, 'SAR');
        DB::table(table: 'payment_transactions')
            ->where('user_id',  $user->id)
            ->where('status', 0)
            ->whereNull('response')
            ->delete();

        return inertia('Home/OrderSuccess', [
            'order' => $order
        ]);
    }

    public function get_shipping_price()
    {
        return get_shipping_price();
    }

    public function save_redirect_session(Request $request)
    {
        session()->put('redirect_url', $request->session_url);
        return redirect(url($request->go_to_url));
    }

    public function get_footer_settings(Settings $settings, Page $page)
    {
        return [
            'socials_setting' => $settings->where('slug', 'social')->firstOrFail(),
            'pages' => $page->where('show_in_footer_bar',0)->get(),
            'footer_bar_pages' => $page->where('show_in_footer_bar',1)->get(),
            'popup' => $settings->where('slug', 'popup')->firstOrFail(),
        ];
    }

    public function page($slug)
    {
        $page = Page::whereSlug($slug)->firstOrFail();
        return inertia('Home/Page', ['page' => $page]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:newsletters',
        ]);
        Newsletters::create([
            'email' => $request->email
        ]);
        return redirect()->back()->with(['message' => 'success']);
    }

    public function search_products(Request $request)
    {
        $currencyService = new CurrencyService();
        $products = Product::with('brand')->latest()
            ->select('id','name_en','name_ar','brand_id','image','final_selling_price','old_price','currency_id')
            ->where('status', 1)
            ->where(function ($query) use ($request) {
                $query->where('name_en', 'like', '%' . $request->search_value . '%')
                    ->orwhere('name_ar', 'like', '%' . $request->search_value . '%')
                    ->orwhere('sku', $request->search_value);
            })->limit(10)->get()->map(function($product) use($currencyService) {
                $product['final_selling_price'] = $currencyService->convertPrice($product,$product->final_selling_price);
                $product['old_price'] = number_format($product->final_selling_price / (1-($product->discount_percentage_selling_price/100)),2);
                return $product;
            });
        return $products;
    }

    public function get_sub_categories($category_id)
    {
        $categories = Category::where('status', 1)->where('category_id', $category_id)->get();
        return $categories;
    }

    public function deleteAccount(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'the current password is not correct!']);
        }
        $user->delete();
        return redirect('login');
    }


    public function update_wallet_used_status(Request $request){
        $user=Auth::user();
        $user->update([
            'wallet_used'=>! $user->wallet_used
        ]);
        return redirect()->back();
    }
}
