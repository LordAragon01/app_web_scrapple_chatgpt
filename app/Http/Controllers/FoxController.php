<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

class FoxController extends Controller
{
    //private $url = "https://www.amazon.com/Glass-Whiteboard-Magnetic-Erase-Board/dp/B08RYQS9JL?th=1";
    
    public function index(ApiFoxController $apiFoxController)
    {

        //Set a title
        $title = "Projeto Fox";

        //dd($content);

        return view('pages.fox_project', compact(
            'title'
        ));

    }


}
