<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LanguagesController;
use App\Http\Controllers\Admin\WordsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\BrandsController;
use App\Http\Controllers\Admin\AttributesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CurrenciesController;
use App\Http\Controllers\Admin\CollectionsController;
use App\Http\Controllers\Admin\VendorsController;
use App\Http\Controllers\Admin\ShippingCompaniesController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\VouchersController;
use App\Http\Controllers\Admin\FormulasController;
use App\Http\Controllers\Admin\SizesController;
use App\Http\Controllers\Admin\ColorsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\ReportsController;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\RatesController;
use App\Http\Controllers\Admin\ReturnPoliciesController;
use App\Http\Controllers\Admin\ReturnReasonsController;
use App\Http\Controllers\Admin\ReturnStatusesController;
use App\Http\Controllers\Admin\GeneralSettingsController;

Route::get('test', [RolesController::class, 'test']);


//admin routes
Config::set('auth.defines', 'admin');
Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'dologin'])->name('admin.dologin');
Route::get("api/get_colors", [ProductsController::class, 'getColors']);

Route::group(['middleware' => 'admin:admin'], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::get('/categories/tree', [CategoriesController::class, 'tree'])->name('categories.tree');
        Route::get('/categories/top', [CategoriesController::class, 'top'])->name('categories.top');
        Route::get('/categories/mobile-top', [CategoriesController::class, 'mobile_top'])->name('categories.mobile_top');
        Route::resources([
            '/languages' => LanguagesController::class,
            '/words' => WordsController::class,
            '/roles' => RolesController::class,
            '/admins' => AdminsController::class,
            '/categories' => CategoriesController::class,
            '/brands' => BrandsController::class,
            '/attributes' => AttributesController::class,
            '/collections' => CollectionsController::class,
            '/products' => ProductsController::class,
            '/currencies' => CurrenciesController::class,
            '/vendors' => VendorsController::class,
            '/coupons' => CouponsController::class,
            '/vouchers' => VouchersController::class,
            '/formulas' => FormulasController::class,
            '/pages' => PagesController::class,
            '/customers' => UsersController::class,
            '/rates' => RatesController::class,
            '/shipping_companies' => ShippingCompaniesController::class,
            '/return_policies' => ReturnPoliciesController::class,
            '/return_reasons' => ReturnReasonsController::class,
            '/return_statuses' => ReturnStatusesController::class,
        ]);
        Route::get('/orders/return/requests', [OrdersController::class, 'return_requests'])->name('orders.return_requests');
        Route::get('/orders/companies', [OrdersController::class, 'companies'])->name('orders.companies');
        Route::get('/orders/companies/{name}', [OrdersController::class, 'company_requests'])->name('orders.company_requests');
        Route::get('/orders/vendors/{id}', [OrdersController::class, 'vendor_requests'])->name('orders.vendor_requests');
        Route::get('/orders/export/{name}', [OrdersController::class, 'export_company_requests'])->name('orders.export_company_requests');
        Route::post('/orders/send-company-email', [OrdersController::class, 'send_company_email'])->name('orders.company_email');
        Route::get('/products/import/{type?}', [ProductsController::class, 'import'])->name('products.import');
        Route::get('/scrap-products', [ProductsController::class, 'scrap_get']);
        Route::get('/tracking-history', [ProductsController::class, 'tracking_history']);
        Route::post('/scrap-products', [ProductsController::class, 'scrap']);
        Route::get('/scrap-company', [ProductsController::class, 'scrapCompany']);
        Route::get('/get-companies', [ProductsController::class, 'getCompanies']);
        Route::post('/scrap-product-companies', [ProductsController::class, 'companyScrapData']);
        Route::post('/return-request/update-status', [OrdersController::class, 'update_status'])->name('return_request.update_status');



        Route::get('/companies-categories', [ProductsController::class, 'getCompanyCategories']);
        Route::get('/product-sizes', [SizesController::class, 'product_sizes'])->name('products.sizes');
        Route::get('/product-colors', [ColorsController::class, 'product_colors'])->name('products.colors');
        Route::get('/products-search', [ProductsController::class, 'products_search'])->name('products.search');
        Route::get('/advanced-products-list', [ProductsController::class, 'advanced_products'])->name('products.advanced_products');
        Route::get('/bills', [OrdersController::class, 'bills'])->name('bills.index');
        Route::get('/bills/{id}', [OrdersController::class, 'show_bill'])->name('bills.show');
        Route::get('/bills-pdf/{id}', [OrdersController::class, 'view_bill'])->name('bills.pdf');

        //
        Route::get('/admins_filter', [AdminsController::class, 'filter']);
        Route::get('/languages_filter', [LanguagesController::class, 'filter']);
        Route::get('/words_filter', [WordsController::class, 'filter']);
        Route::get('/roles_filter', [RolesController::class, 'filter']);
        Route::get('/categories_filter', [CategoriesController::class, 'filter']);
        Route::get('/attributes_filter', [AttributesController::class, 'filter']);
        Route::get('/brands_filter', [BrandsController::class, 'filter']);
        Route::get('/products_filter', [ProductsController::class, 'filter']);
        Route::get('/currencies_filter', [CurrenciesController::class, 'filter']);
        Route::get('/collections_filter', [CollectionsController::class, 'filter']);
        Route::get('/vendors_filter', [VendorsController::class, 'filter']);
        Route::get('/coupons_filter', [CouponsController::class, 'filter']);
        Route::get('/vouchers_filter', [VouchersController::class, 'filter']);
        Route::get('/formulas_filter', [FormulasController::class, 'filter']);
        Route::get('/formulas_filter', [FormulasController::class, 'filter']);
        Route::get('/settings/{type}', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('/mobile-banners', [SettingsController::class, 'mobile_banners'])->name('settings.mobile_banners');
        Route::get('/settings/mobile/{slug}/{category_id?}', [SettingsController::class, 'mobile_edit'])->name('mobile_settings.edit');
        Route::post('/mobile_settings/store', [SettingsController::class, 'mobile_store'])->name('mobile_settings.store');
        Route::post('/mobile_settings/update', [SettingsController::class, 'mobile_update'])->name('mobile_settings.update');
        Route::post('/mobile_settings/delete', [SettingsController::class, 'mobile_delete'])->name('mobile_settings.delete');
        Route::get('/general-settings', [SettingsController::class, 'general_settings'])->name('general_settings.index');
        Route::get('/shipping', [SettingsController::class, 'shipping'])->name('shipping.index');
        Route::get('/settings/edit/{type}/{id}', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/orders/{status?}', [OrdersController::class, 'index'])->name('orders.index');
        Route::post('/update_order', [OrdersController::class, 'update_order']);
        Route::get('/view-order/{id}', [OrdersController::class, 'show'])->name('orders.show');
        Route::post('/category/update_shown_status', [CategoriesController::class, 'update_shown_status']);
        Route::post('/category/update_top_status', [CategoriesController::class, 'update_top_status']);
        Route::get('/customer_filter', [UsersController::class, 'filter']);
        Route::get('/customers-export/excel', [UsersController::class, 'export']);
        Route::get('/orders-export/excel', [OrdersController::class, 'export']);
        Route::get('/order_bill/{order_id}', [OrdersController::class, 'bill']);


        Route::post('/profile_update', [AdminController::class, 'profile_update'])->name('admin.profile_update');
        Route::post('/password_update', [AdminController::class, 'password_update'])->name('admin.password_update');
        Route::post('/shipping_update', [SettingsController::class, 'shipping_update'])->name('shipping.update');
        Route::get('/load-notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
        Route::get('/load-latest-orders', [OrdersController::class, 'latest_orders'])->name('orders.latest');


        // multi delete
        Route::post('/languages/multi_destroy', [LanguagesController::class, 'multi_destroy']);
        Route::post('/roles/multi_destroy', [RolesController::class, 'multi_destroy']);
        Route::post('/admins/multi_destroy', [AdminsController::class, 'multi_destroy']);
        Route::post('/categories/multi_destroy', [CategoriesController::class, 'multi_destroy']);
        Route::post('/brands/multi_destroy', [BrandsController::class, 'multi_destroy']);
        Route::post('/attributes/multi_destroy', [AttributesController::class, 'multi_destroy']);
        Route::post('/products/multi_destroy', [ProductsController::class, 'multi_destroy']);
        Route::post('/collections/multi_destroy', [CollectionsController::class, 'multi_destroy']);
        Route::post('/vendors/multi_destroy', [VendorsController::class, 'multi_destroy']);
        Route::post('/coupons/multi_destroy', [CouponsController::class, 'multi_destroy']);
        Route::post('/vouchers/multi_destroy', [VouchersController::class, 'multi_destroy']);
        Route::post('/formulas/multi_destroy', [FormulasController::class, 'multi_destroy']);


        Route::get('export_products_excel', [ProductsController::class, 'export_products_excel']);
        Route::get('get_main_categories', [CategoriesController::class, 'get_main_categories']);
        /// api routes
        Route::post('add_ids_to_session', [ProductsController::class, 'add_ids_to_session']);
        Route::get("api/get_colors", [ProductsController::class, 'getColors']);
        Route::get("api/get_sizes", [ProductsController::class, 'getSizes']);
        Route::get("api/get_media_categories/{type}", [MediaController::class, 'get_media_categories']);
        Route::get("api/get_media/{type}/{id?}", [MediaController::class, 'get_media']);
        Route::post("api/create_media_category", [MediaController::class, 'create_media_category']);
        Route::post("api/update_media_category", [MediaController::class, 'update_media_category']);
        Route::post("api/delete_media_category", [MediaController::class, 'delete_media_category']);
        Route::post("api/upload_file", [MediaController::class, 'upload_file']);
        Route::post("api/delete_media", [MediaController::class, 'delete_media']);
        Route::post("api/save_scraped_products", [ProductsController::class, 'save_scraped_products']);
        Route::post("api/save_scraped_company_products", [ProductsController::class, 'save_scraped_company_products']);
        Route::post("api/create_size", [SizesController::class, 'create_size']);
        Route::post("api/edit_size", [SizesController::class, 'edit_size']);
        Route::post("api/delete_size/{id}", [SizesController::class, 'delete_size']);
        Route::post("api/multi_delete_size", [SizesController::class, 'multi_delete_size']);
        Route::post("api/create_color", [ColorsController::class, 'create_color']);
        Route::post("api/edit_color", [ColorsController::class, 'edit_color']);
        Route::post("api/delete_color/{id}", [ColorsController::class, 'delete_color']);
        Route::post("api/multi_delete_color", [ColorsController::class, 'multi_delete_color']);
        Route::post("api/update_categories_list", [CategoriesController::class, 'update_categories_list']);
        Route::post("api/update_attributes_list", [AttributesController::class, 'update_attributes_list']);
        Route::post("api/update_categories_top_list", [CategoriesController::class, 'update_categories_top_list']);
        Route::get("api/get_temp_products/{type}", [ProductsController::class, 'get_temp_products']);
        Route::post("api/delete_tem_product/{id}", [ProductsController::class, 'delete_tem_product']);
        Route::post("api/delete_multiple_tem_product", [ProductsController::class, 'delete_multiple_tem_product']);
        Route::post("api/search_products", [ProductsController::class, 'search_products']);
        Route::post("api/move_to", [ProductsController::class, 'move_to']);
        Route::post("api/remove_collections", [ProductsController::class, 'remove_collections']);
        Route::post("api/multi_destroy_products", [ProductsController::class, 'multi_destroy_products']);
        Route::post("api/update_status", [ProductsController::class, 'update_status']);
        Route::post("api/update_products_formula", [ProductsController::class, 'update_products_formula']);
        Route::post("api/update_products_group", [ProductsController::class, 'update_products_group']);
        Route::post("api/update_products_discount", [ProductsController::class, 'update_products_discount']);
        Route::post("api/update_products_attributes", [ProductsController::class, 'update_products_attributes']);
        Route::post("api/update_products_colors", [ProductsController::class, 'update_products_colors']);
        Route::post("api/update_products_rename", [ProductsController::class, 'update_products_rename']);
        Route::post("api/update_translate_products", [ProductsController::class, 'update_translate_products']);
        Route::post("api/update_translate_description", [ProductsController::class, 'update_translate_description']);
        Route::post("api/import_excel_products", [ProductsController::class, 'import_excel_products']);
        Route::get("api/get_products_details/{id}", [ProductsController::class, 'get_products_details']);
        Route::post("delete_my_jobs", [ProductsController::class, 'delete_my_jobs']);
        Route::get("api/admin_permissions", [AdminController::class, 'admin_permissions']);


        Route::get("subscribes", [AdminController::class, 'subscribes']);
        Route::put('rates/changeStatus/{id}', [RatesController::class, 'changeStatus'])->name('rates.changeStatus');
        Route::get('/rates_filter', [RatesController::class, 'filter']);
        Route::post("create_order_item", [OrdersController::class, 'create_order_item']);
        Route::post("customers/send-mail/all", [UsersController::class, 'send_mail_fo_all']);

        Route::get('/reports/main', [ReportsController::class, 'main'])->name('reports.main');
        Route::get('/reports/orders', [ReportsController::class, 'orders']);
        Route::get('/reports/products', [ReportsController::class, 'products']);
        Route::get('/reports/carts', [ReportsController::class, 'carts']);

        Route::post('update_setting_status/{id}', [SettingsController::class, 'update_setting_status']);
        Route::put('markAsRead/{id}', [AdminController::class, 'markReadNotification'])->name('admin.mark.read');
        Route::get('/get-notifications', [AdminController::class, 'getNotifications'])->name('notifications.index');
        Route::post('/update_vendor_status', [VendorsController::class, 'update_vendor_status']);
        Route::post('/update_shipping_company_status', [ShippingCompaniesController::class, 'update_shipping_company_status']);

        Route::get('/general-settings',[GeneralSettingsController::class,'index'])->name('general_settings.index');
        Route::get('/general-settings/{id}',[GeneralSettingsController::class,'edit'])->name('general_settings.edit');
        Route::put('/general-settings/{id}',[GeneralSettingsController::class,'update'])->name('general_settings.update');

    });
});
