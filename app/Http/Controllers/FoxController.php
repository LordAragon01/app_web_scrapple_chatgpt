<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;

/**
 * 
 * Class for CRUD structure
 * 
 * @author Joene Galdeano <joene.goncalves@bibright.com>
 * @since Fox Project 1.0.0
 */
class FoxController extends Controller
{
    //private $url = "https://www.worten.pt/escritorio-e-papelaria/material-de-apresentacao/quadros-e-ardosias/quadro-branco-bi-office-pinho-60x45cm-MRKEAN-5603750304005";
    
    public function index(ApiFoxController $apiFoxController)
    {

        //Set a title
        $title = "Projeto Fox";

        //$verifyintegrate = $apiFoxController->getHostName("https://www.staples.com/tru-red-melamine-dry-erase-board-black-frame-6-x-4-tr59365/product_24534067");

        //dd($verifyintegrate);
        //$worten = new WortenFoxController();
        //$content = $worten->getContentFromUrl($this->url, true, true, null);

        //dd($content);

        return view('pages.fox_project', compact(
            'title'
        ));

    }


}
