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

class DenokidsDriver implements CompanyDriverInterface
{
    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::DENOKIDS;
        // $type  = scrap or track
        $xml = fetchDataFromUrl("http://b2b.ayensoftware.com/xml/reply/IdeasoftVaryantV4XmlRequest?MusteriId=5168&DukkanId=19&MusteriTedarikciId=42");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        // return $productsData;
        $products = [];

        $productIds = TempProduct::where('type', 'company')
            ->where('company_name', $company_name)
            ->pluck('product_id')
            ->toArray();
            $vendor=Vendor::where('name',$company_name)->first();
        foreach ($productsData->item as $product) {
        // $product=$productsData->ProductXml[0];
            if (!in_array((string)$product->ProductId, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $attributes = [];
                $images = [];
                // Convert prices to float for calculations
                $priceAfterDiscount = (float) $product->price1;
                $finalPrice = $priceAfterDiscount;

                if (!empty($product->picture1Path)) {
                    $images[] = (string)$product->picture1Path;
                }
                if (!empty($product->picture2Path)) {
                    $images[] = (string)$product->picture2Path;
                }
                if (!empty($product->picture3Path)) {
                    $images[] = (string)$product->picture3Path;
                }
                if (!empty($product->picture4Path)) {
                    $images[] = (string)$product->picture4Path;
                }
                if (!empty($product->picture5Path)) {
                    $images[] = (string)$product->picture5Path;
                }

                if (isset($images) && is_array($images)) {
                    $images = json_encode($images);
                }

                foreach($product->variants->variant as $secenek){
                    $size_name_tr= (string)$secenek->options->option[1]->variantValue;
                    $size_name['or'] =$size_name;
                    $size = Size::firstOrCreate(['name_tr' =>  $size_name_tr], [
                        'name' => $size_name,
                        'name_tr' => $size_name_tr,
                    ]);
                    if($size){
                        $sizes_ids[] =  ['id'=>$size->id,'inStock'=>(int)$secenek->vStockAmount > 0];
                    }
                    $color_name_tr= (string)$secenek->options->option[0]->variantValue;
                    $color_name['or'] = $color_name_tr;
                    $color = Color::firstOrCreate(['name_tr' =>$color_name_tr], [
                        'name' => $color_name,
                        'name_tr' => $color_name_tr,
                        'color_code' => '#ffffff',
                    ]);
                    $colors_ids[] =  $color->id;

                }

                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;
                // Prepare product data
                $products[] = [
                    'product_id' => (string)$product->variants->variant[0]->vBarcode,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name' => (string)$product->label,
                    'desc' => (string)$product->details,
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
