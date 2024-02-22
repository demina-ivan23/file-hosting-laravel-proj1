<?php

namespace App\Services;

use Exception;
use App\Models\File;
use App\Models\Message;
use App\Models\GlobalFile;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FileService
{

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
            ->paginate(5);
        return $files;
    }
  

    static function createFiles($publicId)
    {
        $userToSendTo = UserService::findUserByPublicId($publicId);
        $userSending = UserService::findUser(auth()->id());
        if (Gate::allows('start-chat', [$userToSendTo, $userSending])) {
            return $userToSendTo;
        } else {
            abort(403, "You Are Not Allowed To Send Anything To This User. This May Be Due To Several Reasons. 1 - You May Be Blocked By This User. 2 - This User May Not Have You As A Contact. 3 - You May Have Blocked This User As Well.");
        }
    }

    static function sendFile($request, $id)
    {
        try {
            $authUser = UserService::findUser(auth()->id());
            $data = $request->all();
            $userReciever = UserService::findUser($id);
            if ($request->hasFile('files')) {
                $fileloader = new FileUploadService();
                $path = $fileloader->UploadFile($data['files'][0]);
                $file = File::create([
                    'path' => $path,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'state' => 'active',
                    'sender_id' => $authUser->id,
                    'receiver_id' => $userReciever->id
                ]);
                $authUser->sentFiles()->attach($file, ['userReceiver' => $userReciever->id]);
                //Notice: make messages for each sent/received file here
                return 'File Uploaded Successfully';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function sendFileViaPlupload($request, $id)
    {
        try{
            $authUser = UserService::findUser(auth()->id());
            $userReciever = UserService::findUserByPublicId($id);
                $path = $request['title'];
                $file = File::create([
                    'path' => $path,
                    // 'title' => $data['title'],
                    // 'description' => $data['description'],
                    // 'category' => $data['category'],
                    'state' => 'active',
                    'sender_id' => $authUser->id,
                    'receiver_id' => $userReciever->id
                ]);
                $authUser->sentFiles()->attach($file, ['userReceiver' => $userReciever->id]);
                return 'File Uploaded Successfully';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
   
    
    static function getPath($id)
    {
        $authUser = UserService::findUser(auth()->id());
        if ($authUser->sentFiles->contains($id) || $authUser->receivedFiles->contains($id)) {
            $file = File::find($id);
            $file_path = public_path('storage\\' . $file->path);
            $file_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file_path);
            return $file_path;
        }
    }
    static function getPathByPubId($publicId)
    {
        try {

            $file = GlobalFile::where('publicId', $publicId)->first();
            $file_path = public_path('storage\\' . $file->path);
            $file_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file_path);
            return $file_path;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function fileExists($path)
    {
        if (Storage::exists($path)) {
            return true;
        } else {
            return false;
        }
    }
    static function deleteFile($id)
    {
        $file = File::find($id);
        if (!$file) {
            abort(404);
        }
        $receiver = $file->receiver;
        $sender = $file->sender;
        if ($file->sender->id === auth()->id() xor $file->receiver->id === auth()->id()) {
            $fileloader = new FileUploadService();
            $fileloader->deleteFile(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file->path));
            $file->delete();


            if ($file->sender->id === auth()->id() && $file->receiver->id !== auth()->id()) {

                $message_to_sender = Message::create([
                    'text' => "You Deleted A File That Was Sent To $receiver->email By You",
                    'system' => true,
                    'userReceiver' => $sender->id
                ]);
                $message_to_receiver = Message::create([
                    'text' => "User $sender->email Have Deleted The File They Sent You",
                    'system' => true,
                    'userReceiver' => $receiver->id
                ]);
            }

            if ($file->receiver->id === auth()->id() && $file->sender->id !== auth()->id()) {

                $message_to_sender = Message::create([
                    'text' => "User $receiver->email Have Deleted A File That Was Sent To Them By You",
                    'system' => true,
                    'userReceiver' => $sender->id
                ]);
                $message_to_receiver = Message::create([
                    'text' => "You Have Deleted The File That Was Sent To You By $sender->email",
                    'system' => true,
                    'userReceiver' => $receiver->id
                ]);
            }
            $receiver->messages()->attach($message_to_receiver);
            $sender->messages()->attach($message_to_sender);
            return 'File Deleted Successfully';
        }
        //Notice: make the PersonalFileService and 
        //PersonalFilesController take care of this part
        // else if($file->receiver->id === auth()->id() && $file->sender->id === auth()->id())
        // {

        //     $fileloader = new FileUploadService();
        //     $fileloader->deleteFile(str_replace( ['/', '\\'] ,DIRECTORY_SEPARATOR , $file->path));
        //     $file->delete();
        //     $message_to_yourself = Message::create([
        //         'text' => 'You Have Deleted Your Personal File',
        //         'system' => true,
        //         'userReceiver' => $sender->id
        //     ]);
        // $sender->messages()->attach($message_to_yourself);
        // return redirect(route('admin.files.personal.index'))->with('success', 'File Deleted Successfully');

        // }

        else {
            return 'You Are Neither File\'s Sender Nor It\'s Receiver. But You Tried :)';
        }
    }
}
