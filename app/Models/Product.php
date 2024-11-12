<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = [
        'company_product_id','company_name', 'name_en', 'name_ar', 'name_tr','slug', 'desc_en', 'desc_ar', 'desc_tr', 'group_id',
         'sku', 'category_id', 'collection_id','return_policy_id', 'brand_id', 'vendor_id', 'quantity', 'admin_id',
        'shipping', 'length', 'width', 'height', 'dimension_unit', 'weight', 'weight_unit',
        'attributes_ids', 'colors_ids', 'scraped_attributes','currency_id','discount_price', 'discount_percentage', 'start_at', 'end_at', 'points', 'image',
        'price', 'sale_price','old_price' ,'discount_percentage_selling_price', 'packaging_shipping_fees','vat',
        'management_fees', 'profit_percentage', 'tax_percentage', 'commercial_percentage',
        'manual_price_adjustment', 'final_price', 'final_selling_price', 'source_link', 'sizes_ids', 'model_number',
        'status', 'featured', 'new_arrival', 'ontop', 'home', 'trending', 'related', 'highlighted', 'most_sold', 'home_forniture', 'group_link', 'stock_status', 'visit','tracked_at'

    ];

    public $translatable = ['name', 'desc'];
    protected $appends = ['translated_name', 'translated_desc'];
    protected $casts = [
        'attributes_ids' => 'json',
    ];

    public function getTranslatedNameAttribute($value)
    {
        return @$this->name;
    }

    public function getTranslatedDescAttribute($value)
    {
        return @$this->desc;
    }

    public function product_attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function scopeWithAttributesAndOptions($query)
    {
        return $query->with([
            'product_attributes' => function ($query) {
                $query
                    ->with([
                        'attribute' => function ($query) {
                            $query;
                        },
                        'product_options' => function ($query) {
                            $query->with('option');
                        }
                    ]);
            }
        ]); // Adjust fields as needed
    }


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'product_id');
    }

    public function rates()
    {
        return $this->hasMany(Rates::class, 'product_id', 'id')
            ->where('status', 1)
            ->with(['user:id,name,avatar',]);
    }


    public function scopeWithRated($query)
    {
        return $query->withCount(['rates as rated' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rate), 0)'));
        }]);
    }


    public function sizes()
    {
        return $this->belongsToMany(Size::class)->with('size')
                    ->withPivot('inStock');

    }

    public function colors()
    {
        return $this->belongsToMany(Color::class)->with('color');
    }

    public function return_policy(){
        return $this->belongsTo(ReturnPolicy::class);
    }







}
