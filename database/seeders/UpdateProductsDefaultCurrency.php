<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Carbon\Carbon;

class UpdateProductsDefaultCurrency extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::whereDate('tracked_at',Carbon::today())->chunk(1000,function($products){
            foreach($products as $product){
                $product->update([
                    'tracked_at'=>null
                ]);
                $this->command->info('Product updated successfully. '.$product->id.' '.$product->currency_id);
            }
            
        });
    }
}
