<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartController;
use App\Http\Controllers\Admin\CategoriesController;




use Inertia\Inertia;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('slug', function () {
   return 'test';
});


Route::get('lang/{lang}', function ($lang) {
    if (! in_array($lang, languages()->pluck('symbol')->toArray())) {
        session()->put('lang', 'en');
    } else {
        session()->put('lang', $lang);
    }
    return back();
});
Route::get('/', [StartController::class, 'home'])->name('welcome');
Route::get('categories', [StartController::class, 'categories'])->name('categories');
Route::get('shop', [StartController::class, 'shop'])->name('products.shop');
Route::post('filter-shop-request', [StartController::class, 'filterShopRequest'])->name('products.filter.shop');
Route::post('load-more-products', [StartController::class, 'loadMoreProducts'])->name('products.load.more');
Route::get('products', [StartController::class, 'products'])->name('products.products');
Route::get('collections/{slug}', [StartController::class, 'collections']);
Route::get('brands/{slug}', [StartController::class, 'brands']);
Route::get('product/{id}/{slug?}', [StartController::class, 'product_details'])->name('product.show');
Route::get('get_setting/{slug}', [StartController::class, 'get_setting']);
Route::get('get_nav_categories', [StartController::class, 'get_nav_categories']);
Route::post('save_redirect_session', [StartController::class, 'save_redirect_session']);
Route::get('get_footer_settings', [StartController::class, 'get_footer_settings']);
Route::get('page/{slug}', [StartController::class, 'page'])->name('page');
Route::get('shop-by-brand', [StartController::class, 'shop_by_brand'])->name('shop_by_brand');

//api
Route::get('api/get_children/{id}', [StartController::class, 'get_children']);
Route::post('/subscribe/store', [StartController::class, 'subscribe'])->name('subscribe.store');
Route::get('/search_products', [StartController::class, 'search_products']);
Route::get('/get_sub_categories/{category_id}', [StartController::class, 'get_sub_categories']);

//cookie and cart
Route::post('/product/cart/add', [StartController::class, 'add_cart'])->name('product.add_cart');
Route::post('/product/cart/delete', [StartController::class, 'deleteCartItem'])->name('product.deleteCartItem');
Route::get('/cart', [StartController::class, 'cart'])->name('cart');
Route::get('/get-cookie-cart', [StartController::class, 'getCookieCart'])->name('cart.cookie');
Route::get('/get-cart', [StartController::class, 'getCart'])->name('cart.get');
Route::post('/cart/update_quantity', [StartController::class, 'update_quantity'])->name('cart.update_quantity');
Route::post('/cart/update_size_color', [StartController::class, 'update_size_color'])->name('cart.update_size_color');
Route::post('/cart/select_items', [StartController::class, 'select_items'])->name('cart.select_items');
//end cookie


Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
Route::get('auth/apple', [SocialiteController::class, 'redirectToApple']);
Route::post('auth/apple/callback', [SocialiteController::class, 'handleAppleCallback']);
Route::post('/calculate_order', [StartController::class, 'calculate_order']);

///test

    Route::post('cart/checkout/payment/callback', [StartController::class,'handleCallback'])->name('payment.callback');

Route::get('cart/checkout/payment/click-pay', [StartController::class,'createClickpayPayment'])->name('payment.store');
Route::get('/clickpay/order_success/{id}', action: [StartController::class, 'order_success_click_pay'])->name('order.clickpay.success');
Route::get('/tamara/order_success/{id}', action: [StartController::class, 'order_success_click_pay'])->name('order.tamara.success');


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [StartController::class, 'dashboard'])->name('dashboard');
    // Route::get('/basic-info', [ProfileController::class, 'basic_info'])->name('profile.basic_info');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::get('/track-order/{id?}', [ProfileController::class, 'track_order'])->name('profile.track_order');
    Route::get('/return-order/{id?}', [ProfileController::class, 'return_order'])->name('profile.return_order');
    Route::get('/return-order-item-details/{id?}', [ProfileController::class, 'return_order_item_details'])->name('profile.return_order_item_details');
    Route::get('/return-order-details/{id?}', [ProfileController::class, 'return_order_details'])->name('profile.return_order_details');
    Route::post('/return-item/cancel', [ProfileController::class, 'cancel_return_item'])->name('profile.return_item.cancel');
    Route::post('/store_return_request', [ProfileController::class, 'store_return_request'])->name('profile.store_return_request');
    Route::post('/track-order', [ProfileController::class, 'track_order_post'])->name('profile.track_order_post');
    Route::get('/addresses-create', [ProfileController::class, 'create_address'])->name('address.create');
    Route::get('/addresses-edit/{id}', [ProfileController::class, 'edit_address'])->name('address.edit');
    Route::post('/addresses-store', [ProfileController::class, 'store_address'])->name('address.store');
    Route::post('/addresses-update', [ProfileController::class, 'update_address'])->name('address.update');
    Route::get('/my-orders', [ProfileController::class, 'my_orders'])->name('profile.orders');
    Route::post('/product/favourite/add', [StartController::class, 'add_favourite'])->name('product.add_favourite');
    Route::post('/cart/update_attribute', [StartController::class, 'update_attribute'])->name('cart.update_attribute');
    Route::get('/favourites', [StartController::class, 'favourites'])->name('favourites');
    Route::get('/cart/checkout/address', [StartController::class, 'checkout'])->name('cart.checkout.address');
    Route::get('/cart/checkout/payment', [StartController::class, 'payment'])->name('cart.checkout.payment');
    Route::get('/cart/checkout/tamara/payment', [StartController::class, 'tamaraPayment'])->name('cart.checkout.tamara.payment');


    Route::post('/cart_checkout', [StartController::class, 'go_checkout'])->name('cart.go_checkout');
    Route::post('/cart_add_coupon', [StartController::class, 'add_coupon'])->name('cart.add_coupon');
    Route::post('/create_order', [StartController::class, 'create_order'])->name('order.create');
    Route::get('/order_success', action: [StartController::class, 'order_success'])->name('order.success');

    Route::post('/create_rate', [StartController::class, 'storeRate'])->name('store.rating');
    Route::post('/delete-account', [StartController::class, 'deleteAccount'])->name('delete.account');
});

Route::get('/api/get_main_parents', [CategoriesController::class, 'get_main_parents']);
Route::get('/api/get_children/{id}', [CategoriesController::class, 'get_children']);
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/vendor.php';
require __DIR__ . '/shipping.php';





