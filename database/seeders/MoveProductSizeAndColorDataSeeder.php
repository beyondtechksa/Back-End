<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class MoveProductSizeAndColorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products with sizes_ids and colors_ids
      Product::chunk(100,function($products){
        foreach ($products as $product) {
            Log::info('updating product');
            // Check if product already has sizes or colors in pivot tables
            $hasSizes = DB::table('product_size')->where('product_id', $product->id)->exists();
            $hasColors = DB::table('color_product')->where('product_id', $product->id)->exists();

            // Only process products without entries in pivot tables
            if (!$hasSizes && !$hasColors) {
                try {
                    // Process sizes_ids
                    $sizes = $product->sizes_ids; // Decode JSON to array

                    if (is_array($sizes)) {
                        foreach ($sizes as $size) {
                            // Check if size is an object with `id` and `inStock` or a simple ID
                            if (is_array($size) && isset($size['id'])) {
                                // If it's an object
                                $sizeId = $size['id'];
                                $inStock = $size['inStock'] ? 1 : 0; // Convert boolean to integer
                            } elseif (is_int($size)) {
                                // If it's a simple ID
                                $sizeId = $size;
                                $inStock = 1; // Default inStock value for simple ID
                            } else {
                                // Skip invalid size entries
                                continue;
                            }

                            // Check if the size_id is valid before inserting
                            if (DB::table('sizes')->where('id', $sizeId)->exists()) {
                                // Insert into the product_size pivot table
                                DB::table('product_size')->insert([
                                    'product_id' => $product->id,
                                    'size_id' => $sizeId,
                                    'inStock' => $inStock,
                                ]);
                            } else {
                                Log::warning("Size ID {$sizeId} does not exist for Product ID {$product->id}");
                            }
                        }
                    }

                    // Process colors_ids
                    $colors = $product->colors_ids;
                    if (is_array($colors)) {
                        foreach ($colors as $colorId) {
                            // Check if the color_id is valid before inserting
                            if (DB::table('colors')->where('id', $colorId)->exists()) {
                                DB::table('color_product')->insert([
                                    'product_id' => $product->id,
                                    'color_id' => $colorId,
                                ]);
                            } else {
                                Log::warning("Color ID {$colorId} does not exist for Product ID {$product->id}");
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing Product ID {$product->id}: " . $e->getMessage());
                }
            } else {
                Log::info("Product ID {$product->id} already has sizes or colors in pivot tables. Skipping.");
            }

            $this->command->info('Product updated successfully. '.$product->id);
        }

    });
    }
}
