<?php

namespace App\Http\Controllers;


Interface InterfaceFoxController
{
    
    /**
    * Indicate URL and Get Clean Content
    *
    * @param string $url
    * @param boolean $validateurl
    * @param boolean $validatehttps
    * @return object|string
    */
    public function getContentFromUrl(
        string $url,
        ?bool $validateurl,
        ?bool $validatehttps,
        ?string $indicate
        ):object|string;

    /**
     * Filter Data and Create Array with select data
     *
     * @param array $list_of_data
     * @param string $type_of_data
     * @return array
     */
    public function filterData(array $list_of_data, string $type_of_data):array|string|int;    


}
