<?php

namespace App\Services\APIservices;

use Exception;
use App\Models\Cookie;

class CookieService {
    static function checkIfCookieExists($imageData){
        $exists = Cookie::where('image_data', $imageData)->exists();
        return $exists;
    }
    static function createCookie($imageData){
        try{
        $cookie = Cookie::create(['image_data' => $imageData]);
        if(!$cookie)
        {
            return "Cookie could not be created. This is all we know";
        }
        return "Cookie saved successfully";
        } catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
}