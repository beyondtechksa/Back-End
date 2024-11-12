<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'list',
        'top_list',
        'mobile_top_list',
        'name',
        'menu_name',
        'desc',
        'slug',
        'category_id',
        'image',
        'status',
        'show_in_navbar',
        'mark_as_top_category',
        'mark_as_mobile_top_category',
        'admin_id',
    ];


    public $translatable = ['name', 'desc', 'menu_name'];
    protected $appends = ['translated_name', 'translated_desc', 'all_children'];



    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }

    public function getTranslatedDescAttribute($value)
    {
        return @$this->desc;
    }



    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function getAllChildrenAttribute()
    {
        return [];
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'category_id')->with('children')->orderBy('list', 'asc')->where('status',true);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id')->orderBy('list', 'asc');
    }
    public function allParents()
    {
        return $this->parent()->with('allParents')->orderBy('list', 'asc');
    }

    public function getAllChildCategories()
    {
        $categories = collect();

        $stack = [$this];
        while (!empty($stack)) {
            $category = array_pop($stack);
            $categories->push($category);
            foreach ($category->children as $child) {
                $stack[] = $child;
            }
        }

        return $categories;
    }


    public function scopeNavCategories($query)
    {
        return $query->whereNull('category_id')
            ->where('show_in_navbar', true)
            ->where('status', true)
            ->withCount('children')
            ->with(['children' => function ($query) {
                $query->where('show_in_navbar', true);
                $query->where('status', true);
            }]);
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }

}
