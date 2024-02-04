<?php

namespace App\Services;

use Exception;
use App\Models\File;
use App\Models\User;
use App\Models\Message;
use App\Models\GlobalFile;
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
    static function getSentFiles()
    {
        $files = File::where(function ($query) {
            $query->where('sender_id', auth()->id());
        })
            ->filter()
            ->latest()
            ->paginate(10);
        return $files;
    }
    static function getReceivedFiles()
    {
        $files = File::where(function ($query) {
            $query->where('receiver_id', auth()->id());
        })
            ->filter()
            ->latest()
            ->paginate(10);
        return $files;
    }

    static function createFiles($id)
    {
        $userToSendTo = static::findUser($id);
        $userSending = static::findUser(auth()->id());
        if (Gate::allows('start-chat', [$userToSendTo, $userSending])) {
            return $userToSendTo;
        } else {
            abort(403, "You Are Not Allowed To Send Anything To This User. This May Be Due To Several Reasons. 1 - You May Be Blocked By This User. 2 - This User May Not Have You As A Contact. 3 - You May Have Blocked This User As Well.");
        }
    }

    static function sendFile($request, $publicId)
    {
        try {

            $authUser = static::findUser(auth()->user()->publicId);
            $data = $request->all();
            $userReciever = static::findUser($publicId);
            if ($request->hasFile('files')) {
                $fileloader = new FileUploadService();
                $path = $fileloader->UploadFile($data['files'][0]);
                $file = File::create([
                    'path' => $path,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'state' => 'active',
                    'sender_id' => $authUser->publicId,
                    'receiver_id' => $userReciever->publicId
                ]);
                $authUser->sentFiles()->attach($file, ['userReceiver' => $userReciever->id]);

                return 'File Uploaded Successfully';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function findUser($publicId)
    {
        $user = User::where('publicId', $publicId)->first();
        if (!$user) {
            abort(404);
        }
        return $user;
    }
    static function getPath($id)
    {
        $authUser = static::findUser(auth()->user()->publicId);
        if ($authUser->sentFiles->contains($id) || $authUser->receivedFiles->contains($id)) {
            $file = File::find($id);
            $file_path = public_path('storage\\' . $file->path);
            $file_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file_path);
            return $file_path;
        }
    }
    static function getPathOfGlobal($publicId)
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
    static function deleteFile($publicId)
    {
        $file = File::where('publicId', $publicId);
        if (!$file) {
            abort(404);
        }
        $receiver = $file->receiver;
        $sender = $file->sender;
        if ($file->sender->publicId === auth()->user()->publicId || $file->receiver->punblicId === auth()->user()->publicId) {
            $fileloader = new FileUploadService();
            $fileloader->deleteFile(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file->path));
            $file->delete();


            if ($file->sender->publicId === auth()->user()->publicId && $file->receiver->publicId !== auth()->user()->publicId) {

                $message_to_sender = Message::create([
                    'text' => "You Deleted A File That Was Sent To $receiver->email By You",
                    'system' => true,
                    'userReceiver' => $sender->publicId
                ]);
                $message_to_receiver = Message::create([
                    'text' => "User $sender->email Have Deleted The File They Sent You",
                    'system' => true,
                    'userReceiver' => $receiver->publicId
                ]);
            }

            if ($file->receiver->id === auth()->id() && $file->sender->id !== auth()->id()) {

                $message_to_sender = Message::create([
                    'text' => "User $receiver->email Have Deleted A File That Was Sent To Them By You",
                    'system' => true,
                    'userReceiver' => $sender->publicId
                ]);
                $message_to_receiver = Message::create([
                    'text' => "You Have Deleted The File That Was Sent To You By $sender->email",
                    'system' => true,
                    'userReceiver' => $receiver->publicId
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
