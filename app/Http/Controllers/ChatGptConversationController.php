<?php

namespace App\Http\Controllers;

use App\Models\ChatMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ChatGptConversationController extends ChatGptController
{

    private $openapi = 'https://api.openai.com/v1/chat/completions';
    //private $openapi = 'https://api.openai.com/v1/engines/davinci-codex/completions';
    private $model = "gpt-3.5-turbo";
    //private $model = "text-davinci-003";
    //private $prompt = "Hello !";
    private $responseList = [];

    public function index()
    {

        $title = "ChatGpt Chat";

        return view('pages.raven_project_chat', compact(
            'title'
        ));

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

            $chatGptContent->role = $role;
            $chatGptContent->content = $text4;
            
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

        //Create User Object
        $userMessageDefault = new stdClass();
        $userMessageDefault->role = 'user';
        //$userMessageDefault->content = $this->prompt; //$request->chatindicateprompt
        $userMessageDefault->content = $request->chatindicateprompt;

        array_push($this->responseList, $userMessageDefault);

        //Create a Object to send for FrontEnd
        $chatGptContent = new stdClass();

        //Check if the apiUrl is security
        if($this->checkUrl($this->openapi) && $this->httpsVerify($this->openapi)){

            //Get content from Curl Connect
            //$content = $this->curlStructure($this->openapi, $this->postFiledsStructure($request->chatindicateprompt));
            $content = $this->curlStructure($this->openapi, $this->postFiledsStructure());

            //Get Content from API
            if(!is_string($content)){

                $getresponse = $this->transformContentFromApi($content, $chatGptContent);

                //Conditional Loop for save messages in the db
                $chatDb = new ChatMessages();
                    $chatDb->role_user = 'user';
                    $chatDb->message_user = $request->chatindicateprompt;
                    $chatDb->role_ai = $getresponse->role;
                    $chatDb->message_ai = $getresponse->content;
                $chatDb->save();

                array_push($this->responseList, $getresponse);

                //dd($this->postFiledsStructure());

                return $this->responseList;

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

    /**
     * Inform the Post Field for CURLOPT_POSTFIELDS
     *
     * @param string $prompt
     * @return string
     */
    private function postFiledsStructure()
    {
        //More PostFileds for Api
        //Relação de Models e Tolkens
        //2048 tolkiens - text-davinci-003, gpt-3.5-turbo
        //4096 tolkiens - gpt-3.5-turbo

        //Temperature Definition
        /* What sampling temperature to use, between 0 and 2. 
        Higher values like 0.8 will make the output more random, 
        while lower values like 0.2 will make it more focused and deterministic. */
       /*  "n": 1,
        "stream": false,
        "logprobs": null,
        "max_tokens": 150, // 2048
        "stop": "\n",
        "stop": [" Human:", " AI:"] 
        "messages": [{"role": "user", "name": "user123456", "content": "'. strval(trim(filter_var(strip_tags($prompt), FILTER_DEFAULT))) .'"}],
        */

        //List Messages Save in the DB
        //$messages = $this->getAllMessage();

        //dd($messages);

        $postFileds = [
            'model' => $this->model,
            'messages' => $this->getAllMessage(),
            'temperature' => 0.9,
            'max_tokens' => 150,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.6,
            'user' => "user123456",
            "stop" => [" user:", " assistant:"] 
        ];

        //dd(json_encode($postFileds));

        return json_encode($postFileds);


    }

    /**
     * Return a loop of messages for Conversation ChatGpt
     *
     * @return array
     */
    protected function getAllMessage():array
    {

        //Get Total Rows - Check if the table is empty
       $db = new DB();
       $query = "SELECT COUNT(id) AS 'totalrows' FROM `chatmessages`";
       $totalRows = $db::select($query);

       if($totalRows[0]->totalrows > 0){

            $result = $db::select("SELECT * FROM `chatmessages`");

            $messagesList = [];

            foreach($result as $value){

                $messagesInfra = new stdClass();
                $messagesInfra->role = $value->role_user;
                $messagesInfra->content = $value->message_user;

                array_push($messagesList, $messagesInfra);

                $messagesInfraAi = new stdClass();
                $messagesInfraAi->role = $value->role_ai;
                $messagesInfraAi->content = $value->message_ai;

                array_push($messagesList, $messagesInfraAi);

            }

            return $messagesList;
          
       }

       //Send Default Message from system
       $messagesInfraDefault = new stdClass();
       $messagesInfraDefault->role = 'system';
       $messagesInfraDefault->content = 'Sou uma Assitente Virtual, me chamo Assistant.';

       array_unshift($this->responseList, $messagesInfraDefault);

       return $this->responseList;

    }

}
