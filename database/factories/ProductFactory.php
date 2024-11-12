<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name_en'=> $this->faker->word,
             'name_ar'=> $this->faker->word,
              'name_tr'=> $this->faker->word,
               'desc_en'=> $this->faker->word,
                'desc_ar'=> $this->faker->word,
                 'desc_tr'=> $this->faker->word,
            'sku' => $this->faker->unique()->numerify('SKU-####'),
            'category_id' => Category::factory(),
            'collection_id' => Collection::factory(),
            'brand_id' => Brand::factory(),
            'vendor_id' => Vendor::factory(),  // Assuming you have a vendor with ID 1
            'quantity' => $this->faker->numberBetween(1, 100),
            'admin_id' => Admin::factory(),  // Assuming you have an admin with ID 1
            'shipping' => $this->faker->numberBetween(0, 1),
            'length' => $this->faker->randomFloat(2, 0, 100),
            'width' => $this->faker->randomFloat(2, 0, 100),
            'height' => $this->faker->randomFloat(2, 0, 100),
            'dimension_unit' => $this->faker->randomElement(['cm', 'in']),
            'weight' => $this->faker->randomFloat(2, 0, 100),
            'weight_unit' => $this->faker->randomElement(['kg', 'lb']),
            'attributes_ids' => json_encode([]),
            'colors_ids' => json_encode([]),
            'scraped_attributes' => json_encode([]),
            'discount_price' => $this->faker->randomFloat(2, 0, 100),
            'discount_percentage' => $this->faker->randomFloat(2, 0, 100),
            'start_at' => $this->faker->dateTime(),
            'end_at' => $this->faker->dateTime(),
            'points' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'sale_price' => $this->faker->randomFloat(2, 0, 100),
            'discount_percentage_selling_price' => $this->faker->randomFloat(2, 0, 100),
            'packaging_shipping_fees' => $this->faker->randomFloat(2, 0, 100),
            'management_fees' => $this->faker->randomFloat(2, 0, 100),
            'profit_percentage' => $this->faker->randomFloat(2, 0, 100),
            'tax_percentage' => $this->faker->randomFloat(2, 0, 100),
            'commercial_percentage' => $this->faker->randomFloat(2, 0, 100),
            'manual_price_adjustment' => $this->faker->randomFloat(2, 0, 100),
            'final_price' => $this->faker->randomFloat(2, 0, 100),
            'final_selling_price' => $this->faker->randomFloat(2, 0, 100),
            'source_link' => $this->faker->url,
            'sizes_ids' => json_encode([]),
            'model_number' => $this->faker->word,
            'status' => 1,
            'featured' => $this->faker->boolean,
            'new_arrival' => $this->faker->boolean,
            'ontop' => $this->faker->boolean,
            'home' => $this->faker->boolean,
            'trending' => $this->faker->boolean,
            'related' => $this->faker->boolean,
            'highlighted' => $this->faker->boolean,
            'most_sold' => $this->faker->boolean,
            'home_forniture' => $this->faker->boolean,
            'group_link' => $this->faker->word,
            'stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock']),
        ];
    }
}
