<?php 

namespace App\Services;

use Exception;
use App\Models\User;

use App\Models\Message;
use App\Models\UserContactRequest;

class UserContactService{
    static function storeContact($request)
    {     
        $userToAdd = static::findUserToAdd($request->id);    
        $authUser = User::find(auth()->user()->id);
        
        if (!$userToAdd) {
            throw new Exception('User not found');
        }
        if(!$authUser->contacts->contains($userToAdd)){

            try{
                $authUser->contacts()->attach($userToAdd->id);
                $userToAdd->contacts()->attach($authUser->id);
                $contact_request = UserContactRequest::where([
                    'sender_id' => $userToAdd->id,
                    'receiver_id' => $authUser->id
                ])->first();
                if($contact_request){
                    $contact_request->delete();
                }
                $message_to_receiver = Message::create([
                    'text' => "Your Request To $authUser->email Had Been Accepted. You Are Now Contacts With $authUser->name",
                    'userReceiver' => $userToAdd->id
                ]);
                $message_to_sender = Message::create([
                    'text' => "You Successfully Accepted Contact Request Of $userToAdd->email. You Are Now Contacts With $userToAdd->name",
                    'userReceiver' => $authUser->id
                ]);
        $userToAdd->messages()->attach($message_to_receiver);
        $authUser->messages()->attach($message_to_sender);
                return true;
            } catch (Exception $e) {
                return $e->getMessage();
            }
            
        }
        else{
            abort(403, 'You Already Have This User As A Contact');
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