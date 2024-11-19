<?php

namespace App\Observers;

use App\Http\Enums\CacheEnums;
use App\Models\Language;

class LanguageObserver
{
    /**
     * Handle the Language "created" event.
     */
    public function created(Language $language): void
    {
       $this->clearLanguageCache();
    }

    /**
     * Handle the Language "updated" event.
     */
    public function updated(Language $language): void
    {
       $this->clearLanguageCache();
    }

    /**
     * Handle the Language "deleted" event.
     */
    public function deleted(Language $language): void
    {
        $this->clearLanguageCache();
    }

    /**
     * Handle the Language "restored" event.
     */
    public function restored(Language $language): void
    {
        //
    }

    /**
     * Handle the Language "force deleted" event.
     */
    public function forceDeleted(Language $language): void
    {
        //
    }

    private function clearLanguageCache()
    {
        clearGlobalCache(CacheEnums::CACHE_LANGUAGES);
    }

}
