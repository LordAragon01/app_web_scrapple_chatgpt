<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenguinController extends Controller
{
    
    public function indexB2B()
    {

        $title = "Penguin Dashboard";

        return view('pages.penguin_b2b_project', compact(
            'title'
        ));


    }


    public function indexB2C()
    {

        $title = "Penguin B2C";

        return view('pages.penguin_b2c_project', compact(
            'title'
        ));


    }


}
