<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\GlobalFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
    static function findUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new Exception('User Not Found');
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
// dd($oldFile->receiver->id);
                    if ($oldFile->receiver->id !== $oldFile->sender->id) {
                        $oldFile->delete();
                        $file = File::create([
                            'path' => $data['path'],
                            'title' => $data['title'],
                            'description' => $data['description'],
                            'category' => $data['category'],
                            'state' => 'active',
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
    static function copyGlobalFile($publicId){
        $authUser = UserService::findUser(auth()->id());
        $file = GlobalFileService::getFileByPubId($publicId);
        $personalFile = File::create([
            'path' =>  $file->path,
            'title' => $file->title,
            'description' => $file->description,
            'category' => $file->category,
            'state' => 'active',
            'sender_id' => $authUser->id,
            'receiver_id' => $authUser->id
        ]);
        $authUser->sentFiles()->attach($personalFile, ['userReceiver' => $authUser->id]);
    }
    static function saveFileViaPlupload($request)
    {
        try {
            $authUser = UserService::findUser(auth()->id());
            
            $uuid = $request->input('uuid');
      
            $filename = Str::random(10).'.'.$request->input('extension'); 
            
            $response = PluploadUploadService::assembleChunks($uuid, $filename);

            $file = File::create([
                    'path' => 'files'. DIRECTORY_SEPARATOR .$filename,
                    'title' => $request['title'],
                    'description' => $request['description'],
                    'category' => $request['category'],
                    'state' => 'active',
                    'sender_id' => $authUser->id,
                    'receiver_id' => $authUser->id,
                ]);
                $authUser->sentFiles()->attach($file, ['userReceiver' => $authUser->id]);
            return 'File Sent Successfully';
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return 'Error: ' . $e->getMessage();
        }
    }
}
