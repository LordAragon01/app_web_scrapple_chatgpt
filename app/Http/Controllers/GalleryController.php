<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PDOException;
use stdClass;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Consumo de API e insert na base de dados
 * 
 * @author Joene Galdeano
 */

class GalleryController extends Controller
{

    private $urlapiaddress = "https://api.flickr.com/services/rest/";
    private $urlapiaddressimage = "https://www.flickr.com/services/rest/";
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DB $db)
    {

        //Page Title
        $title = "Galeria de Imagens";

        //Check table rows
        $rows = $db::select("SELECT COUNT('id') AS total_row FROM images");

        //Check API URL
        $check_apiurl = $this->getValidateApiUrl($this->urlapiaddress);

        //Get list of image
        $image_list = $db::select("SELECT * FROM images");

        //$db::table("images")->truncate();

         //Validate Sequence for Api Call
         if($rows[0]->total_row === 0){

            //Url Api
            $url_api = $this->getUrlApi($check_apiurl);

            //Get Api Data
            $api_data = $this->getApiData($url_api, "photos");

            switch(gettype($api_data)){

                case 'array':
                            
                    //Get Max inform from Data
                    $all_api_data = $this->getMaxApiData($api_data);

                    //Insert data in the table
                    $checkinsert = $this->store($all_api_data);

                    //Get data from table
                    $image_list = $db::select("SELECT * FROM images");

                break;
                
                case 'string':

                    //Get Message Error From Api
                    $image_list = $api_data;

                break;

            }

            //dd($api_data);
            //dd($rows[0]->total_row);
            //dd($image_list);
                        
            return view("pages.home", compact(
                'title',
                'check_apiurl',
                'image_list'
            ));

        }else{

            //dd($image_list);
                        
            return view("pages.home", compact(
                'title',
                'check_apiurl',
                'image_list'
            ));

        }

    }

    /**
     * Check the Api Url is validate
     *
     * @param string $urlapi
     * @return boolean
     */
    public function getValidateApiUrl(string $urlapi):bool
    {

        $url = new ApiGalleryController();

        //URL API construct
        $url->setUrlApi($urlapi);
        $url_api = $url->getUrlApi();

        //URL API validate
        $checkurl = $url->checkUrl($url_api);
        $checkhttps = $url->httpsVerify($url_api);

        $validate_url = $checkurl && $checkhttps === true ? true : false;

        return $validate_url;

    }

    /**
     * Structure Url API Default
     *
     * @param boolean $check_apiurl
     * @return string
     */
    private function getUrlApi(bool $check_apiurl):string
    {

        $url = new ApiGalleryController();

        //Get API Url method
        $apimethod = $url->setApiMethod($check_apiurl, "flickr.photos.search");

        //Get API Key
        $apikey = $url->setApiKey($check_apiurl, config('app.flickr_key'));

        //Get Tags
        //$apitags = $url->setTags($check_apiurl, "kitten");

        //Get Page
        //$apipage = $url->setPage($check_apiurl, 1);

        //Get Format
        //$apiformat = $url->setFormat($check_apiurl, "json");

        //Get Callback
        //$apicallback = $url->setCallback($check_apiurl, 1);

        //Get Privacy Filter
        //$privacyfilter = $url->setPrivacyFilter($check_apiurl, 1);
        //$apiprivacyfilter = $privacyfilter !== null ? $privacyfilter : "";

        //API URL 
        //$apiurl = $this->urlapiaddress . $apimethod . $apikey . $apitags . $apipage . $apiformat . $apicallback . $apiprivacyfilter;


        //Add Spcefics Params
        $list_of_params = [
            'tags' => 'kitten',
            'page' => 1,
            'format' => 'json',
            'nojsoncallback' => 1,
            'privacy_filter' => 1
        ];

        $apiurl = $this->urlapiaddress . $apimethod . $apikey . '&' . $url->getParamsUrlApi($list_of_params) ;

        return $apiurl;

    }

    /**
     * Structure Url API for get list of images and sizes
     *
     * @param boolean $check_apiurl
     * @param integer $id_image
     * @return string
     */
    private function getImageUrlApi(bool $check_apiurl, int $id_image):string
    {

        $url = new ApiGalleryController();
        $image = new ApiGalleryImageController();

        //Get API Url method
        $apimethod = $url->setApiMethod($check_apiurl, "flickr.photos.getSizes");

        //Get API Key
        $apikey = $url->setApiKey($check_apiurl, config('app.flickr_key'));

       //Get Photo Id
       $photo_id = $image->setPhotoId($check_apiurl, $id_image);

        //Get Format
        $apiformat = $url->setFormat($check_apiurl, "json");

        //Get Callback
        $apicallback = $url->setCallback($check_apiurl, 1);

        //API URL 
        $apiurl = $this->urlapiaddressimage . $apimethod . $apikey . $photo_id . $apiformat . $apicallback;

        return $apiurl;

    }

    /**
     * Get data from urlapi indicate
     *
     * @param string $apiurl
     * @param string $indicate_key
     * @return array|boolean
     */
    public function getApiData(string $apiurl, string $indicate_key):array|string
    {

        $response = Http::timeout(5)->
                    acceptJson()->
                    get($apiurl); 
                    
        //Add callback function for API Request
        if($response->successful() && $response->json('stat') === "ok"){

            return $response->json($indicate_key);

        }elseif($response->successful() && $response->json('stat') === "fail"){

            return $response->json('message');

        }else{

            if($response->failed()){

                abort(404, "Erro ao se Conectar");

            }

        }         

    }

    /**
     * Merge urlapi with list of images and get data
     *
     * @param array $apidata
     * @param integer $max_data
     * @return array
     */
    public function getMaxApiData(array $apidata, int $max_data = 10):array
    {
        //Check Image API URL 
        $check_apiurl_image = $this->getValidateApiUrl($this->urlapiaddressimage);


        $list_of_photos = $apidata['photo'];
        $array_key = array_rand($list_of_photos, $max_data);

        $dynamic_list = [
            "image" => [
                "id" => [],
                "title" => [],
                "image_info" => []
            ]
        ];

        foreach($list_of_photos as $index => $value){

            if(in_array($index, $array_key)){

                //Get Image from Api
                $url_api_image = $this->getImageUrlApi($check_apiurl_image, $value['id']);

                //Get Api Data
                $api_data_image = $this->getApiData($url_api_image, "sizes");

                //dd($api_data_image["size"][5]["source"]);

                array_push($dynamic_list["image"]["id"], $value['id']);
                array_push($dynamic_list["image"]["title"], $value['title']);
                array_push($dynamic_list["image"]["image_info"], $api_data_image["size"][5]["source"]);

            }

        }

        return $dynamic_list;

    }

    /**
     * Insert data in the Images Table
     *
     * @param array $list_of_images
     * @return boolean|string
     */
    public function store(array $list_of_images):bool|string
    {

        try{

            $data_for_insert = new stdClass();

            //Separate data for table column
            foreach($list_of_images as $value){

                $data_for_insert->id = array_values($value["id"]);
                $data_for_insert->title = array_values($value["title"]);
                $data_for_insert->source = array_values($value["image_info"]);

            }

            //Insert data in the table
            foreach($data_for_insert->id as $idimage_value){

                    $image_id = filter_var($idimage_value, FILTER_DEFAULT);

                foreach($data_for_insert->title as $title_value){

                    $title = filter_var($title_value, FILTER_DEFAULT);

                    foreach($data_for_insert->source as $source_value){

                        $source = filter_var($source_value, FILTER_DEFAULT);

                    }

                }

                $db = new DB();

                $db::insert("INSERT INTO images('image_id', 'title', 'source') VALUES ('". intval($image_id) ."', '". strval($title) ."', '". strval($source) ."')");

            }

            return true;

        }catch(PDOException $exception){

            return $exception->getMessage();

        }

    }
 
}
