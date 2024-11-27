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

class PazariumDriver implements CompanyDriverInterface
{
    protected $limit = 2500; // Fetch 1000 products at a time

    public function extract($category,$type='scrap')
    {
        $start=0;
        $company_name=CompanyEnums::PAZARIUM;
        $vendor=Vendor::where('name',$company_name)->first();
        $productIds = TempProduct::where('type' , 'company')->where('company_name' , $company_name)->pluck('product_id')->toArray();
        $products=[];
        while (true) {
        $url = "https://www.pazarium.com.tr/xml/?R=1310703&K=152b&AltUrun=1&TamLink=1&Dislink=1&Seo=1&Stok=1&Imgs=1&start={$start}&limit={$this->limit}&pass=QUB1y0fJ";
        $xml = fetchDataFromUrl($url);
        $productsData = simplexml_load_string($xml , "SimpleXMLElement", LIBXML_NOCDATA);
        if (empty($productsData->product) || $start>=10000) {
            break;
        }
        foreach ($productsData->product as $product) {
            // $product=$productsData->Urunler->Urun[5];
                if(!in_array($product->code, $productIds)){
                    $colors_ids = [];
                    $color_name = [];
                    $sizes_ids  = [];
                    $size_name  = [];
                    $images     = [];
                    $attributes = [];

                    foreach($product->subproducts->subproduct as $subproduct){

                            $size_name['or'] = (string)$subproduct->type2;
                            $size = Size::firstOrCreate(['name_tr' =>  $subproduct->type2], [
                                'name' => $size_name,
                                'name_tr' => $subproduct->type2,
                            ]);
                            $sizes_ids[] =  ['id'=>$size->id,'inStock'=>(int)$subproduct->stock];

                            $type1=$subproduct->type1;
                            $color_name['or'] = $type1;
                            if (!empty($type1) && is_array($type1) && count($type1) > 0) {
                                $color = Color::firstOrCreate(['name_tr' => $type1], [
                                    'name' => $color_name,
                                    'name_tr' => $type1,
                                    'color_code' => '#ffffff',
                                ]);
                                (int)$subproduct->stock > 0 ? $colors_ids[] =  $color->id : null;
                            }

                    }

                    // Encode images and attributes as JSON strings
                    $finalPrice=(float)$product->price_special_vat_included;
                    $images = isset($product->images->img_item) ? json_encode($product->images->img_item) : json_encode([]);
                    $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                    $company_discount_price=$finalPrice * $company_discount_percentage / 100;
                    $products[] = [
                        'product_id' => (string)$product->code,
                        'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                        'name' => (string)$product->name,
                        'desc' => strip_tags((string)$product->detail),
                        'price' => $finalPrice,
                        'discount_price' => $company_discount_price,
                        'final_price' => $finalPrice - $company_discount_price,
                        'discount_percentage' => $company_discount_percentage,
                        'link' => (string) $product->product_link,
                        'group_link' => '',
                        'images' => $images,
                        'type' => 'company',
                        'sizes_ids' =>  json_encode($sizes_ids),
                        'colors_ids' => json_encode($colors_ids),
                        'company_name' => $company_name,
                        'attributes' => json_encode($attributes),

                    ];


                }
            }

        $start += $this->limit;
    }
    $products=array_unique($products, SORT_REGULAR);
        // Log::info($products[1]);
        if($type=='scrap'){
            ScrapeProductsCompany::dispatch($products);
        }elseif($type=='track'){
            UpdateProductsCompany::dispatch($products,$company_name);
        }
        return $products;
    }
}
