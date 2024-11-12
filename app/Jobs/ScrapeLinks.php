<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\TempProduct;
use Illuminate\Support\Facades\DB;


class ScrapeLinks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $link;
    protected $admin_id;
    protected $group_link;
    /**
     * Create a new job instance.
     */
    public function __construct($link,$admin_id,$group_link)
    {
        $this->link=$link;
        $this->admin_id=$admin_id;
        $this->group_link=$group_link;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $link=$this->link;
        $admin_id=$this->admin_id;
        $group_link=$this->group_link;
        // base_url
        $base_url = "https://cdn.dsmcdn.com";

        // get product info
        $product_link = 'https://www.trendyol.com'.$link;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $product_link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($curl);
        // Find JSON data within the script tag
        preg_match('/window\.__PRODUCT_DETAIL_APP_INITIAL_STATE__\s*=\s*(.*?);/', $response, $matches);
        if (isset($matches[1])) {
            // Parse the JSON object
            $product_data = json_decode($matches[1], true);
            
            // Extract product information
            if($product_data){
                $product_data = $product_data['product'];
            }else{
                $product_data = json_decode($matches[1], true);
            }
            $groupId = $product_data['productGroupId'];
        
            $apiUrl = "https://public.trendyol.com/discovery-web-websfxproductgroups-santral/api/v1/product-groups/".$groupId;
        
            $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $jsonData = curl_exec($curl);
            // print_r($jsonData);
            // Decode JSON data
            $data = json_decode($jsonData, true);
                if(count($data['result']['slicingAttributes'])>0){
                    $products = $data['result']['slicingAttributes'][0]['attributes'];
                    // print_r(json_encode($products));
                    foreach ($products as $index =>$product) {
                        $id = $product['contents'][0]['id'];
                        // echo $id;
                        $apiUrl2 = trim("https://public.trendyol.com/discovery-web-productgw-service/api/productDetail/" . strval($id) . "?storefrontId=1&culture=tr-TR&linearVariants=true&isLegalRequirementConfirmed=false&channelId=1");
                
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $apiUrl2);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        
                        $jsonData = curl_exec($curl);
                        // print_r($jsonData);
                        $data = json_decode($jsonData, true);

                        $this->single_product($data['result'],$product_link);
                    }
                    }else{
                        $this->single_product($product_data,$product_link);
                    }

            
            
        }

                        
                  
    }

    public function single_product($product,$product_link){
        $link=$this->link;
        $admin_id=$this->admin_id;
        $group_link=$this->group_link;
        $base_url = "https://cdn.dsmcdn.com";
        // 1. Name
        $id=$product['id'];
        $name=$product['name'];
        
        // 2. Image URL , 3. Sub Images
        $images=[];
        $images_data = $product['images'];
        foreach ($images_data as $img) {
            $images[]= $base_url . $img;
        }
    
        // 4. Price
        $price= $product['price']['sellingPrice']['value'];
    
        // 5. Discounted Price
        $initial_discount_price = $product['price']['discountedPrice']['value'];
        $discount_price=$initial_discount_price>0?$price-$initial_discount_price:0;

        $final_price=$price-$discount_price;
        $discount_percentage=$price>0?($discount_price*100/$price):0;
    
        // 6. Descriptions
        $desc='';
        if(array_key_exists('contentDescriptions',$product)){
            $descriptions = $product['contentDescriptions'];
            foreach ($descriptions as $description) {
                $desc.=$description['description'].' ';
            }
        }
    
        // 7. Attributes
        $attributes=[
            'name'=>count($product['variants'])>0?$product['variants'][0]['attributeName']:null,
            'options'=>[]
        ];
        $product_attributes = $product['merchantListings'];
        if(count($product_attributes)>0){
            if(array_key_exists('allVariants',$product_attributes[0])){
                $allVariants = $product_attributes[0]['allVariants'];
                foreach ($allVariants as $var) {
                    if($var['inStock']){
                        $attributes['options'][]=$var['value'];
                    }
                }
            }
        }

        $tempproduct = TempProduct::where('product_id',$id)->first();
        if(!$tempproduct){
            TempProduct::create(
                [
                    'product_id' =>$id,
                    'admin_id' =>$admin_id,
                    'sku' => 'BD-'.rand(100000, 999999),
                    'name' => $name,
                    'desc' => $desc,
                    'price' => $price,
                    'discount_price' => $discount_price,
                    'final_price' => $final_price,
                    'discount_percentage' => $discount_percentage,
                    'link' => $product_link,
                    'group_link' => $group_link,
                    'images' => $images,
                    'attributes' => $attributes,
                ]
                );

        }
    }
}
