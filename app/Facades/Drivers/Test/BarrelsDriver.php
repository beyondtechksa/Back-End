<?php
namespace App\Facades\Drivers\Test;

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

class BarrelsDriver implements CompanyDriverInterface
{
    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::BARRELS;
        $xml = fetchDataFromUrl("https://www.barrelsandoil.com/TicimaxXmlV2/C9A7013EFA1041DB8FFE3B49C71AEC8D/");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $products = [];
        $productIds = TempProduct::where('type', 'company')
            ->where('company_name', $company_name)
            ->pluck('product_id')
            ->toArray();
        $vendor=Vendor::where('name',$company_name)->first();
        return $productsData->Urunler->Urun[0];
        foreach ($productsData->Urunler->Urun as $key=>$product) {
        // $product=$productsData->Urunler->Urun[0];
            if (!in_array((string)$product->UrunKartiID, $productIds)) {
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $attributes = [];
                // Convert prices to float for calculations
                
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
                  
                    $priceAfterDiscount = (float) $product->UrunSecenek->Secenek->IndirimliFiyat;
                    $finalPrice = $priceAfterDiscount;
                 
                }
                // $stock_status=$product->Stoklar->Stok->Miktar>0?'in stock':'out of stock';
        

                // Encode images and attributes as JSON strings
                $images = isset($product->Resimler->Resim) ? json_encode($product->Resimler->Resim) : json_encode([]);

                $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                $company_discount_price=$finalPrice * $company_discount_percentage / 100;

                // Prepare product data
                $products[] = [
                    'product_id' => (string)$product->UrunKartiID,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name' => (string)$product->Baslik,
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
