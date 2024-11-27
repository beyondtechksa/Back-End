<?php

namespace App\Observers;
use App\Models\Currency;
use Illuminate\Support\Facades\Auth;
use App\Services\SkuGenerator;
use App\Http\Enums\CacheEnums;

class CurrencyObserver
{



    public function created(){
        $this->clearCache();
    }
    public function updated(){
        $this->clearCache();
    }

    private function clearCache()
    {
        clearGlobalCache(CacheEnums::CACHE_PRODUCTS);
    }


}
