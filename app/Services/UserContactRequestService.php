<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Message;
use App\Services\UserService;
use App\Models\UserContactRequest;

class UserContactRequestService
{
    static function getAllRequests($id)
    {
        try {

            $authUser = UserService::findUser($id);
            $requests = UserContactRequest::where(['sender_id' => auth()->id()])
                ->orWhere(['receiver_id' => $authUser->id])->get();
            return $requests;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    static function sendRequest($request)
    {
        try {
            $data = $request->all();
            $receiver = UserService::findUserByPublicId($data['publicId']);
            $sender = UserService::findUser(auth()->id());
            $contact_request = UserContactRequest::where([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id
            ])->first();
            if ($contact_request) {
                return 'You Have Already Sent A Request To This User';
            }
            if ($sender->id === $receiver->id) {
                return 'You Are Already In Your Contact List :) XD';
            }
            if ($receiver->contacts->contains($sender->id)) {
                return 'You Are Already In The User\'s Contact List';
            }
            if ($receiver->blacklist->contains($sender->id) || $sender->blacklist->contains($receiver->id)) {
                return 'You Have Either Blocked This User Or Have Been Blocked By Them (Or Both).
                Unblock Them To Send A Request Or Ask Them To Unblock You (Depends On Who Blocked Whom)';
            }
            $contact_request = UserContactRequest::where([
                'sender_id' => $receiver->id,
                'receiver_id' => $sender->id
            ])->first();
            if ($contact_request) {
                return 'This Person Have Already Sent You The Request. Check Your Requests Page';
            }
            $contact_request = UserContactRequest::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id
            ]);
            $message_to_receiver = Message::create([
                'text' => "User With Email $sender->email Have Sent You A Contact Request. Check Your Requests Page",
                'system' => true,
                'userReceiver' => $receiver->id
            ]);
            $message_to_sender = Message::create([
                'text' => "You Sent A Contact Request To User With Id Of $receiver->publicId",
                'system' => true,
                'userReceiver' => $sender->id
            ]);
            $receiver->messages()->attach($message_to_receiver);
            $sender->messages()->attach($message_to_sender);
            return 'Your Contact Request Was Sent Successfully';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    static function deleteRequest($publicId, $state)
    {
        try {
            $contact_request = static::findRequest($publicId);
            if ($state === 'canceled') {
                $message_to_sender = Message::create([
                    'text' => "You Have Canceled The Request To The User With Id Of {$contact_request->receiver->id}",
                    'system' => true,
                    'userReceiver' => $contact_request->sender->id
                ]);
                $sender = UserService::findUser($contact_request->sender->id);
                $sender->messages()->attach($message_to_sender);
                $contact_request->delete();
                return 'Request Canceled Successfully';
            }
            if ($state === 'declined') {
                $message_to_sender = Message::create([
                    'text' => "Your Request Was Denied By The User With Id Of {$contact_request->receiver->publicId}",
                    'system' => true,
                    'userReceiver' => $contact_request->sender->id
                ]);
                $message_to_receiver = Message::create([
                    'text' => "You Have Denied The Request Of {$contact_request->sender->email}",
                    'system' => true,
                    'userReceiver' => $contact_request->receiver->id
                ]);
                $sender = UserService::findUser($contact_request->sender->id);
                $sender->messages()->attach($message_to_sender);
                $receiver = UserService::findUser($contact_request->receiver->id);
                $receiver->messages()->attach($message_to_receiver);
                $contact_request->delete();
                return 'Request Declined Successfully';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function findRequest($publicId)
    {
        $request = UserContactRequest::where('publicId', $publicId)->first();
        if (!$request) {
            abort(404);
        }
        return $request;
    }
    static function deleteRequestAndBlockUser($publicId, $state)
    {
        try {
            $contact_request = static::findRequest($publicId);
            $authUser = UserService::findUser(auth()->id());
            if ($state === 'canceled') {
                return 'You Can\'t Block Yourself';
            }
            if($state === 'declined')
            {
                if(!$authUser->blacklist->contains($contact_request->sender->id))
                {
                    $authUser->blacklist()->attach($contact_request->sender->id);
                }
                $sender = UserService::findUser($contact_request->sender->id);
                $message_to_sender = Message::create([
                    'text' => "You Was Blocked By The User With Id Of {$contact_request->receiver->publicId}",
                    'system' => true,
                    'userReceiver' => $contact_request->sender->id
                ]);
                $sender->messages()->attach($message_to_sender);
                $result = static::deleteRequest($publicId, $state);
                return "{$result}; User Blocked Successfully";
            }
            else {
                return 'Unidentified State';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
