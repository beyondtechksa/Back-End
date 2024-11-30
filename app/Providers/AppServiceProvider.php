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
use App\Models\Settings;
use App\Observers\SettingObserver;
use App\Models\Size;
use App\Observers\SizeObserver;
use App\Models\Color;
use App\Observers\ColorObserver;
use App\Models\Language;
use App\Observers\LanguageObserver;
use App\Models\Currency;
use App\Observers\CurrencyObserver;

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
        Settings::observe(SettingObserver::class);
        Size::observe(SizeObserver::class);
        Color::observe(ColorObserver::class);
        Language::observe(LanguageObserver::class);
        Currency::observe(CurrencyObserver::class);
    }
}
