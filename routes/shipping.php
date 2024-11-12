<?php
use App\Http\Controllers\Shipping\ShippingAuthController;
use App\Http\Controllers\Shipping\ShippingController;


Config::set('auth.defines', 'shipping');

Route::prefix('shipping')->group(function () {
    Route::get('login', [ShippingAuthController::class, 'showLoginForm'])->name('shipping.login');
    Route::post('login', [ShippingAuthController::class, 'login'])->name('shipping.dologin');
    Route::post('logout', [ShippingAuthController::class, 'logout'])->name('shipping.logout');
    Route::group(['middleware' => 'shipping:shipping'], function () {
        Route::get('/dashboard', [ShippingController::class, 'index'])->name('shipping.dashboard');
        Route::get('/profile', [ShippingController::class, 'profile'])->name('shipping.profile');
        Route::get('/notifications', [ShippingController::class, 'notifications'])->name('shipping.notifications');
        Route::get('/orders/{status?}', [ShippingController::class, 'orders'])->name('shipping.orders');
        Route::get('/show-order/{id}', [ShippingController::class, 'show_order'])->name('shipping.order.show');
        Route::post('/update_order', [OrdersController::class, 'update_order']);
        Route::post('/update_order_request_status', [ShippingController::class, 'update_order_request_status'])->name('shipping.update_order_request_status');
        Route::post('/update_traking_code', [ShippingController::class, 'update_traking_code'])->name('shipping.update_traking_code');
        Route::post('/update_shipment', [ShippingController::class, 'update_shipment'])->name('shipping.update_shipment');
        Route::post('/export-orders-session', [VendorController::class, 'export_orders_session'])->name('vendor.export_orders_session');
        Route::get('/export-orders', [VendorController::class, 'export_orders'])->name('vendor.export_orders');
        Route::put('markAsRead/{id}', [ShippingController::class, 'markReadNotification'])->name('shipping.mark.read');
        Route::get('/get_pending_orders', [ShippingController::class, 'get_pending_orders'])->name('shipping.get_pending_orders');
        Route::resources([
            'externa_shipping_companies' => App\Http\Controllers\Shipping\ExternalShippingCompaniesController::class,
        ]);
    });
});
