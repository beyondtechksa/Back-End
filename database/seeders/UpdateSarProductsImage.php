<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateSarProductsImage extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products =  Product::where('currency_id',2)->get();

        foreach ($products as $product){
            $product->update([
                'image'=>url($product->image)
            ]);

            foreach ($product->files() as $file){
                $file->update([
                    'image'=>url($file->image)
                ]);
            }
        }
    }
}
