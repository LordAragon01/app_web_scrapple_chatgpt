<?php

namespace App\Http\Controllers;

use CurlHandle;
use Exception;
use Illuminate\Http\Request;
use stdClass;

/**
 * Structure for get data from OpenApi
 * @author Joene Galdeano
 * @version 1.0.0
 */
class ChatGptController extends Controller
{

    private $openapi = 'https://api.openai.com/v1/completions';
    //private $openapi = 'http';
    private $model = "text-davinci-003";
    //private $model = "text-davi";
    //private $tolken = "";
    

    public function index()
    {

        $title = "ChatGpt Answer";

        return view('pages.raven_project', compact(
            'title'
        ));

    }

    /**
     * Check if the URL is validate
     *
     * @param string $urlapi
     * @return boolean
     */
    protected function checkUrl(string $urlapi):bool
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
    protected function httpsVerify(string $urlapi):bool
    {

        return parse_url($urlapi, PHP_URL_SCHEME) === 'https' ? true : false;

    }

    /**
     * Inform the Post Field for CURLOPT_POSTFIELDS
     *
     * @param string $prompt
     * @return string
     */
    private function postFiledsStructure(string $prompt):string
    {
        //More PostFileds for Api
       /*  "n": 1,
        "stream": false,
        "logprobs": null,
        "stop": "\n" */

        return '{
            "model": "'. $this->model .'",
            "prompt": "'. strval(trim(filter_var(strip_tags($prompt), FILTER_DEFAULT))) .'",
            "temperature": 0.7,
            "max_tokens": 2048,
            "top_p": 1.0,
            "frequency_penalty": 0.0,
            "presence_penalty": 0.0
        }';


    }

    /**
     * Curl Structure to get Content from API
     *
     * @param string $prompt
     * @return array|string
     */
    protected function curlStructure(string $postfields):array|string
    {

        //Curl Structure
        $curl = curl_init();

            curl_setopt_array($curl,
            array(
                CURLOPT_URL => $this->openapi,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_SSL_VERIFYHOST => 2, 
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . config('app.openapi_key'),
                    'Content-Type: application/json; charset=utf-8', //text/plain, multipart/form-data
                    'Accept: application/json', //text/xml,application/xml,application/xhtml+xml
                ),
            ));

            //Get Content
            $response = curl_exec($curl);

            //Get Header Content
            $header  = curl_getinfo($curl);

            //Get Error
            $err = curl_errno($curl);

            //Get Error Message
            $errmsg  = curl_error($curl) ;

            //Curl Structure
            $header['errno']   = $err;

            $header['errmsg']  = $errmsg;

            //Get Header Response
            $header['content'] = $response;

            //Decode Content from OpenApi
            $contentDecode = json_decode($response);

            //Get Error Message ou send Content
            $content = $header['http_code'] === 200 ? $contentDecode->choices : $contentDecode->error->message ; 
        
        curl_close($curl);

        return $content;   

    }

    /**
     * Helper to filter Content from API
     *
     * @param array $content
     * @param object $chatGptContent
     * @return object
     */
    private function transformContentFromApi(array $content, object $chatGptContent):object
    {

        //Get All Data From OpenApi
        foreach($content as $value){

            /* $text = mb_convert_encoding($value->text, 'UTF-8');
            $text2 = htmlentities($value->text);
            $text3 = htmlspecialchars($value->text); */

            $text4 = html_entity_decode($value->text);

            $chatGptContent->content = $text4;
            //dd($text);
            //dd($text2);

        }

        //Return an object
        return $chatGptContent;

    }

    /**
     * Get data from OpenApi
     *
     * @param Request $request
     * @return object
     */
    protected function openApiCon(Request $request):object
    {

        //Create a Object to send for FrontEnd
        $chatGptContent = new stdClass();

        //Check if the apiUrl is security
        if($this->checkUrl($this->openapi) && $this->httpsVerify($this->openapi)){

            //Get content from Curl Connect
            $content = $this->curlStructure($this->postFiledsStructure($request->indicateprompt));

            //Get Content from API
            if(!is_string($content)){

                return $this->transformContentFromApi($content, $chatGptContent);

            }

            //Send Error Message
            $chatGptContent->content = $content;

            //Return an object
            return $chatGptContent;

        }else{

            //Verify if the Debug is true
            if(env('APP_DEBUG')){

                //Send Error Message
                $chatGptContent->content = "A Url informada é inadequada";

                //Return an object
                return $chatGptContent;

            }else{

                //Finished Exxecution
                die;

            }

        }


    }


}
