<?php 

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\UserContact;

class UserContactService{
    static function storeContact($request)
    {      
        try{
            
            $userToAdd = static::findUserToAdd($request->id);    
            if (!$userToAdd) {
                throw new Exception('User not found');
            }
            $authUser = User::find(auth()->user()->id);
           $authUser->contacts()->attach($userToAdd->id);
           $userToAdd->contacts()->attach($authUser->id);
           return true;
        } catch (Exception $e) {
        return $e->getMessage();
       }


    }
    static function findUserToAdd($id)
    {
        $userToAdd = User::find($id);
        if ($userToAdd) {
            return $userToAdd;
        }
    }
}