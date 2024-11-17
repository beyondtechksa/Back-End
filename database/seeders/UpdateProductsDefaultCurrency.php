<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class UpdateProductsDefaultCurrency extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::where('currency_id',null)->chunk(1000,function($products){
            foreach($products as $product){
                $product->update([
                    'currency_id'=>3
                ]);
                $this->command->info('Product updated successfully. '.$product->id.' '.$product->currency_id);
            }
            
        });
    }
}
