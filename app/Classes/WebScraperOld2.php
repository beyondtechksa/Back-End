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

            $products = $crawler->filter('.p-card-chldrn-cntnr.card-border')->each(function ($node) {
                $title = $node->filter('.prdct-desc-cntnr-name')->text();
                $price = $node->filter('.prc-box-dscntd')->text();
                $image = $node->filter('.p-card-img')->attr('src');

                return [
                    'title' => $title,
                    'price' => $price,
                    'image' => $image,
                ];
            });

            return $products;
        }

        return [];
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