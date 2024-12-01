<?php

namespace App\Observers;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Http\Enums\CacheEnums;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{

    public function created(Settings $settings)
    {
        $this->clearSettingCache();
    }
    public function updated(Settings $settings)
    {
        $this->clearSettingCache();
    }
    public function deleted(Settings $settings)
    {
        $this->clearSettingCache();
    }

    private function clearSettingCache()
    {
        clearGlobalCache(CacheEnums::CACHE_SETTINGS());
        Cache::forget(CacheEnums::HOMEPAGECACHE());
    }



}
