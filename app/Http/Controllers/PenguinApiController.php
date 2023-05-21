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

            //Delay for process
            usleep(500000);

            //Save Data in DB
            $penguin->counter_number = filter_var(strip_tags($datab2c->counternumber), FILTER_DEFAULT);
            $penguin->ip = filter_var(strip_tags($datab2c->ip), FILTER_DEFAULT);
            $penguin->save();

            //Confirmation Message
            $success = [
                'generateNumber' => true,
            ];

          /*   $success = [
                'generateNumber' => true,
                'lastId' => $this->getTheLastCustomerId(),
                'totaCustomer' => $this->getTotalCountCustomer()
            ]; */

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
    public function getIp():string
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
     * Get All Data from Customer API
     *
     * @return
     */
    public function customerData()
    {

        //Get all data from Customer
        $allDataFromCustomer = $this->getAllDataFromCustomer();

        $customerData = new stdClass();
        $customerData->lastId = !is_null($this->getTheLastCustomerId()) ? $this->getTheLastCustomerId() : 0;
        $customerData->totaCustomer = $this->getTotalCountCustomer();
        $customerData->ip = !is_null($allDataFromCustomer) ? $allDataFromCustomer->ip : null;
        $customerData->call_number = !is_null($allDataFromCustomer) ? $allDataFromCustomer->call_number : null;
        $customerData->created_at = !is_null($allDataFromCustomer) ? $allDataFromCustomer->created_at : null;
        $customerData->allips = !empty($this->getAllIps()) ? $this->getAllIps() : null;

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

            return !empty($result[0]->total_customer) ? $result[0]->total_customer : null ;


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

            return $result[0] ? $result[0]->lastid : null;

        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }


    }

     /**
     * Get all data from Customer
     *
     * @return
     */
    public function getAllDataFromCustomer()
    {

        try{

            //Get Data Confirmarion
            $db = new Db();
            $result = $db::select("SELECT id, ip, call_number, created_at FROM `penguin_customer` WHERE id = " . intval($this->getTheLastCustomerId()));

            return !empty($result) ? $result[0] : null;

        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }


    }

    /**
     * Get all data from Customer
     *
     * @return
     */
    public function getAllIps()
    {

        try{

            //Get All Ips
            $db = new Db();
            $result = $db::select("SELECT ip AS allips FROM `penguin_customer`");

            return !empty($result) ? $result : null;

        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }


    }

    /**
     * Get all data from Customer
     *
     * @return
     */
    public function getSelectCurrentNumber($ip)
    {

        try{

            //Get All Ips
            $db = new Db();
            $result = $db::select("SELECT id, ip, counter_number AS counternumber, call_number, created_at FROM `penguin_customer` WHERE ip = '" . trim($ip) . "' ORDER BY id DESC LIMIT 1") ;

            //dd($result);

            return !empty($result) ? $result[0] : null;

        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }


    }

    /**
     * Get Current Number by select IP
     *
     * @param Request $request
     * @return
     */
    public function getCurrentNumber(Request $request)
    {

        //Get specific data from Customer
        $allDataFromCustomer = $this->getSelectCurrentNumber($request->selectip);

        $dataCurrentNumber = new stdClass();
        $dataCurrentNumber->id = !is_null($allDataFromCustomer) ? $allDataFromCustomer->id : null;
        $dataCurrentNumber->ip = !is_null($allDataFromCustomer) ? $allDataFromCustomer->ip : null;
        //$dataCurrentNumber->counternumber = $allDataFromCustomer->counternumber;
        $dataCurrentNumber->call_number = !is_null($allDataFromCustomer) ? $allDataFromCustomer->call_number : null;
        $dataCurrentNumber->created_at = !is_null($allDataFromCustomer) ? $allDataFromCustomer->created_at : null;

        return response()->json($dataCurrentNumber, 200);

    }


    /**
     * Get the actual Convocate Number
     *
     * @return
     */
    public function getCallNumber(){


        try{


            $db = new DB();
            $result = $db::select("SELECT COUNT(call_number) AS convocate_number FROM `penguin_customer`");

            return !empty($result[0]) ? $result[0]->convocate_number : null;


        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }


    }

    public function callNumber(Request $request){


        try{

            $db = new DB();
            $db::update("UPDATE `penguin_customer` SET `call_number` = 1 WHERE `id` = ". intval($request->callnumber));

            //Confirmation Message
            $success = [
                'callNumber' => true,
            ];

            return response()->json($success, 200);

        }catch(PDOException $exception){

            $errorMessage = $exception->getMessage();

            return $errorMessage;

        }

    }

}
