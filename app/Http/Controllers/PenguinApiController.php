<?php

namespace App\Http\Controllers;

use App\Models\Penguin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;
use stdClass;

class PenguinApiController extends Controller
{
    
    public function generateNumber(Request $request, Penguin $penguin)
    {

        //Data Structure
        $datab2c = new stdClass();
        $datab2c->counternumber = intval($request->sendnumber);
        $datab2c->ip = $this->getIp();

        try{

            //Save Data in DB
            $penguin->counter_number = filter_var(strip_tags($datab2c->counternumber), FILTER_DEFAULT);
            $penguin->ip = filter_var(strip_tags($datab2c->ip), FILTER_DEFAULT);
            $penguin->save();

            //Confirmation Message
            $success = [
                'generateNumber' => true
            ];

            return response()->json($success, 200);


        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return response()->json($errorMessage, 500);

        }

    }

    /**
     * Get Current IP from user
     *
     * @return string
     */
    private function getIp():string
    {

        //whether ip is from the share internet  
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
        //whether ip is from the proxy  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  
        //whether ip is from the remote address  
        else{  
            $ip = $_SERVER['REMOTE_ADDR'];  
        }  

        return $ip;  

    }

    /**
     * Get All Data from Customer
     *
     * @return
     */
    public function customerData()
    {

        $customerData = new stdClass();
        $customerData->lastId = $this->getTheLastCustomerId();
        $customerData->totaCustomer = $this->getTotalCountCustomer();

        return response()->json($customerData, 200);

    }

    /**
     * Get Total of Customer Insert
     *
     * @return
     */
    public function getTotalCountCustomer()
    {

        try{

            //Get Data Confirmarion
            $db = new DB();
            $result = $db::select("SELECT COUNT(id) AS total_customer FROM `penguin_customer`");

            return $result[0]->total_customer;


        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }


    }

    /**
     * Get the Last Insert Id
     *
     * @return
     */
    public function getTheLastCustomerId()
    {

        try{

            //Get Data Confirmarion
            $db = new Db();
            $result = $db::select("SELECT MAX(id) AS lastid FROM `penguin_customer`");

            return $result[0]->lastid;

        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }


    }

}
