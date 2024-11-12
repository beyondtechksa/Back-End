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
        // return $productsData->Product[0];
        $vendor=Vendor::where('name',$company_name)->first();
        foreach ($productsData->Product as $product) {
            if(!in_array($product->Product_id, $productIds)){
                $colors_ids = [];
                $color_name = [];
                $sizes_ids  = [];
                $size_name  = [];
                $images     = [];
                $attributes = [];
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
                    $images = json_encode(array_values($images));
                }
                if($product->variants){
                    $i = 0 ;
                    foreach($product->variants->variant as $variant_data){
                        if($variant_data->spec[1]){
                            $size_name['or'] = (string)$variant_data->spec[1];
                            $size_name['en'] = (string)$variant_data->spec[1];
                            $size_name['ar'] = (string)$variant_data->spec[1];
                            $size = Size::firstOrCreate(['name_tr' =>  $variant_data->spec[1]], [
                                'name' => $size_name,
                                'name_tr' =>  $variant_data->spec[1],
                            ]);
                           if( !in_array($size->id , $sizes_ids)){
                            $sizes_ids[] =  ['id'=>$size->id,'inStock'=>true];
                            $attributes['options'][] = (string)$variant_data->spec[1];

                           }
                        }
                        if($variant_data->spec[0]){
                            $color_name['or'] = (string)$variant_data->spec[0];
                            $color_name['en'] = (string)$variant_data->spec[0];
                            $color_name['ar'] = (string)$variant_data->spec[0];
                            $color = Color::firstOrCreate(['name_tr' =>  $variant_data->spec[0]], [
                                'name' => $color_name,
                                'name_tr' => $color_name['or'],
                                'color_code' => '#ffffff',
                            ]);
                            if( !in_array($color->id , $colors_ids)){
                                $colors_ids[] =  $color->id;
                               }
                        }
                        ++$i;

                    }
            }

            $finalPrice=(float)$product->Price3kdvli;
            $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
            $company_discount_price=$finalPrice * $company_discount_percentage / 100;

                $products[] = [
                    'product_id'    => (string)$product->Product_id,
                    'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                    'name'          => (string)$product->Name,
                    'desc'          => strip_tags((string)$product->Description),
                    'price'         => $finalPrice,
                    'discount_price'=> $company_discount_price,
                    'final_price'   => $finalPrice - $company_discount_price,
                    'discount_percentage' => $company_discount_percentage,
                    'link'          => "https://www.vaganza.com.tr/" . $this->formatName($product->Name),
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

