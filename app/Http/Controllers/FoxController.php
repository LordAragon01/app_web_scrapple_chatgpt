<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoxController extends Controller
{
    
    public function index()
    {

        $title = "Web Crawler & Scrapping";

        return view('pages.fox_project', compact(
            'title'
        ));

    }


}
