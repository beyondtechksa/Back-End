<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PriceFormula;
use App\Models\Product;
use App\Models\File;
use App\Models\Attribute;
use App\Models\Size;
use App\Models\ProductAttribute;
use App\Models\TempProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class StoreProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $request;
    protected $product;
    protected $admin_id;
    /**
     * Create a new job instance.
     */
    public function __construct($request,TempProduct $product,$admin_id)
    {
        $this->request=$request;
        $this->product=$product;
        $this->admin_id=$admin_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Processing job for product: ' . $this->product->id);

        $request=$this->request;
        $product=$this->product;
        $formula=PriceFormula::find($request['formula_id']);
        $name=$product->name;


        $desc=$product->desc;
        // Log::info($product->sizes_ids);
        // Log::info($product->colors_ids);
        if($formula){
            $createdProduct=Product::firstOrCreate([
                'company_product_id'=>$product->product_id,
                'company_name'=>$product->company_name,
            ],[
                'admin_id'=>$this->admin_id,
                'vendor_id'=>$product->vendor_id,
                'name_tr'=>$name,
                'desc_tr'=>$desc,
                'slug'=>Str::slug($name),
                'price'=>$product->price,
                'discount_price'=>$product->discount_price,
                'discount_percentage'=>$product->discount_percentage,
                'sale_price'=>$product->final_price,
                'final_price'=>$product->final_price,
                'image'=>count($product->images)>0?$product->images[0]:null,
                'source_link'=>$product->link,
                'sku'=>$product->sku,
                'category_id'=>$request['category_id'],
                'brand_id'=>$request['brand_id'],
                'group_link'=>$product->group_link,
                'status'=>0,
                'stock_status'=>$product->stock_status,
                'tax_percentage'=>$formula->discount_percentage_selling_price,
                'packaging_shipping_fees'=>$formula->packaging_shipping_fees,
                'management_fees'=>$formula->management_fees,
                'profit_percentage'=>$formula->profit_percentage,
                'commercial_percentage'=>$formula->commercial_percentage,
                'manual_price_adjustment'=>$formula->manual_price_adjustment,
                'final_selling_price'=>  calc_final_price($product->final_price,$formula),
            ]);


        }else{

        }
        if(count($product->images)>0){
            if($createdProduct->files()->count()==0){
                foreach ($product->images as  $key=>$image) {
                    if($key>0){
                        File::create([
                            'product_id' => $createdProduct->id,
                            'image' => $image
                        ]);
                    }
                }
            }
        }
        $colors_ids=$product->colors_ids;
        $sizes_ids=$product->sizes_ids;
        $createdProduct->colors()->syncWithoutDetaching($colors_ids);
        foreach ($sizes_ids as $size) {
            $syncData[$size['id']] = [
                'inStock' => (int) $size['inStock'] > 0
            ];
        }
        $createdProduct->sizes()->sync($syncData);

        $tempProduct=TempProduct::find($product->id);
        if($tempProduct){
            $tempProduct->delete();
        }
        Log::info('Finished processing job for product: ' . $this->product->id);
    }
}
