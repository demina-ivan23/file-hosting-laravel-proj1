<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\GlobalFile;
use Illuminate\Support\Str;
use App\Services\ArchiveMakers\TarArchiveMaker;
use App\Services\ArchiveMakers\ZipArchiveMaker;

class PersonalFileService
{
    static function getAllPersonalFiles($id)
    {
        $authUser = static::findUser($id);
        $files = File::where(function ($query) use ($authUser) {
            $query->where('sender_id', $authUser->id)
                ->where('receiver_id', $authUser->id);
        })->filter()
        ->latest()
        ->paginate(5);
        return $files;
    }
    static function findUser($publicId){
        $user = User::where('publicId', $publicId)->first();
        if(!$user){
            abort(404);
        }
        return $user;
    }
    static function storeFile($request)
    {
        try {

            $authUser = static::findUser(auth()->id());
            $data = $request->all();
            if ($request->hasFile('files')) {
                $data = $request->all();
                if (count($data['files']) > 1) {
                    if ($request['fileAccessibility'] == 'private') {
                    if($data['fileCompressionFormat'] === 'none'){
                        foreach ($data['files'] as $fileToUpload) {
                            $fileloader = new FileUploadService();
                            $path = $fileloader->UploadFile($fileToUpload);
                            $file = File::create([
                                'path' => $path,
                                'title' => $data['title'],
                                'description' => $data['description'],
                                'category' => $data['category'],
                                'state' => 'active',
                                'sender_id' => $authUser->id,
                                'receiver_id' => $authUser->id
                            ]);
                            $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);
                            
                        }
                        return 'Private File Uploaded Successfully';
                    }
                    else if($data['fileCompressionFormat'] === 'zip') {
                        $archivemaker = new ZipArchiveMaker(new FileUploadService());
                        $archive_name = Str::random(20) . '.zip';
                        $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                        $file = File::create([
                            'path' =>  str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'category' => $data['category'],
                            'state' => 'active',
                            'sender_id' => $authUser->id,
                            'receiver_id' => $authUser->id
                        ]);
                        $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);
                        return '.zip Archive Uploaded Successfully';
                    }
                    else if($data['fileCompressionFormat'] === 'tar') 
                    {
                        $archivemaker = new TarArchiveMaker(new FileUploadService());
                        $archive_name = Str::random(20) . '.tar';
                        $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                        $file = File::create([
                            'path' =>  str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'category' => $data['category'],
                            'state' => 'active',
                            'sender_id' => $authUser->id,
                            'receiver_id' => $authUser->id
                        ]);
                        $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);
                        return  '.tar Archive Uploaded Successfully';
                    }
                    else{
                        return 'Unknown File Compression Format';
                    }
                    } else {
                        return 'Front-end Redirection Failed';
                    }
                } else {
                    if ($request['fileAccessibility'] == 'private') {
                        $fileloader = new FileUploadService();
                        $path = $fileloader->UploadFile($data['files'][0]);
                        $file = File::create([
                            'path' => $path,
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'category' => $data['category'],
                            'state' => 'active',
                            'sender_id' => $authUser->id,
                            'receiver_id' => $authUser->id
                        ]);
                        $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);

                        return 'Private File Uploaded Successfully';
                    } else {
                        return 'Front-end Redirection Failed';
                    }
                }
            }
            if (array_key_exists('path', $data)) {
                if ($data['path']) {

                    $oldFile = File::where(['path' => $data['path']])->first();
                    if ($oldFile->receiver->id !== $oldFile->sender->id) {
                        $oldFile->delete();
                        $file = File::create([
                            'path' => $data['path'],
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'category' => $data['category'],
                            'sender_id' => $authUser->id,
                            'receiver_id' => $authUser->id
                        ]);
                        $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);
                        return 'File Added Successfully';
                    } else {
                        return 'This File Is Already In Your Personal Files';
                    }
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
