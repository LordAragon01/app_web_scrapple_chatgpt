<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

class FoxController extends Controller
{
    //private $url = "https://www.amazon.com/Glass-Whiteboard-Magnetic-Erase-Board/dp/B08RYQS9JL?th=1";
    private $url = "https://www.staples.com/tru-red-melamine-dry-erase-board-black-frame-6-x-4-tr59365/product_24534067";
    
    public function index()
    {

        $title = "Web Crawler & Scrapping";

        $url = $this->url;

        $content = $this->getContentFromUrl($url);

        dd($content);

        return view('pages.fox_project', compact(
            'title'
        ));

    }

    public function getContentFromUrl($url)
    {

        //Get Http response status
        $response = Http::timeout(5)
                    ->get($url);

        //add this line to suppress any warnings
        libxml_use_internal_errors(true); 
        
        $doc = new DOMDocument();
        $doc->loadHTML($response->body());

        //Get Xml structure
        $xpath = new DOMXPath($doc);

        //Data from xml
        $product_data = [
            'title' => $xpath->evaluate('//h1[@id="product_title"]'),
            'price' => $xpath->evaluate('//div[@class="price-info__final_price_sku"]')
        ];

        //Get clean Data
        $get_title = implode("", $this->filterData($product_data, 'title'));
        //$get_price = intval(str_replace('$', '', implode($this->filterData($product_data, 'price'))));
        $get_price = floatval(str_replace('$', '', implode($this->filterData($product_data, 'price'))));

        //List of Clean Data
        $list_of_clean_data = new stdClass();
        $list_of_clean_data->title = $get_title;
        $list_of_clean_data->price = $get_price;

        return $list_of_clean_data;


    }

    public function filterData(array $list_of_data, string $type_of_data)
    {

        //Verify if array is not empty 
        if(is_array($list_of_data) && count($list_of_data) > 0){

            //Get clean data from Url
            $list_of_clean_data = [];

            //Add correspondent data
            switch($type_of_data){

                case 'title':

                    foreach($list_of_data[$type_of_data] as $value){

                        array_push($list_of_clean_data, $value->textContent);
        
                    }
        
                    return $list_of_clean_data;

                break; 
                
                case 'price':

                    foreach($list_of_data[$type_of_data] as $value){
        
                        array_push($list_of_clean_data, $value->textContent);
        
                    }
        
                    return $list_of_clean_data;

                break; 


            }

        }

        //Finish execution of function
        die;
    
    }


    public function getAllContentFromUrl($response_http)
    {

        $doc = new DOMDocument();
        $doc->loadHTML($response_http);

        //# save HTML 
        $content = $doc->saveHTML();

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
        //$content .= $html;

        return $html;

    }

}
