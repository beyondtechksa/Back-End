<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class PageObserver
{
    /**
     * Handle the Category "creating" event.
     */
    public function creating(Page $page)
    {
        $page->slug = unique_slug($page->getTranslation('name', 'en'), 'Page');
    }
    public function updating(Page $page)
    {
        $page->slug = unique_slug($page->getTranslation('name', 'en'), 'Page');
    }




}
