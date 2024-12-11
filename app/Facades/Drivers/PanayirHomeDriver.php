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

class PanayirHomeDriver implements CompanyDriverInterface
{
    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::ZEYLAND;
        $xml = fetchDataFromUrl("https://www.panayirhome.com/export/1/53S4586M5");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $products = [];
        // return $productsData->Urunler->Urun[5];
        $productIds = TempProduct::where('type', 'company')
            ->where('company_name', $company_name)
            ->pluck('product_id')
            ->toArray();
        $vendor=Vendor::where('name',$company_name)->first();
        foreach ($productsData->product as $product) {
            if (!in_array((string)$product->UrunKartiID, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $attributes = [];
                $images = [];


                if (!empty($product->image1)) {
                    $images[] = (string)$product->image1;
                }
                if (!empty($product->image2)) {
                    $images[] = (string)$product->image2;
                }
                if (!empty($product->image3)) {
                    $images[] = (string)$product->image3;
                }
                if (!empty($product->image4)) {
                    $images[] = (string)$product->image4;
                }
                if (!empty($product->image5)) {
                    $images[] = (string)$product->image5;
                }
                $images = json_encode($images);
                

                $finalPrice = (float) $product->price;
                // Prepare product data
                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;

                $products[] = [
                    'product_id' => (string)$product->id,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name' => (string)$product->name,
                    'desc' => strip_tags((string)$product->detail),
                    'price' => $finalPrice,
                    'discount_price' => $company_discount_price,
                    'final_price' => $finalPrice - $company_discount_price,
                    'discount_percentage' => $company_discount_percentage,
                    'link' => '',
                    'group_link' => '',
                    'images' => $images,
                    'attributes' => $attributes,
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
