<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatGptController extends Controller
{
    
    public function index()
    {

        $title = "ChatGpt Custom";

        return view('pages.raven_project', compact(
            'title'
        ));

    }


}
