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

        $this->url = filter_var(strip_tags($url), FILTER_SANITIZE_URL);

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
     * Verify If the Url is compatible 
     *
     * @param string $urlapi
     * @param string|null $indicate
     * @return boolean
     */
    public function verifyUrlIntegrity(string $urlapi, ?string $indicate):bool
    {

        //Get Hostname
        $url = explode(".", parse_url($urlapi, PHP_URL_HOST));

        //List of authorize hosts
        $host_list = ["com", "pt", "uk", "org", "net", "br"];
        $add_host_list = !is_null($indicate) ? array_push($host_list, $indicate) : $host_list;

        //Get Boolean from url integrity
        $verifyintegrity = in_array(end($url), $add_host_list);

        return $verifyintegrity;
        

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
     * Get Url from input
     *
     * @param Request $request
     * @return object|string
     */
    public function getRequestUrl(Request $request):object|string
    {

        //Indicate Url
        $this->setUrl($request->indicateurl);
        $url = $this->getUrl();

        return $this->requestUrlContent($url);
        

    }

    /**
     * Get the name of site reference
     *
     * @param string $url
     * @return string
     */
    public function getHostName(string $url):string
    {

        //Get Hostname
        $url = explode(".", parse_url($url, PHP_URL_HOST));

        //Get Hostname
        $sitename = array_filter($url, function($value){

            //List of Valid Hostnames
            $hostname = ["worten", "staples", "amazon"];

            return in_array($value, $hostname) ? $value : null;

        });

        return implode("", $sitename);


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

        //Get Hostname
        $host = $this->getHostName($url);

        //Get Staples Content
        $staples = new StaplesFoxController();
        $worten = new WortenFoxController();

        $content = match($host){
            "staples" => $staples->getContentFromUrl($url, $validate_url, $validate_ssl, null),
            "worten" => $worten->getContentFromUrl($url, $validate_url, $validate_ssl, null)

        };

        return $content;

    }

}
