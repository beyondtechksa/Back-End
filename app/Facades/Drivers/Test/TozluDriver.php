<?php
namespace App\Facades\Drivers\Test;

use App\Models\Size;
use App\Models\Color;
use App\Models\TempProduct;
use App\Models\Vendor;
use App\Models\Product;
use App\Http\Enums\CompanyEnums;
use App\Jobs\ScrapeProductsCompany;
use App\Jobs\UpdateProductsCompany;
use Illuminate\Support\Facades\Log;
use App\Facades\Contracts\CompanyDriverInterface;
use Illuminate\Support\Facades\Auth;

class TozluDriver implements CompanyDriverInterface
{
    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::TOZLU;
        // $type  = scrap or track
        $xml = fetchDataFromUrl("http://xml.tozlu.com/api/Product/tozluxml?password=aLtciTyx5");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        // return $productsData->ProductXml[0];
        $products = [];

        $productIds = TempProduct::where('type', 'company')
            ->where('company_name', $company_name)
            ->pluck('product_id')
            ->toArray();
            $vendor=Vendor::where('name',$company_name)->first();
            $product=$productsData->ProductXml[0];
            return $product;
        foreach ($productsData->ProductXml as $product) {
            if (!in_array((string)$product->ProductId, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $attributes = [];
                // Convert prices to float for calculations
                $priceBeforeDiscount = (float) $product->CrossedPrice;
                $priceAfterDiscount = (float) $product->Price;

                // Initialize variables
                $discountAmount = 0;
                $discountPercentage = 0;
                $finalPrice = $priceAfterDiscount;

                foreach($product->productVariantsList->ProductVariant as $secenek){
                    $size_name['or'] = (string)$secenek->Size;
                    $size = Size::firstOrCreate(['name_tr' =>  $secenek->Size], [
                        'name' => $size_name,
                        'name_tr' => $secenek->Size,
                    ]);
                    if($size){
                        $sizes_ids[] =  ['id'=>$size->id,'inStock'=>(int)$secenek->Stock > 0];
                    }

                    $color_name['or'] = (string)$secenek->Color;
                    $color = Color::firstOrCreate(['name_tr' => $secenek->Color], [
                        'name' => $color_name,
                        'name_tr' => $secenek->Color,
                        'color_code' => '#ffffff',
                    ]);
                    (int)$secenek->Stock > 0 ? $colors_ids[] =  $color->id:null;
                    // $attributes['options'][] = (string)$secenek->Size;

                }

                $vatRate = (float) $product->VatRate / 100;
                // Check if VAT is included
             

                // Encode images and attributes as JSON strings
                $images=[];
                if(isset($product->ImageUrl->ProductImage)){
                    foreach($product->ImageUrl->ProductImage as $imageObject){
                        $images[]=$imageObject->imagePath[0][0];
                    }
                }

                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;
                // Prepare product data
                $products[] = [
                    'product_id' => (string)$product->ProductId,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name' => (string)$product->ProductDescription,
                    'desc' => strip_tags((string)$product->ProductFeature),
                    'price' => $finalPrice,
                    'discount_price' => $company_discount_price,
                    'final_price' => $finalPrice - $company_discount_price,
                    'discount_percentage' => $company_discount_percentage,
                    'link' => (string)$product->ProductUrl,
                    'group_link' => '',
                    'images' => json_encode($images[0]),
                    // 'attributes' => $attributes,
                    'type' => 'company',
                    'sizes_ids' =>  json_encode($sizes_ids),
                    'colors_ids' => json_encode($colors_ids),
                    'company_name' => $company_name,
                ];
            }
        }
        if($type=='scrap'){
            Log::info('scrap');
            ScrapeProductsCompany::dispatch($products);
        }elseif($type=='track'){
            Log::info('track');
            UpdateProductsCompany::dispatch($products,$company_name);
        }
        return $products;
    }
}
