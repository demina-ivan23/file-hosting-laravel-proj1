<?php

namespace App\Services;

use Exception;
use App\Models\File;

use App\Models\User;
use App\Models\Message;
use App\Models\UserContactRequest;

class UserContactService
{
    static function storeContact($request)
    {
        $userToAdd = static::findUser($request->id);
        $authUser = static::findUser(auth()->id());

        if (!$authUser->contacts->contains($userToAdd)) {

            if (!$authUser->blacklist->contains($userToAdd) && !$userToAdd->blacklist->contains($userToAdd)) {

                try {
                    $authUser->contacts()->attach($userToAdd->id);
                    $userToAdd->contacts()->attach($authUser->id);
                    $contact_request = UserContactRequest::where([
                        'sender_id' => $userToAdd->id,
                        'receiver_id' => $authUser->id
                    ])->first();
                    if ($contact_request) {
                        $contact_request->delete();
                    }
                    $message_to_receiver = Message::create([
                        'text' => "Your Request To $authUser->email Had Been Accepted. You Are Now Contacts With $authUser->name",
                        'system' => true,
                        'userReceiver' => $userToAdd->id
                    ]);
                    $message_to_sender = Message::create([
                        'text' => "You Successfully Accepted Contact Request Of $userToAdd->email. You Are Now Contacts With $userToAdd->name",
                        'system' => true,
                        'userReceiver' => $authUser->id
                    ]);
                    $userToAdd->messages()->attach($message_to_receiver);
                    $authUser->messages()->attach($message_to_sender);
                    return true;
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            } else {
                abort(403, 'You Have Either Blocked This User Or Have Been Blocked By Them (Or Both). Unblock Them To Accept Their Request Or Ask Them To Unblock You (Depends On Who Blocked Whom)');
            }
        } else {
            abort(403, 'You Already Have This User As A Contact');
        }
    }
    static function getRelatedFiles($id)
    {
        try {
            $contact = static::findUser($id);
            $authUser = static::findUser(auth()->id());
            if (!$contact->contacts->contains($authUser)) {
                abort(403);
            }
            $files = File::where(function ($query) use ($contact) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $contact->id);
            })
                ->orWhere(function ($query) use ($contact) {
                    $query->where('sender_id', $contact->id)
                        ->where('receiver_id', auth()->id());
                })
                ->filter()
                ->latest()
                ->paginate(10);
            if ($files->isEmpty()) {
                return [];
            }
            return $files;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    static function getReceivedFiles($id){
        try {
            $contact = static::findUser($id);
            $authUser = static::findUser(auth()->id());
            if (!$contact->contacts->contains($authUser)) {
                abort(403);
            }
            $files = File::where(function ($query) use ($contact) {
                    $query->where('sender_id', $contact->id)
                        ->where('receiver_id', auth()->id());
                })
                ->filter()
                ->latest()
                ->paginate(10);
            if ($files->isEmpty()) {
                return [];
            }
            return $files;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function getSentFiles($id){
        try {
            $contact = static::findUser($id);
            $authUser = static::findUser(auth()->id());
            if (!$contact->contacts->contains($authUser)) {
                abort(403);
            }
            $files = File::where(function ($query) use ($contact) {
                    $query->where('sender_id', auth()->id())
                        ->where('receiver_id', $contact->id);
                })
                ->filter()
                ->latest()
                ->paginate(10);
            if ($files->isEmpty()) {
                return [];
            }
            return $files;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    static function findUser($id)
    {

        $userToAdd = User::find($id);
        if (!$userToAdd) {
            throw new Exception("User Not Found, Id: {$id}");
        }
        return $userToAdd;
    }
    static function getContacts()
    {
        $authUser = User::find(auth()->id());
        return $authUser->contacts()->filter()->paginate(5);
    }
    static function blockUser($request, $id)
    {
        try {
            $authUser = static::findUser(auth()->id());
            $contact = static::findUser($id);
            if ($request['blocking']) {
                if (!$authUser->blacklist->contains($id)) {
                    $authUser->blacklist()->attach($id);
                    $messageToAuth = Message::create([
                        'text' => "You Have Blocked The User Who's Email is $contact->email",
                        'system' => true,
                        'userReceiver' => $authUser->id
                    ]);
                    $messageToBlockedContact = Message::create([
                        'text' => "You Have Been Blocked By The User Who's Email is $authUser->email. Now You Can't Send Them Contact Requests, Files Or Messages",
                        'system' => true,
                        'userReceiver' => $contact->id
                    ]);
                    $authUser->messages()->attach($messageToAuth);
                    $contact->messages()->attach($messageToBlockedContact);
                    return "You Have Blocked The User {$contact->email}";
                } else {
                    abort(403, 'You Have Already Blocked The User');
                }
            } else if ($request['blocking'] == false) {
                if ($authUser->blacklist->contains($id)) {
                    $authUser->blacklist()->detach($id);
                    $messageToAuth = Message::create([
                        'text' => "You Have Unlocked The User Who's Email is $contact->email",
                        'system' => true,
                        'userReceiver' => $authUser->id
                    ]);
                    $messageToBlockedContact = Message::create([
                        'text' => "You Have Been Unlocked By The User Who's Email is $authUser->email. Now You Can Send Them Contact Requests, Files Or Messages Again",
                        'system' => true,
                        'userReceiver' => $contact->id
                    ]);
                    $authUser->messages()->attach($messageToAuth);
                    $contact->messages()->attach($messageToBlockedContact);
                    return "You Have Unblocked The User {$contact->email}";
                } else {
                    return 'You Haven\'t Blocked The User Yet. So curious :) ?';
                }
            } else {
                abort(403, 'Wierd Action... This Shouldn\'t Appear To You. Pleas Contact The Support');
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function deleteContact($id)
    {
        try {

            $authUser = static::findUser(auth()->id());
            $contactUser = static::findUser($id);
            $authUser->contacts()->detach($id);
            $contactUser->contacts()->detach(auth()->id());
            $messageToAuth = Message::create([
                'text' => "You Have Deleted $contactUser->email. You Are Not Contacts Anymore",
                'system' => true,
                'userReceiver' => $authUser->id
            ]);
            $messageToContact = Message::create([
                'text' => "You Have Been Deleted By $authUser->email. You Are Not Contacts Anymore",
                'system' => true,
                'userReceiver' => $contactUser->id
            ]);
            $authUser->messages()->attach($messageToAuth);
            $contactUser->messages()->attach($messageToContact);
            return 'Contact Deleted Successfully';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
