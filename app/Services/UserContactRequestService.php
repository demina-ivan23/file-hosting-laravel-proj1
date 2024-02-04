<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Message;
use App\Models\UserContactRequest;

class UserContactRequestService
{
    static function getAllRequests($publicId)
    {
        try {
            $authUser = static::findUser($publicId);
            $requests = UserContactRequest::where(['sender_id' => $authUser->publicId])
                ->orWhere(['receiver_id' => $authUser->publicId])->get();
            return $requests;
        } catch (Exception $e) {
            throw new Exception("{$e->getMessage()}");
        }
    }
    static function findUser($publicId){
        $user = User::where('publicId', $publicId)->first();
        if(!$user){
            abort(404);
        }
        return $user;
    }
    static function sendRequest($request)
    {
        try{

            $data = $request->all();
            $receiver = static::findUser($data['publicId']);
            $sender = static::findUser(auth()->user()->publicId);
        $contact_request = UserContactRequest::where([
            'sender_id' => $sender->publicId,
            'receiver_id' => $receiver->publicId
            ])->first();
            if ($contact_request) {
                return 'You Have Already Sent A Request To This User';
            }
            if ($sender->publicId === $receiver->publicId) {
                return 'You Are Already In Your Contact List :) XD';
            }
            if ($receiver->contacts->contains($sender->pulicId)) {
                return 'You Are Already In The User\'s Contact List';
            }
            if ($receiver->blacklist->contains($sender->publicId) || $sender->blacklist->contains($receiver->publicId)) {
                return 'You Have Either Blocked This User Or Have Been Blocked By Them (Or Both).
                Unblock Them To Send A Request Or Ask Them To Unblock You (Depends On Who Blocked Whom)';
            }
            $contact_request = UserContactRequest::where([
                'sender_id' => $receiver->publicId,
                'receiver_id' => $sender->publicId
                ])->first();
        if ($contact_request) {
            return 'This Person Have Already Sent You The Request. Check Your Requests Page';
        }
        $contact_request = UserContactRequest::create([
            'sender_id' => $sender->publicId,
            'receiver_id' => $receiver->publicId
        ]);
        $message_to_receiver = Message::create([
            'text' => "User With Email $sender->email Have Sent You A Contact Request. Check Your Requests Page",
            'system' => true,
            'userReceiver' => $receiver->publicId
        ]);
        $message_to_sender = Message::create([
            'text' => "You Sent A Contact Request To User With Id Of $receiver->publicId",
            'system' => true,
            'userReceiver' => $sender->publicId
        ]);
        $receiver->messages()->attach($message_to_receiver);
        $sender->messages()->attach($message_to_sender);
        return 'Your Contact Request Was Sent Successfully';
    } catch (Exception $e)
    {
        return $e->getMessage();
    }
    }

    static function deleteRequest($id, $state){
        try{
            $contact_request = static::findRequest($id);
            if($state === 'canceled'){
                $message_to_sender = Message::create([
                    'text' => "You Have Canceled The Request To The User With Id Of {$contact_request->receiver->id}",
                    'system' => true,
                    'userReceiver' => $contact_request->sender->publicId  
                ]);
                $sender = static::findUser($contact_request->sender->publicId);
                $sender->messages()->attach($message_to_sender);
                $contact_request->delete();
                return 'Request Canceled Successfully';
            }
            if($state === 'declined'){
                $message_to_sender = Message::create([
                    'text' => "Your Request Was Denied By The User With Id Of {$contact_request->receiver->id}",
                    'system' => true,
                    'userReceiver' => $contact_request->sender->publicId  
                ]);
                $message_to_receiver = Message::create([
                    'text' => "You Have Denied The Request Of {$contact_request->sender->email}",
                    'system' => true,
                    'userReceiver' => $contact_request->receiver->publicId  
                ]);
                $sender = static::findUser($contact_request->sender->publicId);
                $sender->messages()->attach($message_to_sender);
                $receiver = static::findUser($contact_request->receiver->publicId);
                $receiver->messages()->attach($message_to_receiver);
                $contact_request->delete();
                return 'Request Declined Successfully';
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
    static function findRequest($id){
        $request = UserContactRequest::find($id);
        if(!$request)
        {
            abort(404);
        }
        return $request;
    }
}
