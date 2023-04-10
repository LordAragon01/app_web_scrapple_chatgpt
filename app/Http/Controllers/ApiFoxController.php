<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

class ApiFoxController extends Controller
{

    //Propertie URL
    private $url;

    /**
     * Get Clean URL
     *
     * @return string
     */
    public function getUrl():string
    {

        return strval($this->url);

    }

    /**
     * Set Url
     *
     * @param string $url
     * @return
     */
    public function setUrl(string $url)
    {

        $this->url = $url;

    }

       /**
     * Check if the URL is validate
     *
     * @param string $urlapi
     * @return boolean
     */
    public function checkUrl(string $urlapi):bool
    {

        if(filter_var($urlapi, FILTER_VALIDATE_URL)){

            return true;

        }else{

            return false;

        }

    }

    /**
     * Verify if the url contain https
     *
     * @param string $urlapi
     * @return boolean
     */
    public function httpsVerify(string $urlapi):bool
    {

        return parse_url($urlapi, PHP_URL_SCHEME) === 'https' ? true : false;

    }

   /**
    * Indicate URL and Get Clean Content
    *
    * @param string $url
    * @param boolean $validateurl
    * @param boolean $validatehttps
    * @return object|string
    */
    public function getContentFromUrl(
        string $url,
        ?bool $validateurl,
        ?bool $validatehttps
        ):object|string
    {
     
        
        if($validateurl === true && $validatehttps === true){

            //Get Http response status
            $response = Http::timeout(5)
                        ->accept('text/html; charset=utf-8')
                        ->get($url);

            //dd($response->headers()); 

            try{

                //Url Http Response Verificarion
                if($response->successful()){
    
                    //add this line to suppress any warnings
                    libxml_use_internal_errors(true); 
                    
                    $doc = new DOMDocument();
                    $doc->loadHTML($response->body());
    
                    //Get Xml structure
                    $xpath = new DOMXPath($doc);
    
                    //Data from xml
                    $product_data = [
                        'title' => $xpath->evaluate('//h1[@id="product_title"]'),
                        'price' => $xpath->evaluate('//div[@class="price-info__final_price_sku"]'),
                        'stars' => $xpath->evaluate('//span[@id="core-sku-rating-label"]'),
                        'reviews' => $xpath->evaluate('//span[@id="core-sku-review-count"]')
                    ];
    
                    //Get clean Data
                    $get_title = implode("", $this->filterData($product_data, 'title'));
                    //$get_price = intval(str_replace('$', '', implode($this->filterData($product_data, 'price'))));
                    $get_price = floatval(str_replace('$', '', implode($this->filterData($product_data, 'price'))));
                    $get_total_of_starts = intval(implode("", $this->filterData($product_data, 'stars')));
                    $get_total_of_reviews = intval(str_replace(' Review', '', implode("", $this->filterData($product_data, 'reviews'))));

                    //List of Clean Data
                    $list_of_clean_data = new stdClass();
                    $list_of_clean_data->title = $get_title;
                    $list_of_clean_data->price = $get_price;
                    $list_of_clean_data->total_stars = $get_total_of_starts;
                    $list_of_clean_data->total_reviews = $get_total_of_reviews;
    
                    return $list_of_clean_data;
    
                }

    
            }catch(Exception $err){
    
                //Message error
                $message_error = $err->getMessage("Erro ao processar requisição");
    
                //Url Http Response Verificarion
                if($response->failed()){
    
                    return $message_error;
    
                }
    
            }

        }

        return "Erro ao Processar Requisição";
        
    }

    /**
     * Filter Data and Create Array with select data
     *
     * @param array $list_of_data
     * @param string $type_of_data
     * @return array
     */
    public function filterData(array $list_of_data, string $type_of_data):array
    {

        //Verify if array is not empty 
        if(is_array($list_of_data) && count($list_of_data) > 0){

            //Add correspondent data
            switch($type_of_data){

                case 'title':

                    //Get Data from Helper
                    return $this->helperFilterData($list_of_data, $type_of_data);

                break; 
                
                case 'price':

                    //Get Data from Helper
                    return $this->helperFilterData($list_of_data, $type_of_data);

                break; 

                case 'stars':

                    //Get Data from Helper
                    return $this->helperFilterData($list_of_data, $type_of_data);

                break; 

                
                case 'reviews':

                    //Get Data from Helper
                    return $this->helperFilterData($list_of_data, $type_of_data);

                break;


            }

        }

        //Finish execution of function
        die;
    
    }

    /**
     * Helper for filter Data Function
     *
     * @param array $list_of_data
     * @param string $type_of_data
     * @return array
     */
    public function helperFilterData(array $list_of_data, string $type_of_data):array
    {

        //Get clean data from Url
        $list_of_clean_data = [];

        foreach($list_of_data[$type_of_data] as $value){

            array_push($list_of_clean_data, $value->textContent);

        }

        return $list_of_clean_data;

    }

    /**
     * Get All Content From URL and transform in string
     *
     * @param string $response_http
     * @return string
     */
    public function getAllContentFromUrl(string $response_http):string
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

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return object|string
     */
    public function getRequestUrl(Request $request):object|string
    {

        $this->setUrl($request->indicateurl);

        $url = $this->getUrl();

        return $this->requestUrlContent($url);
        

    }


    /**
     * Send Data request API
     *
     * @return object|string
     */
    public function requestUrlContent($url):object|string
    {

        //Indicate Url
        //$this->setUrl("https://www.staples.com/tru-red-melamine-dry-erase-board-black-frame-6-x-4-tr59365/product_24534067");
        //$url = $this->getUrl();

        //Validate URL
        $validate_url = $this->checkUrl($url);

        //Validate HTTPS
        $validate_ssl = $this->checkUrl($url);

        //Get Data from URL
        $content = $this->getContentFromUrl($url, $validate_url, $validate_ssl);
    
        //return json_encode(['Success' => $content]);

        return $content;

    }

}
