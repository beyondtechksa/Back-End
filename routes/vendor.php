<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Admin\ExternalShippingCompaniesController;

Config::set('auth.defines', 'vendor');

Route::prefix('vendor')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('vendor.login');
    Route::post('login', [AuthController::class, 'login'])->name('vendor.dologin');
    Route::post('logout', [AuthController::class, 'logout'])->name('vendor.logout');
    Route::group(['middleware' => 'vendor:vendor'], function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
        Route::get('/profile', [VendorController::class, 'profile'])->name('vendor.profile');
        Route::get('/notifications', [VendorController::class, 'notifications'])->name('vendor.notifications');
        Route::get('/order-requests/{status?}', [VendorController::class, 'order_requests'])->name('vendor.order_requests');
        Route::post('/update_order_request_status', [VendorController::class, 'update_order_request_status'])->name('vendor.update_order_request_status');
        Route::post('/update_traking_code', [VendorController::class, 'update_traking_code'])->name('vendor.update_traking_code');
        Route::post('/export-orders-session', [VendorController::class, 'export_orders_session'])->name('vendor.export_orders_session');
        Route::get('/export-orders', [VendorController::class, 'export_orders'])->name('vendor.export_orders');
        Route::put('markAsRead/{id}', [VendorController::class, 'markReadNotification'])->name('vendor.mark.read');
        Route::get('/get_pending_orders', [VendorController::class, 'get_pending_orders'])->name('vendor.get_pending_orders');
        Route::resources([
            'external_shipping_companies' => ExternalShippingCompaniesController::class,
        ]);
    });
});
