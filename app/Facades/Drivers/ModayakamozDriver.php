<?php
namespace App\Facades\Drivers;

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

class ModayakamozDriver implements CompanyDriverInterface
{
    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::MODAYAKAMOZ;
        // $type  = scrap or track
        $xml = fetchDataFromUrl("https://modayakamoz.com/xml/stockmount?b=arb");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        // return $productsData->Products->Product;
        $products = [];

        $productIds = TempProduct::where('type', 'company')
            ->where('company_name', $company_name)
            ->pluck('product_id')
            ->toArray();
            $vendor=Vendor::where('name',$company_name)->first();
        foreach ($productsData->Products->Product as $product) {
            if (!in_array((string)$product->ProductId, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $attributes = [];
                $images = [];
                // Convert prices to float for calculations
                $priceAfterDiscount = (float) $product->Price;
                $finalPrice = $priceAfterDiscount;

                if (!empty($product->Image1)) {
                    $images[] = (string)$product->Image1;
                }
                if (!empty($product->Image2)) {
                    $images[] = (string)$product->Image2;
                }
                if (!empty($product->Image3)) {
                    $images[] = (string)$product->Image3;
                }
                if (!empty($product->Image4)) {
                    $images[] = (string)$product->Image4;
                }
                if (!empty($product->Image5)) {
                    $images[] = (string)$product->Image5;
                }
                if (!empty($product->Image6)) {
                    $images[] = (string)$product->Image6;
                }
                if (!empty($product->Image7)) {
                    $images[] = (string)$product->Image7;
                }

                if (isset($images) && is_array($images)) {
                    $images = json_encode(array_values($images));
                }


                foreach($product->Variants->Variant as $secenek){
                    $size_name['or'] = (string)$secenek->VariantValue1;
                    $size = Size::firstOrCreate(['name_tr' =>  $secenek->VariantValue1], [
                        'name' => $size_name,
                        'name_tr' => $secenek->VariantValue1,
                    ]);
                    if($size){
                        $sizes_ids[] =  ['id'=>$size->id,'inStock'=>(int)$secenek->VariantQuantity > 0];
                    }

                    $color_name['or'] = (string)$secenek->VariantValue2;
                    $color = Color::firstOrCreate(['name_tr' => $secenek->VariantValue2], [
                        'name' => $color_name,
                        'name_tr' => $secenek->VariantValue2,
                        'color_code' => '#ffffff',
                    ]);
                    if( !in_array($color->id , $colors_ids)){
                        $colors_ids[] = $color->id;
                        }
                    // $attributes['options'][] = (string)$secenek->Size;

                }

                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;
                // Prepare product data
                $products[] = [
                    'product_id' => (string)$product->ProductCode,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name' => (string)$product->ProductDescription,
                    'desc' => (string)$product->Description,
                    'price' => $finalPrice,
                    'discount_price' => $company_discount_price,
                    'final_price' => $finalPrice - $company_discount_price,
                    'discount_percentage' => $company_discount_percentage,
                    'link' => (string)$product->Url,
                    'group_link' => '',
                    'images' => $images,
                    // 'attributes' => $attributes,
                    'type' => 'company',
                    'sizes_ids' =>  json_encode($sizes_ids),
                    'colors_ids' => json_encode(array_unique($colors_ids)),
                    'company_name' => $company_name,
                ];
            }
        }
        $products=array_unique($products, SORT_REGULAR);
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
