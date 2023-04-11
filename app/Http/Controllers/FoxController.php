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

        //$verifyintegrate = $apiFoxController->getHostName("https://www.staples.com/tru-red-melamine-dry-erase-board-black-frame-6-x-4-tr59365/product_24534067");

        //dd($verifyintegrate);

        return view('pages.fox_project', compact(
            'title'
        ));

    }


}
