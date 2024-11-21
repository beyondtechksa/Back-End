<?php


namespace App\Http\Enums;

use BenSampo\Enum\Enum;
use phpseclib3\Crypt\EC\Curves\secp112r1;

final class CacheEnums extends Enum
{
    const ACTIVE_LANGUAGES = 'active_languages';
    const LOCAL_LANGUAGES = 'locale_';
    const CATEGORIES_WITH_PARENTS = 'categories_with_parents';
    const MAIN_CATEGORIES = 'main_categories';
    const MAINCURRENCY = 'MAINCURRENCY';
    const CURRENCIES = 'currencies';
    const CURRENCIES_SAR_USD_TRY = 'currencies_sar_usd_try';
    const COLORS = 'all_colors';
    const SIZES = 'all_sizes';
    const NAV_CATEGORIES = 'nav_categories';
    const NAV_NEWSBAR = 'nav_newsbar';
    const SETTINGS = 'all_settings';
    const NEWS = 'news_setting';
    const CATEGORIES_SETTING = 'categories_setting';
    const BANNER_SETTING = 'banner_setting';
    const MEDIA = 'media';
    const GENERALSETTINGS = 'general_settings';

    const CACHE_TIME = 60 * 60 * 24;
    const ACTIVE_BRANDS = 'active_brands';

    const TOP_CATEGORIES = 'top_categories';
    const TOP_MOBILE_CATEGORIES = 'top_mobile_categories';
    const ALL_MOBILE_BANNER = 'all_mobile_banner_';

    const SOCIAL_LINKS = 'social_links';

    const CACHE_CATEGORIES = [
        self::CATEGORIES_WITH_PARENTS,
        self::MAIN_CATEGORIES,
        self::NAV_CATEGORIES,
        self::TOP_CATEGORIES,
        self::TOP_MOBILE_CATEGORIES,

    ];
    const CACHE_MOBILE_BANNERS = [
        self::ALL_MOBILE_BANNER,
    ];
    const CACHE_LANGUAGES = [
        self::ACTIVE_LANGUAGES,
        self::LOCAL_LANGUAGES,
    ];

    const CACHE_CURRENCIES = [
        self::CURRENCIES,
        self::CURRENCIES_SAR_USD_TRY,
    ];

    const CACHE_BRANDS = [
        self::ACTIVE_BRANDS,
    ];
    const CACHE_SETTINGS = [
        self::SETTINGS,
        self::NEWS,
        self::CATEGORIES_SETTING,
        self::BANNER_SETTING,
        self::NAV_NEWSBAR,
        self::SOCIAL_LINKS,
    ];

}
