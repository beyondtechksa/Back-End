<?php
namespace App\Facades\Drivers;
use Illuminate\Support\Facades\Log;
use App\Facades\Contracts\CompanyDriverInterface;

class BreezeDriver implements CompanyDriverInterface

{

    public function extract($category)
    {
        $xml = file_get_contents("https://www.breeze.com.tr/XMLExport/F37FF33152C04B4EA7D0461AA9DFC743");
        $products = simplexml_load_string($xml , "SimpleXMLElement", LIBXML_NOCDATA);
        return  $products;
    }
}
