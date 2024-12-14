<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\File;
use App\Models\TrakingProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateProductsCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products;
    protected $company;

    /**
     * Create a new job instance.
     */
    public function __construct(array $products, string $company)
    {
        $this->products = $products;
        $this->company = $company;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $trackedCount = 0;
        // try{

        
        foreach ($this->products as $companyProduct) {
            $companyProductId = $companyProduct['product_id'] ?? null;

            if (!$companyProductId) {
                \Log::warning("Invalid product data received: " . json_encode($companyProduct));
                continue;
            }

            $product = Product::where('company_product_id', $companyProductId)
                ->whereDate('tracked_at', '!=', Carbon::today())
                ->first();

            if ($product) {
                \Log::info("Updating product: {$product->id}");

                $oldValues = $product->only(['price', 'discount_price', 'final_price', 'discount_percentage']);

                // Update product fields
                $product->update([
                    'name_tr' => $companyProduct['name'] ?? $product->name_tr,
                    'desc_tr' => $companyProduct['desc'] ?? $product->desc_tr,
                    'price' => $companyProduct['price'] ?? $product->price,
                    'discount_price' => $companyProduct['discount_price'] ?? $product->discount_price,
                    'final_price' => $companyProduct['final_price'] ?? $product->final_price,
                    'sale_price' => $companyProduct['final_price'] ?? $product->sale_price,
                    'discount_percentage' => $companyProduct['discount_percentage'] ?? $product->discount_percentage,
                    'tracked_at' => now(),
                ]);

                // Sync sizes and colors
                $sizes = json_decode($companyProduct['sizes_ids'] ?? '[]', true);
                $colors = json_decode($companyProduct['colors_ids'] ?? '[]', true);

                $product->sizes()->syncWithoutDetaching(
                    collect($sizes)->mapWithKeys(fn($size) => [$size['id'] => ['inStock' => $size['inStock']]])->toArray()
                );

                $product->colors()->syncWithoutDetaching($colors);

                // Sync files
                try {
                    $newFiles = json_decode($companyProduct['images'] ?? '[]', true) ?? [];
                    $existingFiles = $product->files()->pluck('image')->toArray();

                    $filesToAdd = array_diff($newFiles, $existingFiles);
                    $filesToRemove = array_diff($existingFiles, $newFiles);

                    foreach ($filesToAdd as $filePath) {
                        $product->files()->create(['image' => $filePath]);
                    }

                    File::whereIn('image', $filesToRemove)->where('product_id', $product->id)->delete();
                } catch (\Exception $e) {
                    \Log::error("Error syncing files for product {$product->id}: " . $e->getMessage());
                }

                // Track changes
                TrakingProduct::create([
                    'product_id' => $product->id,
                    'company_product_id' => $companyProduct['product_id'],
                    'sizes_ids' => json_decode($companyProduct['sizes_ids']),true,
                    'price' => $companyProduct['price'],
                    'discount_price' => $companyProduct['discount_price'],
                    'final_price' => $companyProduct['final_price'],
                    'discount_percentage' => $companyProduct['discount_percentage'],
                    'old_price' => $oldValues['price'],
                    'old_discount_price' => $oldValues['discount_price'],
                    'old_final_price' => $oldValues['final_price'],
                    'old_discount_percentage' => $oldValues['discount_percentage'],
                ]);

                $trackedCount++;
            }
        }
    // }catch(\Exception $e){
    //     \Log::error("Error tracking products  for company {$this->company}" );
    // }
        \Log::info("$trackedCount products tracked for company {$this->company}");
    }
}
