<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\MobilePaymentController;
use App\Http\Controllers\StartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('cart/checkout/payment/return', [StartController::class,'handleReturn'])->name('payment.return');


Route::get('/get_all_categories', [ApiController::class, 'get_all_categories']);
Route::get('/brands', [ApiController::class, 'brands']);
Route::get('/colors', [ApiController::class, 'colors']);
Route::get('/sizes', [ApiController::class, 'sizes']);

Route::get('/home', [ApiController::class, 'home']);
Route::get('/categories-banners', [ApiController::class, 'categoriesBanners']);
Route::post('/search-products', [HomeController::class, 'search']);
Route::get('/products', [HomeController::class, 'products']);
Route::post('/reset-account', [HomeController::class, 'resetAccount']);


Route::get('/get_main_categories', [ApiController::class, 'get_main_categories']);
Route::get('/news_bar', [ApiController::class, 'news_bar']);
Route::get('/top_slider', [ApiController::class, 'top_slider']);
Route::get('/top_categories', [ApiController::class, 'top_categories']);
Route::get('/single_banner', [ApiController::class, 'single_banner']);
Route::get('/new_in', [ApiController::class, 'new_in']);
Route::get('/single_banner2', [ApiController::class, 'single_banner2']);
Route::get('/featured', [ApiController::class, 'featured']);
Route::get('/banners2', [ApiController::class, 'banners2']);
Route::get('/shop_by_brand', [ApiController::class, 'shop_by_brand']);
Route::get('/home_forniture', [ApiController::class, 'home_forniture']);
Route::get('/test-xml', [ApiController::class, 'test_xml']);
Route::get('/socials', [HomeController::class, 'socials']);

///

Route::get('/products/{featured}', [ApiController::class, 'typed_products']);
Route::get('/single-product', [ApiController::class, 'single_product']);
Route::get('/related-products', [ApiController::class, 'related_products']);
Route::get('/get_currencies', [ApiController::class, 'get_currencies']);
Route::post('/filter-products-shop', [HomeController::class, 'filterProducts']);
Route::get('/languages', [ApiController::class, 'languages']);
Route::get('/currencies', [ApiController::class, 'currencies']);
Route::get('/pages', [ApiController::class, 'pages']);


Route::group(['controller' => AuthController::class], function () {
    Route::post('login', 'login')->name('api.login');
    Route::post('register', 'register')->name('api.register');
    Route::post('register2Auth', 'register2Auth')->name('api.register.2auth');
    Route::post('forget-password', 'forgetPassword')->name('api.forget.password');
    Route::post('reset-password', 'resetPassword')->name('api.reset.password');
    Route::post('test/mobile/payment/click-pay', [MobilePaymentController::class, 'testInitiatePayment']);
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/mobile/payment/click-pay', [MobilePaymentController::class, 'initiatePayment']);

        Route::post('logout', 'logout')->name('api.logout');
        Route::post('update-password', 'updatePassword')->name('api.update.password');
        Route::get('profile', 'profile')->name('api.profile');
        Route::post('/add-to-cart', [ApiController::class, 'add_cart']);
        Route::post('/add-delete-favourite', [ApiController::class, 'add_delete_favourite']);
        Route::get('/favourites', [ApiController::class, 'my_favourites']);
        Route::post('/delete-cart-item', [ApiController::class, 'delete_cart_item']);
        Route::post('/delete-cart-item', [ApiController::class, 'delete_cart_item']);
        Route::post('/update-cart-quantity', [ApiController::class, 'update_quantity']);
        Route::post('/update-cart-size', [ApiController::class, 'update_size']);
        Route::post('/update-cart-color', [ApiController::class, 'update_color']);
        Route::post('/clear-cart', [ApiController::class, 'clearCart']);
        Route::get('/my-cart', [ApiController::class, 'my_cart']);
        Route::prefix('addresses')->group(function () {
            Route::get('/', [AddressController::class, 'index']);
            Route::post('/', [AddressController::class, 'store']);
            Route::put('/{id}', [AddressController::class, 'update']);
            Route::delete('/{id}', [AddressController::class, 'destroy']);
            Route::put('/favourite/{id}', [AddressController::class, 'favouriteAddress']);
        });
        Route::post('/store-order', [HomeController::class, 'storeOrder']);
        Route::get('/my-orders', [ApiController::class, 'my_orders']);
        Route::get('/order/{id}', [ApiController::class, 'order']);
        Route::post('/track-order', [ApiController::class, 'track_order']);

        Route::post('/use-coupon', [HomeController::class, 'useCoupon']);
        Route::post('/update-profile', [HomeController::class, 'updateProfile']);
        Route::post('/load-telr-iframe', [HomeController::class, 'loadTerlIframe']);
        Route::post('/delete-account', [HomeController::class, 'deleteAccount']);
        Route::post('/notifications', [HomeController::class, 'notifications']);
        Route::post('/mark-as-read/{id}', [HomeController::class, 'markAsRead']);
    });
});
