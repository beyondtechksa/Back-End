<?php
namespace App\Facades\Drivers\Test;
use App\Models\Color;
use App\Models\TempProduct;
use App\Models\Vendor;
use Illuminate\Support\Str;
use App\Http\Enums\CompanyEnums;
use App\Jobs\ScrapeProductsCompany;
use App\Jobs\UpdateProductsCompany;
use Illuminate\Support\Facades\Log;
use App\Facades\Contracts\CompanyDriverInterface;
use App\Models\Size;
use Illuminate\Support\Facades\Auth;

class ZepkidsDriver implements CompanyDriverInterface
{

    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::ZEPKIDS;
        $xml = fetchDataFromUrl("http://www.zepkids.com/TicimaxXmlV2/72CFF1DD7C6847FB9B925A2BB6B79F9A/");
        $productsData = simplexml_load_string($xml , "SimpleXMLElement", LIBXML_NOCDATA);
        $productIds = TempProduct::where('type' , 'company')->where('company_name' , $company_name)->pluck('product_id')->toArray();
        $products=[];
        $vendor=Vendor::where('name',$company_name)->first();
        return $productsData->Urunler->Urun[0];
        foreach ($productsData->Urunler->Urun as $product) {
        // $product=$productsData->Urunler->Urun[5];
            if(!in_array($product->Product_id, $productIds)){
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $images     = [];
                $attributes = [];
                // Convert prices to float for calculations
                $priceBeforeDiscount = (float) $product->UrunSecenek->Secenek->ÜyeTipiFiyatı19;
                $priceAfterDiscount = (float) $product->UrunSecenek->Secenek->ÜyeTipiFiyatı19;

                // Initialize variables
                $discountAmount = 0;
                $discountPercentage = 0;
                $finalPrice = $priceAfterDiscount;

                foreach($product->UrunSecenek->Secenek as $secenek){

                    if($secenek->EkSecenekOzellik){
    
                        for($i = 0 ;$i < count($secenek->EkSecenekOzellik->Ozellik)  ; $i++){
                            if($i ==  count($secenek->EkSecenekOzellik->Ozellik) - 1){
                                $size_name['or'] = (string)$secenek->EkSecenekOzellik->Ozellik[$i];
                                $size = Size::firstOrCreate(['name_tr' =>  $secenek->EkSecenekOzellik->Ozellik[$i]], [
                                    'name' => $size_name,
                                    'name_tr' => $secenek->EkSecenekOzellik->Ozellik[$i],
                                ]);
                                $sizes_ids[] =  ['id'=>$size->id,'inStock'=>(int)$secenek->StokAdedi>0];
                                $attributes['options'][] = (string)$secenek->EkSecenekOzellik->Ozellik[$i];
                            }else{
                                $color_name['or'] = (string)$secenek->EkSecenekOzellik->Ozellik[$i];
                                $color = Color::firstOrCreate(['name_tr' => $secenek->EkSecenekOzellik->Ozellik[$i]], [
                                    'name' => $color_name,
                                    'name_tr' => $secenek->EkSecenekOzellik->Ozellik[$i],
                                    'color_code' => '#ffffff',
                                ]);
                                $colors_ids[] =  $color->id;
                            }
    
                        }
                    }
                    // Check if VAT is included
                  
                    $priceAfterDiscount = (float) $product->UrunSecenek->Secenek->ÜyeTipiFiyatı19;
                    $finalPrice = $priceAfterDiscount;
                 
                }

                // Encode images and attributes as JSON strings
                $images = isset($product->Resimler->Resim) ? json_encode($product->Resimler->Resim) : json_encode([]);
                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;
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
