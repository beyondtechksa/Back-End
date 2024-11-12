<?php

namespace App\Observers;

use App\Http\Enums\CacheEnums;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     */
    public function creating(Category $category)
    {
        if (Auth::guard('admin')->check()) {
            $category->admin_id = Auth::guard('admin')->id();
        }
        $category->slug = unique_slug($category->getTranslation('name', 'en'), 'Category');
    }

    /**
     * Handle the Category "updating" event.
     */
    public function updating(Category $category)
    {
        if (Auth::guard('admin')->check()) {
            $category->admin_id = Auth::guard('admin')->id();
        }
    }

    /**
     * Handle the Category "saved" event.
     */
    public function saved(Category $category)
    {
        $this->clearCategoryCache();
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category)
    {
        $this->clearCategoryCache();
    }

    /**
     * Clear the categories cache.
     */
    private function clearCategoryCache()
    {
        clearGlobalCache(CacheEnums::CACHE_CATEGORIES);
    }
}
