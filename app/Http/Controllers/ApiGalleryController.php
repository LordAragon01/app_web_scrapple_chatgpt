<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Criar estrutura de parametros para Default API
 * 
 * @author Joene Galdeano
 */

class ApiGalleryController
{
    
    protected $url;

    public function __construct(){

        $this->url = $this->getUrlApi();

    }

    /**
     * Get Url API
     *
     * @return string|null
     */
    public function getUrlApi():?string
    {

        return $this->url;

    }

    /**
     * Set Url API
     *
     * @param string $urlapi
     * @return void
     */
    public function setUrlApi(string $urlapi){

        $this->url = $urlapi;

    }

    /**
     * Check if the URL is validate
     *
     * @param string $urlapi
     * @return boolean
     */
    public function checkUrl(string $urlapi):bool
    {

        if(filter_var($urlapi, FILTER_VALIDATE_URL)){

            return true;

        }else{

            return false;

        }

    }

    /**
     * Verify if the url contain https
     *
     * @param string $urlapi
     * @return boolean
     */
    public function httpsVerify(string $urlapi):bool
    {

        return parse_url($urlapi, PHP_URL_SCHEME) === 'https' ? true : false;

    }

    /**
     * Set Method Param for API
     *
     * @param boolean $checkurlapi
     * @param string $method
     * @return string
     */
    public function setApiMethod(bool $checkurlapi, string $method):string
    {

        return $checkurlapi === true ? "?method=". $method : "";

    }

    /**
     * Set Api Key Param for API
     *
     * @param boolean $checkurlapi
     * @param string $apikey
     * @return string
     */
    public function setApiKey(bool $checkurlapi, string $apikey):string
    {

        return $checkurlapi === true ? "&api_key=". $apikey : "";

    }

    /**
     * Set Tags Param for API
     *
     * @param boolean $checkurlapi
     * @param string $tags
     * @return string
     */
    public function setTags(bool $checkurlapi, string $tags):string
    {

        return $checkurlapi === true ? "&tags=". $tags : "";

    }

    /**
     * Set Page Param for API
     *
     * @param boolean $checkurlapi
     * @param integer $page
     * @return string
     */
    public function setPage(bool $checkurlapi, int $page):string
    {

        return $checkurlapi === true ? "&page=". strval($page) : "";

    }

    /**
     * Set Format Param for API
     *
     * @param boolean $checkurlapi
     * @param string $format
     * @return string
     */
    public function setFormat(bool $checkurlapi, string $format):string
    {

        return $checkurlapi === true ? "&format=". $format : "";

    }

    /**
     * Set Callback Param for API
     *
     * @param boolean $checkurlapi
     * @param integer $callback
     * @return string
     */
    public function setCallback(bool $checkurlapi, int $callback):string
    {

        return $checkurlapi === true ? "&nojsoncallback=". strval($callback) : "";

    }

    /**
     * Set Privacy Filter Param for API
     *
     * @param boolean $checkurlapi
     * @param integer|null $privacy
     * @return string|null
     */
    public function setPrivacyFilter(bool $checkurlapi, ?int $privacy):string|null
    {

        return $checkurlapi === true ? "&privacy_filter=". strval($privacy) : null;

    }

    /**
     * Inform especific params for URL API
     *
     * @param array $list_of_params
     * @return string
     */
    public function getParamsUrlApi(array $list_of_params):string
    {

        return http_build_query($list_of_params);

    }


}
