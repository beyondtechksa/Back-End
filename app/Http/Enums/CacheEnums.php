<?php 

namespace App\Http\Enums;

use BenSampo\Enum\Enum;

final class CacheEnums extends Enum
{
    const CACHE_TIME = 60 * 60 * 24;

    // Define a method to get the prefix from the environment
    private static function getPrefix(): string
    {
        return env('CACHE_PREFIX', 'default_prefix_');
    }

    // Define methods for each cache constant to apply the prefix dynamically
    public static function HOMEPAGECACHE(): string
    {
        return self::getPrefix() . 'home_page_cache';
    }

    public static function ACTIVE_LANGUAGES(): string
    {
        return self::getPrefix() . 'active_languages';
    }

    public static function LOCAL_LANGUAGES(): string
    {
        return self::getPrefix() . 'locale_';
    }

    public static function CATEGORIES_WITH_PARENTS(): string
    {
        return self::getPrefix() . 'categories_with_parents';
    }

    public static function MAIN_CATEGORIES(): string
    {
        return self::getPrefix() . 'main_categories';
    }

    public static function MAINCURRENCY(): string
    {
        return self::getPrefix() . 'MAINCURRENCY';
    }

    public static function CURRENCIES(): string
    {
        return self::getPrefix() . 'currencies';
    }

    public static function CURRENCIES_SAR_USD_TRY(): string
    {
        return self::getPrefix() . 'currencies_sar_usd_try';
    }

    public static function COLORS(): string
    {
        return self::getPrefix() . 'all_colors';
    }

    public static function SIZES(): string
    {
        return self::getPrefix() . 'all_sizes';
    }

    public static function NAV_CATEGORIES(): string
    {
        return self::getPrefix() . 'nav_categories';
    }

    public static function NAV_NEWSBAR(): string
    {
        return self::getPrefix() . 'nav_newsbar';
    }

    public static function SETTINGS(): string
    {
        return self::getPrefix() . 'all_settings';
    }

    public static function NEWS(): string
    {
        return self::getPrefix() . 'news_setting';
    }

    public static function CATEGORIES_SETTING(): string
    {
        return self::getPrefix() . 'categories_setting';
    }

    public static function BANNER_SETTING(): string
    {
        return self::getPrefix() . 'banner_setting';
    }

    public static function MEDIA(): string
    {
        return self::getPrefix() . 'media';
    }

    public static function GENERALSETTINGS(): string
    {
        return self::getPrefix() . 'general_settings';
    }

    public static function TRENDINGPRODUCTS(): string
    {
        return self::getPrefix() . 'trending_products';
    }

    public static function FEATUREDPRODUCTS(): string
    {
        return self::getPrefix() . 'featured_products';
    }

    public static function NEWARRIVALPRODUCTS(): string
    {
        return self::getPrefix() . 'new_arrival_products';
    }

    public static function SOCIAL_LINKS(): string
    {
        return self::getPrefix() . 'social_links';
    }

    public static function ACTIVE_BRANDS(): string
    {
        return self::getPrefix() . 'active_brands';
    }

    public static function TOP_CATEGORIES(): string
    {
        return self::getPrefix() . 'top_categories';
    }

    public static function TOP_MOBILE_CATEGORIES(): string
    {
        return 'top_mobile_categories'; // This does not need a prefix since it's already set
    }

    public static function ALL_MOBILE_BANNER(): string
    {
        return 'all_mobile_banner_'; // This does not need a prefix since it's already set
    }

    // Define the cache groups as methods as well
    public static function CACHE_CATEGORIES(): array
    {
        return [
            self::CATEGORIES_WITH_PARENTS(),
            self::MAIN_CATEGORIES(),
            self::NAV_CATEGORIES(),
            self::TOP_CATEGORIES(),
            self::TOP_MOBILE_CATEGORIES(),
        ];
    }

    public static function CACHE_PRODUCTS(): array
    {
        return [
            self::TRENDINGPRODUCTS(),
            self::FEATUREDPRODUCTS(),
            self::NEWARRIVALPRODUCTS(),
        ];
    }

    public static function CACHE_MOBILE_BANNERS(): array
    {
        return [
            self::ALL_MOBILE_BANNER(),
        ];
    }

    public static function CACHE_LANGUAGES(): array
    {
        return [
            self::ACTIVE_LANGUAGES(),
            self::LOCAL_LANGUAGES(),
        ];
    }

    public static function CACHE_CURRENCIES(): array
    {
        return [
            self::CURRENCIES(),
            self::CURRENCIES_SAR_USD_TRY(),
        ];
    }

    public static function CACHE_BRANDS(): array
    {
        return [
            self::ACTIVE_BRANDS(),
        ];
    }

    public static function CACHE_SETTINGS(): array
    {
        return [
            self::SETTINGS(),
            self::NEWS(),
            self::CATEGORIES_SETTING(),
            self::BANNER_SETTING(),
            self::NAV_NEWSBAR(),
            self::SOCIAL_LINKS(),
        ];
    }
}
