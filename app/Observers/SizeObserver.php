<?php

namespace App\Observers;

use App\Models\Size;
use App\Http\Enums\CacheEnums;
use Illuminate\Support\Facades\Cache;

class SizeObserver
{
    /**
     * Handle the Size "created" event.
     */
    public function created(Size $size): void
    {
        Cache::forget(CacheEnums::SIZES);
    }

    /**
     * Handle the Size "updated" event.
     */
    public function updated(Size $size): void
    {
        Cache::forget(CacheEnums::SIZES);
    }

    /**
     * Handle the Size "deleted" event.
     */
    public function deleted(Size $size): void
    {
        Cache::forget(CacheEnums::SIZES);
    }

    /**
     * Handle the Size "restored" event.
     */
    public function restored(Size $size): void
    {
        //
    }

    /**
     * Handle the Size "force deleted" event.
     */
    public function forceDeleted(Size $size): void
    {
        //
    }
}
