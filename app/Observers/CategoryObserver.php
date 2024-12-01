<?php

namespace App\Observers;

use App\Http\Enums\CacheEnums;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{

    public function creating(Category $category)
    {
        if (Auth::guard('admin')->check()) {
            $category->admin_id = Auth::guard('admin')->id();
        }
        $category->slug = unique_slug($category->getTranslation('name', 'en'), 'Category');
    }


    public function updating(Category $category)
    {
        if (Auth::guard('admin')->check()) {
            $category->admin_id = Auth::guard('admin')->id();
        }
    }

  
    public function saved(Category $category)
    {
        $this->clearCategoryCache();
    }

 
    public function deleted(Category $category)
    {
        $this->clearCategoryCache();
    }


    private function clearCategoryCache()
    {
        clearGlobalCache(CacheEnums::CACHE_CATEGORIES);
        Cache::forget(CacheEnums::HOMEPAGECACHE());
    }
}
