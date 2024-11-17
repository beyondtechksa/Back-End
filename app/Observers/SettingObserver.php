<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Http\Enums\CacheEnums;
use Illuminate\Support\Facades\Cache;

class SettingObserver
{

    public function creating(Page $page)
    {
        Cache::forget(CacheEnums::SETTINGS);
    }
    public function updating(Page $page)
    {
        Cache::forget(CacheEnums::SETTINGS);
    }




}
