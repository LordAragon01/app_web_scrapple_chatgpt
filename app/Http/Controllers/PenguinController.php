<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenguinController extends Controller
{
    
    public function indexB2B(PenguinApiController $penguinApi)
    {

        //Conditinal for show the next number to call
        if($penguinApi->getCallNumber() == 0 && $penguinApi->getTotalCountCustomer() == 0){

            $convocate_number = "No clients";
           
        }elseif($penguinApi->getTotalCountCustomer() !== 0 & 
        $penguinApi->getCallNumber() !== $penguinApi->getTotalCountCustomer()){

            $convocate_number = $penguinApi->getCallNumber() == 0 ? 1 : $penguinApi->getCallNumber() + 1;

        }else{

            $convocate_number = "All customers have already been called";

        }
     
        $totalcustomerlist = $penguinApi->getTotalCountCustomer() - $penguinApi->getCallNumber();
        $totalcustomer = !is_null($penguinApi->getTotalCountCustomer()) ? $penguinApi->getTotalCountCustomer() : 0;

        //dd($penguinApi->getCallNumber());

        $title = "Penguin Dashboard";

        return view('pages.penguin_b2b_project', compact(
            'title',
            'totalcustomerlist',
            'convocate_number',
            'totalcustomer'
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
