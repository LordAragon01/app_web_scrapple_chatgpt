<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;
use Spatie\Crawler\Crawler;
use stdClass;

class WebCrawlerController extends Controller
{

    private $url_search = "https://www.noticeboardcompany.com";
    private $path_url_search = "/lockable-indoor-notice-boards/hinged-door/";
    //private $url_search = "https://books.toscrape.com/";
    
    public function index(){

        $title = "Web Crawler & Scrapping";

        $url_link = $this->url_search . $this->path_url_search;

        $result = $this->getBodyFromWeb($this->url_search, $this->path_url_search);

        //$crawler = new CustomCrawlerObserver(); 

        //$content = $crawler->finishedCrawling();

        $clean_list = $this->cleanProductList($result);
        $product_data = $this->separateProductData($clean_list['product'], $clean_list['price']);

        //dd($product_data);

        return view('pages.webcrawler', compact(
            'title',
            'product_data'
        ));


    }

    public function getBodyFromWeb($url, $path){

        $url_link = $url . $path;

        $response = Http::timeOut(5)
                    //->withHeaders([])
                    //->baseUrl($url)
                    //->accept('text/html')
                    ->get($url_link);
                    //->get("https://books.toscrape.com/");

        //$response->headers();
        //$content_type = $response->header('Content-Type');

        //add this line to suppress any warnings
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->loadHTML($response->body());

        //# save HTML 
        $content = $doc->saveHTML();
        //# convert encoding
        $content1 = mb_convert_encoding($content,'UTF-8',mb_detect_encoding($content,'UTF-8, ISO-8859-1',true));
        //# strip all javascript
        $content2 = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content1);
        //# strip all style
        $content3 = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content2);
        //# strip tags
        $content4 = str_replace('<',' <',$content3);
        $content5 = strip_tags($content4);
        $content6 = str_replace( '  ', ' ', $content5 );
        //# strip white spaces and line breaks
        $content7 = preg_replace('/\s+/S', " ", $content6);
        //# html entity decode - ö was shown as &ouml;
        $html = html_entity_decode($content7);
        //# append
        $content .= $html;

        //$tag_class = $doc->getElementsByTagName('div');

        $xpath = new DOMXPath($doc);

        //$titles = $xpath->evaluate('//div[@class="product"]//div[@class="image-container"]//h3//a');
        $titles = $xpath->evaluate('//html//body//div'); //html/body/div
        //$prices = $xpath->evaluate('//ol[@class="row"]//li//article//div[@class="product_price"]//p[@class="price_color"]');
        
        $list_of_products = [];

        foreach($titles as $value){

            //dd($value->attributes);
            
            foreach($value->attributes as $val_attr){

                if(str_contains($val_attr->value, 'product')){

                    //$clean = mb_convert_encoding($value->nodeValue, 'UTF-8');
                    //$clean = preg_replace('/["\r\n\t","\r","\n","\t"]+/', '', $value->nodeValue);
                    //$content_clean = preg_replace('/\s+/S', '', $clean);
                    //$content_test = str_split($content_clean, 5);

                    //$list_data = explode(" ", $content_clean);

                    //dd($value->childNodes);

                    foreach($value->childNodes as $val_text){

                        //dd($val_text->textContent);ctype_space
                       /*  if(!str_contains($val_text->textContent, '"""\r\n\t\t\t\t\t\t\t\t"""')){

                            array_push($list_of_products, trim($val_text->textContent));

                        } */

                        if(!ctype_space($val_text->textContent)){

                            array_push($list_of_products, trim($val_text->textContent));

                        }

                    }

                }

            }

        }

        //dd($list_of_products);

        /* $list_of_products = array_map(function($product){

            if($product !== ""){

                return $product;

            }

        }, $list_of_products); */


        //dd($list_of_products);

        //$test = $xpath->query();

        //return $response->body();

        //return $titles;

        return $list_of_products;

        //return $html;

    }

    public function cleanProductList($list_of_products){

        $list = [
            'product' => [],
            'price' => []
        ];
       
       
        foreach($list_of_products as $value){


            if(!is_null($value) && !str_contains($value, "More Details")){


                if(!str_contains($value, "From")){

                    array_push($list['product'], $value);

                }else{

                    $clean_value = str_replace("From£", "", $value);
                    array_push($list['price'], intval($clean_value));

                }

            }

        };

        return $list;

    }

    public function separateProductData($list_product_name, $list_product_price){

        $all_data = [];

        foreach($list_product_name as $key => $value){

            $info_product_data = new stdClass();
            $info_product_data->product_name = $value;
            $info_product_data->product_price = number_format($list_product_price[$key], 2, '.', ',');
            $info_product_data->last_att = date('d/m/Y');

            array_push($all_data, $info_product_data);

        }

        return $all_data;

    }

    public function fetchContent(){
        //# initiate crawler 
        Crawler::create([RequestOptions::ALLOW_REDIRECTS => true, RequestOptions::TIMEOUT => 30])
        ->acceptNofollowLinks()
        ->ignoreRobots()
        // ->setParseableMimeTypes(['text/html', 'text/plain'])
        ->setCrawlObserver(new CustomCrawlerObserver())
        ->setCrawlProfile(new CrawlInternalUrls('https://www.lipsum.com'))
        ->setMaximumResponseSize(1024 * 1024 * 2) // 2 MB maximum
        ->setTotalCrawlLimit(100) // limit defines the maximal count of URLs to crawl
        // ->setConcurrency(1) // all urls will be crawled one by one
        ->setDelayBetweenRequests(100)
        ->startCrawling('https://www.lipsum.com');
        return true;
    }


}
