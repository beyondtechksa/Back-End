<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductSlugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::where('slug',null)->chunk(100,function($products){
            foreach ($products as $product) {
                // Generate a new slug from the product's name
                $slug = unique_slug($product->name_tr,'Product');
                // Save the updated slug
                $product->update([
                    'slug'=>$slug
                ]);
                \Log::info('slug created');
            }
        });



        $this->command->info('Product slugs updated successfully.');
    }
}
