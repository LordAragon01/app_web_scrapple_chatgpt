<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

class StaplesFoxController extends ApiFoxController
{


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
        ?bool $validatehttps,
        ?string $indicate
        ):object|string
    {
     
        //Validate URL structure
        if($validateurl === true && $validatehttps === true){

            //Verify Url Integrity
            if($this->verifyUrlIntegrity($url, $indicate)){

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
                        $get_total_of_starts = floatval(implode("", $this->filterData($product_data, 'stars')));
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
                    $message_error = $err->getMessage();

                    return $message_error;

                }

            }

            return "Erro ao Processar Requisição";

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
    
}
