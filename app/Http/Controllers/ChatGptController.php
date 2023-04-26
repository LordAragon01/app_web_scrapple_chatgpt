<?php

namespace App\Http\Controllers;

use CurlHandle;
use Exception;
use Illuminate\Http\Request;
use stdClass;

class ChatGptController extends Controller
{

    private $openapi = 'https://api.openai.com/v1/completions';
    //private $openapi = 'http';
    private $model = "text-davinci-003";
    //private $model = "text-davi";
    private $tolken = "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";
    //private $tolken = "sk-";
    

    public function index()
    {

        $title = "ChatGpt Custom";

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
    private function checkUrl(string $urlapi):bool
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
    private function httpsVerify(string $urlapi):bool
    {

        return parse_url($urlapi, PHP_URL_SCHEME) === 'https' ? true : false;

    }

    protected function openApiCon(Request $request)
    {

        //Create a Object to send for FrontEnd
        $chatGptContent = new stdClass();

        //Check if the apiUrl is security
        if($this->checkUrl($this->openapi) && $this->httpsVerify($this->openapi)){

            $curl = curl_init();

                curl_setopt_array($curl,
                array(
                    CURLOPT_URL => $this->openapi,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "model": "'. $this->model .'",
                        "prompt": "'. trim($request->indicateprompt) .'",
                        "temperature": 0.7,
                        "max_tokens": 2048,
                        "top_p": 1.0,
                        "frequency_penalty": 0.0,
                        "presence_penalty": 0.0
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $this->tolken,
                        'Content-Type: application/json'
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

            //Helper Create
            if(!is_string($content)){

                //Get All Data From OpenApi
                foreach($content as $value){

                  /*   $text = mb_convert_encoding($value->text, 'UTF-8');
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
