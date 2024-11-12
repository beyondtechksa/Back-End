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

class MixrayDriver implements CompanyDriverInterface
{
    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::MIXRAY;
        $xml = fetchDataFromUrl("https://www.mixray.com/TicimaxXml/5D3719A2668545819072B839B0B45560/");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $products = [];
        $productIds = TempProduct::where('type', 'company')
            ->where('company_name', $company_name)
            ->pluck('product_id')
            ->toArray();
        $vendor=Vendor::where('name',$company_name)->first();
        // return $productsData;
        foreach ($productsData->Urunler->Urun as $key=>$product) {
        // $product=$productsData->Urunler->Urun[0];
            if (!in_array((string)$product->UrunKartiID, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $attributes = [];
                // Convert prices to float for calculations
                    if($product->VARYASYON_GRUBU_XMLKOD_KONTROL_EDINIZ){
                        $size_name_tr=(string) $product->VARYASYON_GRUBU_XMLKOD_KONTROL_EDINIZ[1];
                        if($size_name_tr!=null){
                            $size_name['or'] = $size_name_tr;
                            $size = Size::firstOrCreate(['name_tr' => $size_name_tr], [
                                'name' => $size_name,
                                'name_tr' => $size_name_tr,
                            ]);
                            $sizes_ids[] =  ['id'=>$size->id,'inStock'=>true];
                        }

                        $color_name_tr=(string)$product->VARYASYON_GRUBU_XMLKOD_KONTROL_EDINIZ[0];
                        if($color_name_tr!=null){
                            $color_name['or'] = $color_name_tr;
                            $color = Color::firstOrCreate(['name_tr' => $color_name_tr], [
                                'name' => $color_name,
                                'name_tr' => $color_name_tr,
                                'color_code' => '#ffffff',
                            ]);
                            $colors_ids[] =  $color->id;
                        }
                    }


                $priceAfterDiscount = (float) $product->IndirimliFiyat;
                $finalPrice = $priceAfterDiscount;

                // Encode images and attributes as JSON strings
                $images = isset($product->Resimler->Resim) ? json_encode($product->Resimler->Resim) : json_encode([]);

                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;

                // Prepare product data
                $products[] = [
                    'product_id' => (string)$product->UrunKartiID,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name' => (string)$product->UrunAdi,
                    'desc' => strip_tags((string)$product->Aciklama),
                    'price' => $finalPrice,
                    'discount_price' => $company_discount_price,
                    'final_price' => $finalPrice - $company_discount_price,
                    'discount_percentage' => $company_discount_percentage,
                    'link' => (string)$product->UrunUrl,
                    'group_link' => '',
                    'images' => $images,
                    'attributes' => $attributes,
                    'type' => 'company',
                    'sizes_ids' =>  json_encode($sizes_ids),
                    'colors_ids' => json_encode($colors_ids),
                    'company_name' => $company_name,
                    'attributes' => json_encode($attributes),
                    // 'stock_status' => $stock_status,
                ];
            }
        }
        if($type=='scrap'){
            ScrapeProductsCompany::dispatch($products);
        }elseif($type=='track'){
            UpdateProductsCompany::dispatch($products,$company_name);
        }
        return $products;
    }
}
