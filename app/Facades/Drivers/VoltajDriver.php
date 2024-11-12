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

class VoltajDriver implements CompanyDriverInterface
{
    protected $limit = 2500; // Fetch 1000 products at a time

    public function extract($category,$type='scrap')
    {
        $start=0;
        $company_name=CompanyEnums::VOLTAJ;
        $vendor=Vendor::where('name',$company_name)->first();
        $productIds = TempProduct::where('type' , 'company')->where('company_name' , $company_name)->pluck('product_id')->toArray();
        $products=[];
        while (true) {
        $url = "https://www.voltaj.com.tr/xml/?R=464&K=7d03&AltUrun=1&TamLink=1&Dislink=1&Seo=1&Imgs=1&start={$start}&limit={$this->limit}&pass=4FF11J3W";
        $xml = fetchDataFromUrl($url);
        $productsData = simplexml_load_string($xml , "SimpleXMLElement", LIBXML_NOCDATA);
        if (empty($productsData->product) || $start>=10000) {
            break;
        }
        // return $productsData->product[0];
        foreach ($productsData->product as $product) {
            // $product=$productsData->Urunler->Urun[5];
                if(!in_array($product->code, $productIds)){
                    $colors_ids = [];
                    $color_name = [];
                    $sizes_ids  = [];
                    $size_name  = [];
                    $images     = [];
                    $attributes = [];

                    $groupedByColor = [];

                    // Group by type1 and structure the data
                    foreach ($product->subproducts->subproduct as $data) {
                        // Ensure $data->type1 is accessed properly
                        $type1 = (string)$data->type1; // Cast to string to avoid type issues

                        // Debugging statement to see the value of $type1
                        if (empty($type1)) {
                            // Handle or log the empty type1 case
                            continue; // Skip this iteration if type1 is empty
                        }

                        // Check if the type1 key already exists in the array
                        if (!isset($groupedByColor[$type1])) {
                            $groupedByColor[$type1] = [
                                'type1' => $type1,
                                'data' => []
                            ];
                        }

                        // Append the data to the corresponding type1
                        $groupedByColor[$type1]['data'][] = $data;
                    }

                    // Re-index the array for a clean output
                    $groupedByColor = array_values($groupedByColor);

                    foreach($groupedByColor as $group){
                        $colors_ids = [];
                        $sizes_ids  = [];
                        $images     = [];

                            foreach($group['data'] as $subproduct){
                                $size_name['or'] = (string)$subproduct->type2;
                                    $size = Size::firstOrCreate(['name_tr' =>  $subproduct->type2], [
                                        'name' => $size_name,
                                        'name_tr' => $subproduct->type2,
                                    ]);
                                    $sizes_ids[] =  ['id'=>$size->id,'inStock'=>(int)$subproduct->stock];

                                }

                                $type1=$group['type1'];
                                    $color_name['or'] = $type1;

                                        $color = Color::firstOrCreate(['name_tr' => $type1], [
                                            'name' => $color_name,
                                            'name_tr' => $type1,
                                            'color_code' => '#ffffff',
                                        ]);
                                (int)$subproduct->stock > 0 ? $colors_ids[] =  $color->id : null;

                                if(isset($group['data'][0]->images->img_item)){
                                    foreach($group['data'][0]->images->img_item as $img_item){
                                        $images[]=(string) $img_item;
                                    }
                                }

                    $product_id=$group['data'][0]->code;

                    // Encode images and attributes as JSON strings
                    $finalPrice=(float)$product->price_special_vat_included;
                    $company_discount_percentage=$vendor?$vendor->discount_percentage:0;
                    $company_discount_price=$finalPrice * $company_discount_percentage / 100;
                    $products[] = [
                        'product_id' => (string)$product_id,
                        'admin_id' => Auth::guard('admin')->check()?admin()->id:null,
                        'name' => (string)$product->name,
                        'desc' => strip_tags((string)$product->detail),
                        'price' => $finalPrice,
                        'discount_price' => $company_discount_price,
                        'final_price' => $finalPrice - $company_discount_price,
                        'discount_percentage' => $company_discount_percentage,
                        'link' => (string) $product->product_link,
                        'group_link' => '',
                        'images' => json_encode($images),
                        'type' => 'company',
                        'sizes_ids' =>  json_encode($sizes_ids),
                        'colors_ids' => json_encode($colors_ids),
                        'company_name' => $company_name,
                        'attributes' => json_encode($attributes),

                    ];

                    }

                }
            }

        $start += $this->limit;
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
