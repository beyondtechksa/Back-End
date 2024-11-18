<?php

namespace App\Observers;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Http\Enums\CacheEnums;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{

    public function creating(Settings $settings)
    {
        Cache::forget(CacheEnums::SETTINGS);
    }
    public function updating(Settings $settings)
    {
        Cache::forget(CacheEnums::SETTINGS);
    }




}
