<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;

class ChatGptConversationController extends ChatGptController
{

    private $openapi = 'https://api.openai.com/v1/chat/completions';
    //private $openapi = 'https://api.openai.com/v1/engines/davinci-codex/completions';
    private $model = "gpt-3.5-turbo";
    //private $model = "text-davinci-003";
    //private $prompt = "Assist me with a list of wonderfull words";
    private $responseList = [];

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
    private function postFiledsStructure($prompt)
    {
        //More PostFileds for Api
       /*  "n": 1,
        "stream": false,
        "logprobs": null,
        "max_tokens": 150,
        "stop": "\n",
        "stop": [" Human:", " AI:"] 
        "messages": [{"role": "user", "name": "user123456", "content": "'. strval(trim(filter_var(strip_tags($prompt), FILTER_DEFAULT))) .'"}],
        */

        $postFileds = [
            'model' => $this->model,
            'messages' => $prompt,
            'temperature' => 0.9,
            'max_tokens' => 150,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.6,
            'user' => "user123456"
        ];

        //dd($postFileds);

        return json_encode($postFileds);


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
    protected function openApiChat(Request $request):object|array
    {

        $lorem = new stdClass();
        $lorem->role = 'user';
        //$lorem->content = $this->prompt; //$request->chatindicateprompt
        $lorem->content = $request->chatindicateprompt;

        array_push($this->responseList, $lorem);

        //Create a Object to send for FrontEnd
        $chatGptContent = new stdClass();

        //Check if the apiUrl is security
        if($this->checkUrl($this->openapi) && $this->httpsVerify($this->openapi)){

            //Get content from Curl Connect
            //$content = $this->curlStructure($this->openapi, $this->postFiledsStructure($request->chatindicateprompt));
            $content = $this->curlStructure($this->openapi, $this->postFiledsStructure($this->responseList));


            //Get Content from API
            if(!is_string($content)){

                //Conditional Loop for response
                if(count($this->responseList) <= 1){

                    $getresponse = $this->transformContentFromApi($content, $chatGptContent);

                    array_push($this->responseList, $getresponse);

                    return $this->responseList;

               /*      if(count($this->responseList) > 1){

                        $contentsequence = $this->curlStructure($this->openapi, $this->postFiledsStructure($this->responseList));

                        $getresponsesequence = $this->transformContentFromApi($contentsequence, $chatGptContent);

                        array_push($this->responseList, $getresponse);

                        return $this->responseList;

                    } */
    

                }else{

                    $content = $this->curlStructure($this->openapi, $this->postFiledsStructure($this->responseList));

                    $getresponse = $this->transformContentFromApi($content, $chatGptContent);

                    array_push($this->responseList, $getresponse);
    
                    return $getresponse;

                }

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
