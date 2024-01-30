<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Message;
use App\Models\GlobalFile;
use Illuminate\Support\Str;
use App\Services\ArchiveMakers\TarArchiveMaker;
use App\Services\ArchiveMakers\ZipArchiveMaker;

class GlobalFileService
{

    static function getAllPublicFiles()
    {
        return GlobalFile::where('isPublic', true)->filter()->latest()->paginate(4);
    }
    static function getAllProtectedFiles()
    {
        $authUser = static::findUser(auth()->id());
        $files = GlobalFile::where('isPublic', false)->filter();
        $filteredFiles = $files->filter(function ($file) use ($authUser) {
            return $file->owner === $authUser->id ||
                ($file->owner->contacts->contains($authUser->id) && !$file->owner->blacklist->contains($authUser->id));
        })->filter()->latest()->paginate(4);

        return $filteredFiles;
    }
    static function storeProtectedFile($request)
    {
        if ($request->hasFile('files')) {
            $path = ' ';
            $authUser = static::findUser(auth()->id());
            $data = $request->all();
            if (count($data['files']) > 1) {
                if ($data['fileCompressionFormat'] === 'none') {
                    return 'You Cannot Post Multiple Global Files Without Any Compression Format Selected';
                } else if ($data['fileCompressionFormat'] === 'tar') {
                    $archivemaker = new TarArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.tar';
                    $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                    $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name);
                } else if ($data['fileCompressionFormat'] === 'zip') {
                    $archivemaker = new ZipArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.zip';
                    $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                    $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name);
                } else {
                    return 'Unknown File Compression Format';
                }
            } else {
                $fileloader = new FileUploadService();
                $path = $fileloader->UploadFile($data['files'][0]);
            }
        } else {
            return 'No Files Selected';
        }
        $file_path = public_path('storage\\' . $path);
        $file_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file_path);
        $file = GlobalFile::create([
            'path' => $path,
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'isPublic' => false,
            'state' => 'active',
            'publicId' => Str::random(30),
            'owner_id' => $authUser->id,
            'expireDate' => Carbon::now()->addDays(30),
            'mimeType' => mime_content_type($file_path)
        ]);
        $authUser->ownedGlobalFiles()->attach($file);
        return 'Contacts-only File Uploaded Successfully';
    }
    static function getFileByPubId($publicId)
    {

        $file = GlobalFile::where('publicId', $publicId)->first();
        if (!$file) {
            abort(404);
        }
        return $file;
    }
    static function storePublicFile($request)
    {
        if ($request->hasFile('files')) {
            $path = '';
            $authUser = static::findUser(auth()->id());
            $data = $request->all();
            if (count($data['files']) > 1) {

                if ($data['fileCompressionFormat'] === 'none') {
                    return 'You Cannot Post Multiple Global Files Without Any Compression Format Selected';
                } else if ($data['fileCompressionFormat'] === 'tar') {
                    $archivemaker = new TarArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.tar';
                    $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                    $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name);
                } else if ($data['fileCompressionFormat'] === 'zip') {
                    $archivemaker = new ZipArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.zip';
                    $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                    $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name);
                } else {
                    return 'Unknown File Compression Format';
                }
            } else {
                $fileloader = new FileUploadService();
                $path = $fileloader->UploadFile($data['files'][0]);
            }
           
        } else {
            return 'No Files Selected';
        }
        $file_path = public_path('storage\\' . $path);
        $file_path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file_path);
        $file = GlobalFile::create([
            'path' => $path,
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'isPublic' => true,
            'state' => 'active',
            'publicId' => Str::random(30),
            'owner_id' => $authUser->id,
            'expireDate' => Carbon::now()->addDays(30),
            'mimeType' => mime_content_type($file_path)

        ]);
        $authUser->ownedGlobalFiles()->attach($file);
        return 'Public File Uploaded Successfully';
    }
    static function deleteFile($id){
        $file = GlobalFile::where('publicId', $id)->first();
        $authUser = static::findUser(auth()->id());
        if(!$file)
        {
            abort(404);
        }

        if($file->owner->id === $authUser->id){
            $fileloader = new FileUploadService();
            $fileloader->deleteFile(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file->path));
            $file->delete();
            $message_to_owner = Message::create([
                'text' => 'You Have Deleted A Global File',
                'system' => true,
                'userReceiver' => $authUser->id
            ]);
            $authUser->messages()->attach($message_to_owner);
            return 'File Deleted Successfully';
        }
        else{
            return 'You Are Not The Owner Of This File';
        }
    }
    static function incrementViews($file)
    {
        if(auth()->id())
        {

            $authUser = static::findUser(auth()->id());
            if (!$authUser->viewedGlobalFiles->contains($file->id)) {
                $views = $file->views + 1;
                $file->update(['views' => $views]);
                $authUser->viewedGlobalFiles()->attach($file->id);
            } else {
                //   
            }
        }
    }
    static function incrementLikes($file)
    {
        if(auth()->id())
        {
            $authUser = static::findUser(auth()->id());
            if (!$authUser->likedGlobalFiles->contains($file->id)) {
                $likes = $file->likes + 1;
                $file->update(['likes' => $likes]);
                $authUser->likedGlobalFiles()->attach($file->id);
                return 'You Have Successfully Liked A Global File';
            } else {
                return 'You Have Already Liked The File';  
            }
        }
    }
    static function findUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404);
        }
        return $user;
    }
}
