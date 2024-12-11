<?php
namespace App\Facades\Drivers;

use App\Models\Size;
use App\Models\Color;
use App\Models\TempProduct;
use App\Models\Vendor;
use App\Http\Enums\CompanyEnums;
use App\Jobs\ScrapeProductsCompany;
use App\Jobs\UpdateProductsCompany;
use Illuminate\Support\Facades\Log;
use App\Facades\Contracts\CompanyDriverInterface;
use Illuminate\Support\Facades\Auth;

class VavinorDriver implements CompanyDriverInterface
{
    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::VAVINOR;
        $xml = fetchDataFromUrl("https://zariftesettur.com/instock/XmlApplai/xml");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $products = [];
        return $productsData;
        $productIds = TempProduct::where('type', 'company')
            ->where('company_name', $company_name)
            ->pluck('product_id')
            ->toArray();
        $vendor=Vendor::where('name',$company_name)->first();
        foreach ($productsData->Product as $product) {
            if (!in_array((string)$product->UrunKartiID, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $attributes = [];
                $images = [];



                foreach($product->variants->variant as $variant){

                        $size_name_tr=(string) $variant->spec;
                                if($size_name_tr!=null){
                                    $size_name['or'] = $size_name_tr;
                                    $size = Size::firstOrCreate(['name_tr' => $size_name_tr], [
                                        'name' => $size_name,
                                        'name_tr' => $size_name_tr,
                                    ]);
                                    $sizes_ids[] =  ['id'=>$size->id,'inStock'=> (int) $variant->quantity > 0];
                                }
      
                }

                $priceBeforeDiscount = (float) $product->Price_includes_VAT;
                $priceAfterDiscount = $priceBeforeDiscount;
                $finalPrice = $priceAfterDiscount?$priceAfterDiscount:$priceBeforeDiscount;

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

                if (isset($images) && is_array($images)) {
                    $images = json_encode($images);
                }
                


                // Prepare product data
                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;

                $products[] = [
                    'product_id' => (string)$product->Product_id,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name' => (string)$product->Name,
                    'desc' => strip_tags((string)$product->Description),
                    'price' => $finalPrice,
                    'discount_price' => $company_discount_price,
                    'final_price' => $finalPrice - $company_discount_price,
                    'discount_percentage' => $company_discount_percentage,
                    'link' => (string)$product->UrunUrl,
                    'group_link' => '',
                    'images' => $images,
                    // 'attributes' => $attributes,
                    'type' => 'company',
                    'sizes_ids' =>  json_encode($sizes_ids),
                    'colors_ids' => json_encode($colors_ids),
                    'company_name' => $company_name,
                    'attributes' => json_encode($attributes),
                ];
            }
        }
        $products=array_unique($products, SORT_REGULAR);
        if($type=='scrap'){
            ScrapeProductsCompany::dispatch($products);
        }elseif($type=='track'){
            UpdateProductsCompany::dispatch($products,$company_name);
        }
        return $products;
    }
}
