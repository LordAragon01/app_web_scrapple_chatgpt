<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;

class ChatGptConversationController extends ChatGptController
{

    private $openapi = 'https://api.openai.com/v1/chat/completions';
    private $model = "gpt-3.5-turbo";
    //private $prompt = "Hello !";

    public function index()
    {

        $title = "ChatGpt Chat";

        return view('pages.raven_project_chat', compact(
            'title'
        ));

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
            "messages": [{"role": "user", "name": "user123456", "content": "'. strval(trim(filter_var(strip_tags($prompt), FILTER_DEFAULT))) .'"}],
            "temperature": 0.9,
            "max_tokens": 150,
            "top_p": 1.0,
            "frequency_penalty": 0.0,
            "presence_penalty": 0.6,
            "user": "user123456",
            "stop": [" Human:", " AI:"] 
        }';


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

        //dd($content);

        //Get All Data From OpenApi
        foreach($content as $value){

            //dd($value);

            /* $text = mb_convert_encoding($value->text, 'UTF-8');
            $text2 = htmlentities($value->text);
            $text3 = htmlspecialchars($value->text); */

            //$name = html_entity_decode($value->message->name);
            $role = html_entity_decode($value->message->role);
            $text4 = html_entity_decode($value->message->content);

            $chatGptContent->content = $text4;
            $chatGptContent->role = $role;
            //$chatGptContent->role = $name;
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
    protected function openApiChat(Request $request):object
    {

        //Create a Object to send for FrontEnd
        $chatGptContent = new stdClass();

        //Check if the apiUrl is security
        if($this->checkUrl($this->openapi) && $this->httpsVerify($this->openapi)){

            //Get content from Curl Connect
            $content = $this->curlStructure($this->openapi, $this->postFiledsStructure($request->chatindicateprompt));

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
