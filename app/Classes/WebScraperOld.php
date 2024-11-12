<?php
namespace App\Classes;
use GuzzleHttp\Client;

class WebScraper{

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function scrape($url)
    {
        // Fetch HTML content from the URL with cURL
        $html = $this->getHtmlContent($url);

        // Create a DOMDocument and load the HTML content with the correct encoding
        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true); // Disable libxml errors
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors(); // Clear libxml errors

        // Create a DOMXPath instance
        $xpath = new \DOMXPath($dom);


        // Extract product information using XPath expressions
        $titleNode = $xpath->query('//*[@id="product-detail-app"]/div/div[2]/div/div[2]/div[2]/div/div[1]/div[2]/div/div/div[1]/h1')->item(0);
        $title = $titleNode ? $titleNode->textContent : 'Title not found';

        $priceNode = $xpath->query('//*[@id="product-detail-app"]/div/div[2]/div/div[2]/div[2]/div/div[1]/div[2]/div/div/div[3]/div/div/span')->item(0);
        $price = $priceNode ? $priceNode->textContent : 'Price not found';

        $descriptionNode = $xpath->query('//*[@id="content-descriptions-list"]')->item(0);
        $description = $descriptionNode ? $descriptionNode->textContent : 'Description not found';
        
        // $imageNode = $xpath->query('//*[@id="product-detail-app"]/div/div[2]/div/div[2]/div[2]/div/div[1]/div[3]/section/div[2]/div[1]/div[2]/a[5]/img')->item(0);
        // $image = $imageNode ? $imageNode->getAttribute('src') : 'image not found';
        $all_images=[];
        $images = $dom->getElementsByTagName('img'); 
         foreach($images as $image) {
            $all_images[]=$image->getAttribute('src');
        }

        // Return the extracted data
        return response()->json([
            'name' => $title,
            'price' => $price,
            'desc' => $description,
            'images' => $all_images,
        ]);
    }

    private function getHtmlContent($url)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, ''); // Handle all encodings

        $html = curl_exec($curl);

        curl_close($curl);

        return $html;
    }
}