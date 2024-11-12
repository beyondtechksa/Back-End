<?php

namespace App\Providers;

use App\Services\AddressesService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Models\Category;
use App\Observers\CategoryObserver;
use App\Models\Page;
use App\Observers\PageObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        $this->app->singleton(AddressesService::class,
//            fn() => AddressesService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        Page::observe(PageObserver::class);
    }
}
