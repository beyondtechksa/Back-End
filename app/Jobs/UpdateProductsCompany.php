<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\File;
use Carbon\Carbon;

class UpdateProductsCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $products;
    public $company;
    /**
     * Create a new job instance.
     */
    public function __construct(array $products,string $company)
    {
      $this->products=$products;
      $this->company=$company;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $i=0;
        foreach($this->products as $company_product){
            $company_product_id=$company_product['product_id'];
            $product = Product::where('company_product_id',$company_product_id)->whereDate('tracked_at', '!=', Carbon::today())->first();
            // Add any additional fields you want to update or set on the new record
            if($product){
                \Log::info('product old name '.$product->name_tr);
                \Log::info('product old sizes '.$product->sizes());
                $i+=1;
                $product->update([
                    // 'company_name' => $this->company,
                    'name_tr'=>$company_product['name'],
                    'desc_tr'=>$company_product['desc'],
                    'price'=>$company_product['price'],
                    'discount_price'=>$company_product['discount_price'],
                    'discount_percentage'=>$company_product['discount_percentage'],
                    'final_price'=>$company_product['final_price'],
                    'sale_price'=>$company_product['final_price'],
                    'tracked_at'=>now()
                ]);

                $sizes = json_decode($company_product['sizes_ids'],true);
                $colors = json_decode($company_product['colors_ids'],true);

                $sizesData = array_map(function ($size) {
                    return ['inStock' => $size['inStock']];
                }, $sizes);
                
                $sizesToSync = [];
                foreach ($sizes as $size) {
                    $sizesToSync[$size['id']] = ['inStock' => $size['inStock']];
                }
                $product->sizes()->syncWithoutDetaching($sizesToSync);

                $product->colors()->syncWithoutDetaching($colors);

                \Log::info('product new name '.$product->name_tr);
                \Log::info('product new sizes '.$product->sizes);
                // files
                try{
                    $existingFiles = $product->files()->pluck('image')->toArray();
                    $newFiles = $product['images'] ?? [];
                    $filesToAdd = array_diff($newFiles, $existingFiles);
                    $filesToRemove = array_diff($existingFiles, $newFiles);
                    foreach ($filesToAdd as $filePath) {
                        $product->files()->create(['image' => $filePath]);
                        \Log::info('file '.$filePath);
                    }
                    // Remove old files
                    File::whereIn('image', $filesToRemove)->where('product_id', $product->id)->delete();
                    $product->save();
                    \Log::info('product tracked at '.$product->tracked_at);
                }catch(\Exception $e){

                }

            }
        }

        \Log::info($i .' products tracked for '.$this->company);
    }
}
