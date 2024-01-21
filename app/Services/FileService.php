<?php

namespace App\Services;

use Exception;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class FileService{

    static function getAllFiles()
    {
        $files = File::where(function ($query) {
            $query->where('sender_id', auth()->id());
        })
        ->orWhere(function ($query) {
            $query->where('receiver_id', auth()->id());
        })
        ->filter() 
        ->latest()
        ->get();
        return $files;
    }

    static function createFiles($id){
        $userToSendTo = static::findUser($id);
        $userSending = static::findUser(auth()->id());
        if(Gate::allows('start-chat', [$userToSendTo, $userSending])){
           return view('admin.files.create', ['contact_user' => $userToSendTo]);
        }
        else{
           abort(403, "You Are Not Allowed To Send Anything To This User. This May Be Due To Several Reasons. 1 - You May Be Blocked By This User. 2 - This User May Not Have You As A Contact. 3 - You May Have Blocked This User As Well.");
        }
    }

    static function sendFile($request, $id){
        $authUser = static::findUser(auth()->id());
        $data = $request->all();
        $userReciever = static::findUser($id);
        if($request->hasFile('files'))
        {
            $fileloader = new FileUploadService();
            $path = $fileloader->UploadFile($data['files'][0]);
            $file = File::create([
                'path' => $path,
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'],
                'sender_id' => $authUser->id,
                'receiver_id' => $userReciever->id
            ]);
            $authUser->sentFiles()->attach($file, ['userReceiver' => $userReciever->id]);

            return redirect()->route('admin.contacts.dashboard')->with('success', 'File Uploaded Successfully');
        }
    }
    static function findUser($id)
    {
        $user = User::find($id);
        if(!$user){
            throw new Exception("User Not Found, Id: {$id}");
        }
        return $user;
    }

}