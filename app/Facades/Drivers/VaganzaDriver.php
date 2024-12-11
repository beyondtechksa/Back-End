<?php
namespace App\Facades\Drivers;
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

class VaganzaDriver implements CompanyDriverInterface
{

    public function extract($category,$type='scrap')
    {
        $company_name=CompanyEnums::VAGANZA;
        $xml = fetchDataFromUrl("https://www.vaganza.com.tr/feeds/product/feed.php?key=b917f67365470b42e996e3dd3710fc09");
        $productsData = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $products = $images = $attributes= [];
        $productIds = TempProduct::where('type' , 'company')->where('company_name' , $company_name)->pluck('product_id')->toArray();
        return $productsData;
        $vendor=Vendor::where('name',$company_name)->first();
        foreach ($productsData->Urun as $product) {
            Log::info($product->Name);
            if(!in_array($product->Product_id, $productIds)){
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $images     = [];
                $attributes = [];

                if($product->Bedenler){
                    $i = 0 ;
                    foreach($product->Bedenler->Beden as $variant_data){
                            $size_name_tr=(string)$variant_data->Baslik;
                            $size_name['or'] = $size_name_tr;
                            $size = Size::firstOrCreate(['name_tr' =>  $size_name_tr], [
                                'name' => $size_name,
                                'name_tr' =>  $size_name_tr,
                            ]);
                           if( !in_array($size->id , $sizes_ids)){
                            $sizes_ids[] =  ['id'=>$size->id,'inStock'=>true];
                           }
                        ++$i;

                    }
            }
            $color_name_tr=(string)$product->UrunRenk;
            $color_name['or'] = $color_name_tr;
            $color = Color::firstOrCreate(['name_tr' =>  $color_name_tr], [
                'name' => $color_name,
                'name_tr' =>  $color_name_tr,
                'color_code'=>'#ffffff'
            ]);
            if( !in_array($color->id , $colors_ids)){
            $colors_ids[] = $color->id;
            }


            $finalPrice=(float)$product->IndirimliFiyat;
            $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
            $company_discount_price=$finalPrice * $company_discount_percentage / 100;
            $images = isset($product->Fotograflar) ? json_encode($product->Fotograflar) : json_encode([]);
                $products[] = [
                    'product_id'    => (string)$product->UrunKodu,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name'          => (string)$product->DetayliUrunAdi,
                    'desc'          => strip_tags((string)$product->Aciklama),
                    'price'         => $finalPrice,
                    'discount_price'=> $company_discount_price,
                    'final_price'   => $finalPrice - $company_discount_price,
                    'discount_percentage' => $company_discount_percentage,
                    'link'          => "https://www.vaganza.com.tr/" . $product->SeoLink,
                    'group_link'    => '',
                    'images'        => $images,
                    'type'          =>  'company',
                    'company_name'  => $company_name,
                    'sizes_ids'     =>  json_encode($sizes_ids),
                    'colors_ids'    => json_encode($colors_ids),
                    'attributes'    => json_encode($attributes),

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

    private function formatName($name)
    {
        // Step 1: Remove any dashes
        $step1 = str_replace('-', ' ', $name);

        // Step 2: Replace spaces with hyphens
        $step2 = Str::slug($step1, '-');

        return $step2;
    }
}

