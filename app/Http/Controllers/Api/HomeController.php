<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OrderCreationException;
use App\Http\Controllers\Controller;
use App\Http\Enums\CacheEnums;
use App\Http\Requests\StoreRateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\CartDiscount;
use App\Http\Resources\ProductResourceCollection;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rates;
use App\Models\Settings;
use App\Models\User;
use App\Rules\ValidCoupon;
use App\Services\FilterService;
use App\Services\GlobalService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
    public function filterProducts(Request $request, FilterService $filterService)
    {
        $currency = $request->header('currency', 'SAR');
        $validator = Validator::make(['currency' => $currency], [
            'currency' => 'nullable|in:USD,SAR,TRY'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        $subCategories = [];
        $filter_data = $request->input('filter_data', []);
        if (\request()->has('category_id') && \request('category_id') != null) {
            $leafCategories = collect();

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
            $filter_data['subCategories'] = array_key_exists('subCategories', $filter_data) && is_array($filter_data['subCategories'])
                ? collect($filter_data['subCategories'])->merge($leafCategories)->unique()->values()->all()
                : $leafCategories->values()->all();
            $subCategories = Category::where('status', 1)->whereIn('id', $leafCategories)->get();
        }

        $products = new ProductResourceCollection($filterService->filter($filter_data), $currency);
        $data = [
            'products' => $products,
            'subCategories' => $subCategories,
        ];
        return returnSuccess('products', $data, 'success');
    }

    public function storeOrder(OrderService $orderService, Request $request)
    {
        try {
            $currency = $request->header('currency', 'SAR');
            $validator = Validator::make($request->all(), [
                'payment_id' => 'required|integer|exists:payment_transactions,id'
            ]);
            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);
            $result = (new PaymentService())->checkPayment(\user()->id);
            if (isset($result['order']['status']['code']) && $result['order']['status']['code'] == 3) {
                DB::table('payment_transactions')
                    ->where('uuid', $result['order']['ref'])
                    ->where('id', $request->payment_id)
                    ->update(['response' => $result, 'status' => 1]);
                $order = $orderService->order(Auth::user()->id, $request->payment_id, $currency);
                DB::table('payment_transactions')
                    ->where('user_id', user()->id)
                    ->where('status', 0)
                    ->whereNull('response')
                    ->delete();
                return returnSuccess('order', $order, 'success');
            } else {
                return response()->json(['error' => 'payment failed', 'result' => $result], 404);
            }
        } catch (OrderCreationException $exception) {
            return response()->json([
                'message' => 'Order creation failed',
                'error' => $exception->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function useCoupon(Request $request, GlobalService $globalService)
    {
        $validCouponRule = new ValidCoupon();
        $request->validate([
            'code' => ['required', 'string', 'max:255', $validCouponRule],
        ]);
        $coupon = $validCouponRule->getCoupon();
        $cart_discount = CartDiscount::where('user_id', Auth::user()->id)
            ->where('coupon_code', $coupon->code)->first();
        if (!$cart_discount) {
            $discount = $globalService->useCoupon(Auth::user()->id, $coupon);
            return returnSuccess('discount', $discount, 'success');
        }
        return response()->json(['error' => 'The code already used'], 400);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:255|unique:users,phone,' . Auth::id(),
        ]);
        $data = $request->all();
        $user = User::find(auth()->id());
        if ($request->hasFile('image')) {
            $avatar = uploadImage($request->file('image'), 'avatars');
            $data['avatar'] = $avatar;
        }
        $user->update($data);
        $user->avatar = url($user->avatar ?? '/image/default.png');
        return returnSuccess('message', $user, 'success');
    }

    public function search(FilterService $filterService, Request $request)
    {
        $currency = $request->header('currency', 'SAR');
        $validator = Validator::make(['currency' => $currency], [
            'currency' => 'nullable|in:USD,SAR,TRY'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        $validator = Validator::make($request->all(), [
            'search_data.search' => 'required',
            'search_data.offset' => 'required|integer',
            'search_data.limit' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 422);

        $products = new ProductResourceCollection($filterService->search($request->search_data), $currency);
        return returnSuccess('products', $products, 'success');
    }

    //all products or ( trending - featured - sale - new_arrival )
    public function products(Request $request)
    {
        $currency = $request->header('currency', 'SAR');
        $validator = Validator::make(['currency' => $currency], [
            'currency' => 'nullable|in:USD,SAR,TRY'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        $leafCategories = collect();
        $perPage = $request->get('limit', 9); // Default to 9 items per page
        $productsQuery = Product::with('brand')
            ->where('status', 1)
            ->when($request->type, function ($query, $type) {
                $conditions = [
                    'trending' => ['trending', 1],
                    'featured' => ['featured', 1],
                    'new_arrival' => ['new_arrival', 1],
                    'sale' => ['discount_percentage_selling_price', '>', 0.00],
                ];
                if (isset($conditions[$type]))
                    $query->where(...$conditions[$type]);
            })->when($request->category_id, function ($q, $category_id) use ($leafCategories) {
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
            ->latest();
        $products = $productsQuery->paginate($perPage);
        $currencyService=new CurrencyService();
        $products->getCollection()->transform(function ($product) use($currencyService){
            if ($product->brand && $product->brand->image) {
                $product->brand->image = url($product->brand->image);

            }
            $product['final_selling_price'] = $currencyService->convertPrice($product,$product->final_selling_price);
            return $product;
        });
        $products = new ProductResourceCollection($products, $currency);
        return returnSuccess('products', $products, 'success');
    }

    public function loadTerlIframe(Request $request)
    {
        $currency = $request->header('currency', 'SAR');
        $carts = Cart::with('product.brand')->where('user_id', auth()->id())->get();
        $res = (new PaymentService())->loadTelrIframe(auth()->id(), $carts, $currency);
        $frame = isset($res) && $res['order'] && $res['order']['url'] ? $res['order']['url'] : null;
        if ($frame == null)
            return response()->json(['errors' => 'something went wrong', 'result' => $res], 400);
        $payment = DB::table('payment_transactions')
            ->where('uuid', $res['order']['ref'])->first();
        $data = [
            'iframe' => $frame,
            'payment' => $payment
        ];
        return returnSuccess('data', $data, 'success');

    }

    public function rateProduct(StoreRateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        if ($request->file) {
            $path = uploadImage($request->file('file'), 'ratings');
            $data['file'] = $path;
        }
        Rates::create($data);
        return returnSuccess('rate', '', 'add successfully, kindly wait for review');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'password' => 'required|current_password',
        ]);
        $user->delete();
        $user->tokens()->delete();
        return returnSuccess('message', '', 'Account deleted successfully.!');
    }

    public function resetAccount(Request $request)
    {
        $user = User::onlyTrashed()
            ->where('email', $request->email)
            ->first();
        if (!$user)
            return response()->json(['errors' => 'User not found'], Response::HTTP_NOT_FOUND);

        $user->restore();
        return returnSuccess('message', '', 'User has been restored. you can login now');
    }
    public function socials()
    {
        $setting = Cache::remember(CacheEnums::SOCIAL_LINKS(), CacheEnums::CACHE_TIME, function () {
            return Settings::whereSlug('social')->first();
        });
        return returnSuccess('socials', $setting?->value, 'success');
    }

    public function notifications()
    {
        $limit = request()->get('limit', 10);
       $notification= Auth::user()->notifications()->paginate($limit);
       $data = [
           'notifications' => $notification,
           'unreadNotificationCount' => Auth::user()->unreadNotifications()->count(),
       ];
       return returnSuccess('notifications', $data, 'success');
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        $notification->markAsRead();
        return returnSuccess('message', '', 'Notification marked as read');
    }
}
