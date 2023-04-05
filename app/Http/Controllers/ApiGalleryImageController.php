<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Criar estrutura de parametros para Image API
 * 
 * @author Joene Galdeano
 */

class ApiGalleryImageController extends ApiGalleryController
{
    /**
     * Set Photo Id Param for API Image
     *
     * @param boolean $checkurlapi
     * @param integer $photo_id
     * @return string|null
     */
    public function setPhotoId(bool $checkurlapi, int $photo_id):string|null
    {

        return $checkurlapi === true ? "&photo_id=". strval($photo_id) : null;

    }


}
