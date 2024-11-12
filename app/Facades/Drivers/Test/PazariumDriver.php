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

class PazariumDriver implements CompanyDriverInterface
{
    protected $limit = 100; // Fetch 1000 products at a time 

    public function extract($category,$type='scrap')
    {
        $url = "https://www.pazarium.com.tr/xml/?R=1310703&K=152b&AltUrun=1&TamLink=1&Dislink=1&Seo=1&Imgs=1&start=0&limit=100&pass=QUB1y0fJ";
        $xml = fetchDataFromUrl($url);
        $productsData = simplexml_load_string($xml , "SimpleXMLElement", LIBXML_NOCDATA);
    return $productsData;

        

        if($type=='scrap'){
            ScrapeProductsCompany::dispatch($products);
        }elseif($type=='track'){
            UpdateProductsCompany::dispatch($products,$company_name);
        }
        return $products;
    }
}
