<?php
namespace App\Classes;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebScraper{

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function scrape($url)
    {
        $client = new Client();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            $links = $crawler->filter('.p-card-chldrn-cntnr.card-border')->each(function ($node) {
                $link = $node->filter('a')->attr('href');
                return [
                    'link' => $link,
                ];
            });
            $products=[];
            foreach($links as $link){
                $response = $client->request('GET', 'https://www.trendyol.com'.$link['link']);
                if ($response->getStatusCode() == 200) {
                    $html = $response->getBody()->getContents();
                    $crawler = new Crawler($html);

                    $product = $crawler->filter('#product-detail-app')->each(function ($node) use($link) {
                        $name = $node->filter('.pr-new-br')->text();
                        $discount_price = $node->filter('.prc-dsc')->text();
                        $price = $node->filter('.prc-org');
                        $desc = $node->filter('#content-descriptions-list');
                        $image=$node->filter('.detail-section-img');
                        
                        return [
                            'name' => $name,
                            'desc' => count($desc)>0?$desc->text():null,
                            'discount_price' => $discount_price,
                            'price' => count($price)>0?$price->text():$discount_price,
                            'link' => 'https://www.trendyol.com'.$link['link'],
                            'image' => count($image)>0?$image->attr('src'):'',
                        ];
                    });
                    if(count($product)>0){
                        $products[]=$product[0];
                    }
                }
            }

            return $products;
        }

        return [];
    }

    
}

