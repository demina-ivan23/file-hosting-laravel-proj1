<?php

namespace App\Services;

use Exception;
use App\Models\File;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Str;
use App\Services\ArchiveMakers\TarArchiveMaker;
use App\Services\ArchiveMakers\ZipArchiveMaker;

class MultipleFilesService
{
    static function getAllFiles($id)
    {
        try {

            $authUser = static::findUser($id);
            $files = File::where(function ($query) use ($authUser) {
                $query->where('sender_id', $authUser->id);
            })
                ->orWhere(function ($query) use ($authUser) {
                    $query->where('receiver_id', $authUser->id);
                })
                ->filter()
                ->latest()
                ->get();
            return $files;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    static function findUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new Exception('User Not Found');
        }
        return $user;
    }
    static function sendFiles($request, $user)
    {
        try {
            $authUser = static::findUser(auth()->id());
            $userReceiver = static::findUser($user);
            $data = $request->all();
            $fileCompressionFormat = $data['fileCompressionFormat'];
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                if ($fileCompressionFormat === 'none') {

                    foreach ($files as $file) {
                        $fileloader = new FileUploadService();
                        $path = $fileloader->uploadFile($file);
                        $createdFile = File::create([
                            'path' => $path,
                            'title' => $request->input('title'),
                            'description' => $request->input('description'),
                            'category' => $request->input('category'),
                            'state' => 'active',
                            'sender_id' => $authUser->id,
                            'receiver_id' => $userReceiver->id,
                        ]);

                        $authUser->sentFiles()->attach($createdFile, ['userReceiver' => $userReceiver->id]);
                    }

                    return 'Files Uploaded Successfully';
                }
                if ($fileCompressionFormat === 'zip') {
                    $archivemaker = new ZipArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.zip';
                    $archive = $archivemaker->makeArchive($files, $archive_name);
                    $file = File::create([
                        'path' =>  str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'category' => $data['category'],
                        'state' => 'active',
                        'sender_id' => $authUser->id,
                        'receiver_id' => $userReceiver->id
                    ]);
                    $authUser->sentFiles()->attach($file, ['userReceiver' => $userReceiver->id]);
                    return '.zip Archive Uploaded Successfully';
                }
                if ($fileCompressionFormat === 'tar') {
                    $archivemaker = new TarArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.tar';
                    $archive = $archivemaker->makeArchive($files, $archive_name);
                    $file = File::create([
                        'path' =>  str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'category' => $data['category'],
                        'state' => 'active',
                        'sender_id' => $authUser->id,
                        'receiver_id' => $userReceiver->id
                    ]);
                    $authUser->sentFiles()->attach($file, ['userReceiver' => $userReceiver->id]);
                    return  '.tar Archive Uploaded Successfully';
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    static function deleteFiles($request)
    {
        try{

            if ($request['delete_files']) {
                if ($request['delete_files'] == 'all') {
                    $files = File::where('sender_id', auth()->id())->get();
                $filesToDelete = [];
                foreach ($files as $file) {
                    $filesToDelete[] = $file->id;
                }
            } else {
                $filesToDelete = $request['delete_files'];
            }
            foreach ($filesToDelete as $file) {
                $file = File::find($file);
                if (!$file) {
                    abort(404);
                }
                $receiver = User::find($file->receiver_id);
                $sender = User::find($file->sender_id);
                $file->delete();
                if ($file->sender->id === auth()->id() xor $file->receiver->id === auth()->id()) {
                    
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
                } else if ($file->receiver->id === auth()->id() && $file->sender->id === auth()->id()) {

                    $fileloader = new FileUploadService();
                    $fileloader->deleteFile(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file->path));
                    $file->delete();
                    $message_to_yourself = Message::create([
                        'text' => 'You Have Deleted Your Personal File',
                        'system' => true,
                        'userReceiver' => $sender->id
                    ]);
                    $sender->messages()->attach($message_to_yourself);
                } else {
                    return 'You Are Neither File\'s Sender Nor It\'s Receiver. But You Tried :)';
                }
            }
            return 'Files Deleted Successfully';
        } else {
            return 'No Files Selected';
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
    }
}
