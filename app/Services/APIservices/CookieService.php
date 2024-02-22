<?php

namespace App\Services\APIservices;

use Exception;

use App\Models\CanvasCookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Contracts\Encryption\Encrypter;

class CookieService {
    static function cookieExists($cookieKey, $cookieVal){
        $exists = CanvasCookie::where($cookieKey, $cookieVal)->exists();
        return $exists;
    }
    static function createCookie($cookieKey, $cookieVal){
        try{
        $cookie = Cookie::make($cookieKey, $cookieVal, 14400)->withHttpOnly(false); // You can specify the expiration time if needed
        $response = response('Cookie set')->withCookie($cookie);
        if(!static::cookieExists($cookieKey, $cookieVal))
        {
            $cookie = CanvasCookie::create([
                'canvasId' => $cookieVal,
                'userId' => auth()->id()
            ]);
        }
        return $response;
        } catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    static function updateCookie($cookieKey, $cookieVal){
        try{
        if(static::cookieExists($cookieKey, $cookieVal))
        {
            $cookie = CanvasCookie::where('canvasId', $cookieVal)->first();
            if(auth()->id() !== null && $cookie->userId == null)
            {
                $cookie->update(['userId' => auth()->id()]);
                return 'Cookie updated successfully';
            }
            else {
                return 'User is still unregistered or User Id is not null anymore';
            }
        }
        return "Cookie doesn't exist";
        } catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
}