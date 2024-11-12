<?php
namespace App\Facades\Drivers\Test;
use Illuminate\Support\Facades\Log;
use App\Facades\Contracts\CompanyDriverInterface;

class ToucheDriver implements CompanyDriverInterface
{

    public function extract($category)
    {
        $xml = file_get_contents("https://cdn1.xmlbankasi.com/p1/bigdartr/image/data/xml/standart.xml");
        $products = simplexml_load_string($xml , "SimpleXMLElement", LIBXML_NOCDATA);
        return  $products;
    }
}
