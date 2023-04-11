<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

/**
 * 
 * Get Content from Worten
 * 
 * @author Joene Galdeano <joene.goncalves@bibright.com>
 * @since Fox Project 1.0.0
 */
class WortenFoxController extends ApiFoxController implements InterfaceFoxController
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
                            'title' => $xpath->evaluate('//h1[@class="title"]//span'),
                            'stars' => $xpath->evaluate('//span[@class="rating__star-value semibold"]'),
                            'price' => $xpath->evaluate('//div[@class="product-price-info"]//span//span//span//span//span'),
                            'seller' => $xpath->evaluate('//div[@class="product-price-info__seller"]//div'),                      
                            'reviews' => $xpath->evaluate('//span[@class="rating__opinions"]//span')
                        ];

                        //return $product_data['stars'];

                        //Get clean Data                    
                        $get_title = implode("", $this->filterData($product_data, 'title'));
                        //$get_price = sprintf('%01.2f', intval($this->filterData($product_data, 'price')));
                        $get_price = number_format(intval($this->filterData($product_data, 'price')), 0, '', '.');
                        //$get_price = intval(implode("", $this->filterData($product_data, 'price')));
                        //$get_price = intval($this->filterData($product_data, 'price'));
                        $get_seller = implode("", $this->filterData($product_data, 'seller'));
                        $get_total_of_starts = floatval(implode("", $this->filterData($product_data, 'stars')));
                        $get_total_of_reviews = intval(str_replace(' opiniões', '', implode("", $this->filterData($product_data, 'reviews'))));                      

                        //List of Clean Data
                        $list_of_clean_data = new stdClass();
                        $list_of_clean_data->title = $get_title;
                        $list_of_clean_data->price = abs($get_price);
                        //$list_of_clean_data->price = sprintf('%01.2f', $get_price);
                        $list_of_clean_data->seller = $get_seller;
                        $list_of_clean_data->total_stars = $get_total_of_starts;
                        $list_of_clean_data->total_reviews = $get_total_of_reviews;

                        return $list_of_clean_data;

                    }


                }catch(Exception $err){

                    //throw new Exception('Exception message');

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
    public function filterData(array $list_of_data, string $type_of_data):array|string|int
    {

        //Verify if array is not empty 
        if(is_array($list_of_data) && count($list_of_data) > 0){

            //Add correspondent data
            switch($type_of_data){

                case 'title':

                    //Get Data from Helper
                    $list_of_clean_data = [];

                    foreach($list_of_data[$type_of_data] as $value){

                        if(!ctype_space($value->textContent)){

                            array_push($list_of_clean_data, trim($value->textContent));

                        }

                    }

                    return $list_of_clean_data;


                break; 
                
                case 'price':

                    //Get Data from Helper
                    $list_of_clean_data = [];

                    foreach($list_of_data[$type_of_data] as $value){

                        
                        foreach($value->attributes as $val_price){

                           if($val_price->nodeName === "value"){

                                array_push($list_of_clean_data, trim($val_price->textContent));

                           }

                        }

                    }

                    return $list_of_clean_data[0] . 00;
                   

                break; 

                case 'seller':

                    //Get Data from Helper
                    $list_of_clean_data = [];

                    foreach($list_of_data[$type_of_data] as $value){

                        foreach($value->childNodes as $val_child){

                            if($val_child->nodeName === 'a'){

                                foreach($val_child->childNodes as $val_seller){

                                    if($val_seller->nodeName === 'span'){

                                        if(!ctype_space($val_seller->textContent)){

                                            array_push($list_of_clean_data, trim($val_seller->textContent));
            
                                        }

                                    }


                                }

                            }

                        }

                    }

                    return $list_of_clean_data;


                break; 

                case 'stars':

                    //Get Data from Helper
                    $list_of_clean_data = [];

                    foreach($list_of_data[$type_of_data] as $value){

                        foreach($value->childNodes as $val_child){

                            if(!ctype_space($val_child->textContent) && !empty($val_child->textContent)){

                                array_push($list_of_clean_data, trim($val_child->textContent));

                            }

                        }

                    }

                    return $list_of_clean_data;

                break; 

                case 'reviews':

                    //Get Data from Helper
                    $list_of_clean_data = [];

                    foreach($list_of_data[$type_of_data] as $value){

                        if(!ctype_space($value->textContent) && !empty($value->textContent)){

                            array_push($list_of_clean_data, trim($value->textContent));

                        }
                    }

                    return $list_of_clean_data;

                break;

            }

        }

        //Finish execution of function
        die;
    
    }


}
