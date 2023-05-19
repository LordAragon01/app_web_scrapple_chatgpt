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


    public function indexB2C(PenguinApiController $penguinApi)
    {

        $prevcustomernumber = $penguinApi->getTheLastCustomerId() === null ? 0 : $penguinApi->getTheLastCustomerId();
        $totalcustomerlist = $penguinApi->getTotalCountCustomer();
        $currentcustomerIp = $penguinApi->getIp();

        $getcurrentSelectId = $penguinApi->getSelectCurrentNumber($currentcustomerIp);

        $currentSelectId = !is_null($getcurrentSelectId) ? $getcurrentSelectId->id : 0;

        $convocate_number = $penguinApi->getCallNumber();

        //dd($convocate_number);

        $title = "Penguin B2C";

        return view('pages.penguin_b2c_project', compact(
            'title',
            'prevcustomernumber',
            'totalcustomerlist',
            'currentcustomerIp',
            'currentSelectId',
            'convocate_number'
        ));


    }


}
