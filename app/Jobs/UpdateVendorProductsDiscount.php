<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class UpdateVendorProductsDiscount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $productsBatch;
    protected $discount_percentage;

    /**
     * Create a new job instance.
     */
    public function __construct(array $productsBatch,$discount_percentage)
    {
        $this->productsBatch = $productsBatch;
        $this->discount_percentage = $discount_percentage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->productsBatch as $productData) {
            $product = Product::where('id', $productData['id'])->first();

            if ($product) {
                $discount_price=$product->price * $this->discount_percentage/100;
                $sale_price = $product->price - $discount_price;
                $product->update([
                    'discount_percentage'=>$this->discount_percentage,
                    'discount_price'=>$discount_price,
                    'sale_price'=>$sale_price,
                    'final_price'=>$sale_price,
                ]);

                Log::info("Product ID {$product->id} updated.");
            } else {
                Log::warning("Product ID {$productData['id']} not found.");
            }
        }

    }
}
