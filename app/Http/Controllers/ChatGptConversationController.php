<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatGptConversationController extends ChatGptController
{
    

    public function index()
    {

        $title = "ChatGpt Chat";

        return view('pages.raven_project_chat', compact(
            'title'
        ));

    }


}
