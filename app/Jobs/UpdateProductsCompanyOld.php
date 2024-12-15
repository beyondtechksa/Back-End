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
use Illuminate\Support\Facades\Log;

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

    // Cache product ids for faster access
    $companyProductIds = collect($this->products)->pluck('product_id')->filter();

    // Fetch all products for the given company in a single query
    $products = Product::whereIn('company_product_id', $companyProductIds)
        ->whereDate('tracked_at', '!=', Carbon::today())
        ->get()
        ->keyBy('company_product_id'); // Key products by their company_product_id

    // Loop through the company products
    foreach ($this->products as $companyProduct) {
        $companyProductId = $companyProduct['product_id'] ?? null;

        if (!$companyProductId || !isset($products[$companyProductId])) {
            Log::warning("Invalid or missing product data received: " . json_encode($companyProduct));
            continue;
        }

        $product = $products[$companyProductId];

        // Log the start of the product update
        Log::info("Updating product: {$product->id}");

        $oldValues = $product->only(['price', 'discount_price', 'final_price', 'discount_percentage','final_selling_price']);
        Log::info('old price '.$product->final_selling_price);

        // Update product fields (Only if the new value differs)
        $product->fill([
            'name_tr' => $companyProduct['name'] ?? $product->name_tr,
            'desc_tr' => $companyProduct['desc'] ?? $product->desc_tr,
            'price' => $companyProduct['price'] ?? $product->price,
            'discount_price' => $companyProduct['discount_price'] ?? $product->discount_price,
            'final_price' => $companyProduct['final_price'] ?? $product->final_price,
            'sale_price' => $companyProduct['final_price'] ?? $product->sale_price,
            'final_selling_price'=> update_final_price($product, $companyProduct['final_price']),
            'discount_percentage' => $companyProduct['discount_percentage'] ?? $product->discount_percentage,
            'tracked_at' => now(),
        ]);

        // Only save if changes occurred
        if ($product->isDirty()) {
            $product->save();
            Log::info('new price '.$product->final_selling_price);
        }

        // Sync sizes and colors (Use chunking or batch operations for larger datasets)
        $sizes = json_decode($companyProduct['sizes_ids'] ?? '[]', true);
        $colors = json_decode($companyProduct['colors_ids'] ?? '[]', true);

        $product->sizes()->syncWithoutDetaching(
            collect($sizes)->mapWithKeys(fn($size) => [$size['id'] => ['inStock' => $size['inStock']]])->toArray()
        );

        $product->colors()->syncWithoutDetaching($colors);

        // Bulk file handling: collect all the new file paths and handle them in batch
        $newFiles = json_decode($companyProduct['images'] ?? '[]', true) ?? [];
        $existingFiles = $product->files()->pluck('image')->toArray();
        Log::info('new files'.json_encode($newFiles));
        Log::info('existing files'.json_encode($existingFiles));

        // Assuming product has a primary image column, e.g. 'image'
        $productImage = $product->image;  // Adjust if the field name is different.

        // Calculate files to add, excluding the product's own image
        $filesToAdd = array_diff($newFiles, $existingFiles);

        // Remove the product's own image from filesToAdd (if it exists in newFiles)
        $filesToAdd = array_diff($filesToAdd, [$productImage]);

        // Calculate files to remove
        $filesToRemove = array_diff($existingFiles, $newFiles);

        Log::info('files to add'.json_encode($filesToAdd));
        Log::info('files to remove'.json_encode($filesToRemove));

        // Bulk insert new files
        $filesToAddData = array_map(fn($filePath) => ['image' => $filePath, 'product_id' => $product->id], $filesToAdd);
        if (count($filesToAddData) > 0) {
            File::insert($filesToAddData);
        }

        // Bulk delete removed files
        File::whereIn('image', $filesToRemove)->where('product_id', $product->id)->delete();

        Log::info('after updating files'.json_encode($product->files()->pluck('image')->toArray()));

        // Track changes
        TrakingProduct::create([
            'product_id' => $product->id,
            'company_product_id' => $companyProduct['product_id'],
            'sizes_ids' => json_decode($companyProduct['sizes_ids'],true),
            'price' => $companyProduct['price'],
            'discount_price' => $companyProduct['discount_price'],
            'final_price' => $companyProduct['final_price'],
            'discount_percentage' => $companyProduct['discount_percentage'],
            'final_selling_price' => $product->final_selling_price,
            'old_price' => $oldValues['price'],
            'old_discount_price' => $oldValues['discount_price'],
            'old_final_price' => $oldValues['final_price'],
            'old_discount_percentage' => $oldValues['discount_percentage'],
            'old_final_selling_price'=>$oldValues['final_selling_price'],
            'images' => json_decode($companyProduct['images'] ?? '[]', true) ?? []
        ]);

        $trackedCount++;
    }

    Log::info("$trackedCount products tracked for company {$this->company}");
}
}