<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\GlobalFile;
use Illuminate\Support\Str;
use App\Services\ArchiveMakers\TarArchiveMaker;
use App\Services\ArchiveMakers\ZipArchiveMaker;

class GlobalFileService
{

    static function getAllPublicFiles()
    {
        return GlobalFile::where('isPublic', true)->filter()->paginate(4);
    }
    static function getAllProtectedFiles()
    {
        $authUser = static::findUser(auth()->id());
        $files = GlobalFile::where('isPublic', false)->filter();
        $filteredFiles = $files->filter(function ($file) use ($authUser) {
            return $file->owner === $authUser->id ||
                ($file->owner->contacts->contains($authUser->id) && !$file->owner->blacklist->contains($authUser->id));
        })->filter()->paginate(4);
    
        return $filteredFiles;
    }
    static function storeProtectedFile($request)
    {
        if($request->hasFile('files')){
            $authUser = static::findUser(auth()->id());
            $data= $request->all();
            if(count($data['files']) > 1)
            {
                if($data['fileCompressionFormat'] === 'none'){
                    return 'You Cannot Post Multiple Global Files Without Any Compression Format Selected';
                }
                else if($data['fileCompressionFormat'] === 'tar'){
                    $data = $request->all();
                    $archivemaker = new TarArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.tar';
                    $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                    $file = GlobalFile::create([
                        'path' => str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'category' => $data['category'],
                        'isPublic' => false,
                        'state' => 'active',
                        'publicId' => Str::random(30),
                        'owner_id' => $authUser->id,
                        'expireDate' => Carbon::now()->addDays(30)
                    ]);
                    $authUser->ownedGlobalFiles()->attach($file);
                }
                else if($data['fileCompressionFormat'] === 'zip'){
                    $data = $request->all();
                    $archivemaker = new ZipArchiveMaker(new FileUploadService());
                    $archive_name = Str::random(20) . '.zip';
                    $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                    $file = GlobalFile::create([
                        'path' => str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'category' => $data['category'],
                        'isPublic' => false,
                        'state' => 'active',
                        'publicId' => Str::random(30),
                        'owner_id' => $authUser->id,
                        'expireDate' => Carbon::now()->addDays(30)
                    ]);
                    $authUser->ownedGlobalFiles()->attach($file);
                }
                else{
                    return 'Unknown File Compression Format';
                }
            }   
            else{
                $data = $request->all();
                $fileloader = new FileUploadService();
                $path = $fileloader->UploadFile($data['files'][0]);
                $file = GlobalFile::create([
                    'path' => $path,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'isPublic' => false,
                    'state' => 'active',
                    'publicId' => Str::random(30),
                    'owner_id' => $authUser->id,
                    'expireDate' => Carbon::now()->addDays(30)
                ]);
                $authUser->ownedGlobalFiles()->attach($file);
                
            } 
            return 'Contacts-only File Uploaded Successfully';
        }
        else{
            return 'No Files Selected';
        }
    }
    static function getFileByPubId($publicId){
        
        $file = GlobalFile::where('publicId', $publicId)->first();
        if(!$file){
            abort(404);
        }
        return $file;
    }
    static function storePublicFile($request)
    {
        if($request->hasFile('files')){
        $authUser = static::findUser(auth()->id());
        $data= $request->all();
        if(count($data['files']) > 1)
        {

            if($data['fileCompressionFormat'] === 'none'){
                return 'You Cannot Post Multiple Global Files Without Any Compression Format Selected';
            }
            else if($data['fileCompressionFormat'] === 'tar'){
                $data = $request->all();
                $archivemaker = new TarArchiveMaker(new FileUploadService());
                $archive_name = Str::random(20) . '.tar';
                $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                $file = GlobalFile::create([
                    'path' => str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'isPublic' => true,
                    'state' => 'active',
                    'publicId' => Str::random(30),
                    'owner_id' => $authUser->id,
                    'expireDate' => Carbon::now()->addDays(30)
                ]);
                $authUser->ownedGlobalFiles()->attach($file);
            }
            else if($data['fileCompressionFormat'] === 'zip'){
                $data = $request->all();
                $archivemaker = new ZipArchiveMaker(new FileUploadService());
                $archive_name = Str::random(20) . '.zip';
                $archive = $archivemaker->makeArchive($data['files'], $archive_name);
                $file = GlobalFile::create([
                    'path' => str_replace(['/', '\\'], DIRECTORY_SEPARATOR,  'files/archives/' . $archive_name),
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'isPublic' => true,
                    'state' => 'active',
                    'publicId' => Str::random(30),
                    'owner_id' => $authUser->id,
                    'expireDate' => Carbon::now()->addDays(30)
                ]);
                $authUser->ownedGlobalFiles()->attach($file);
            }
            else{
                return 'Unknown File Compression Format';
            }
        }   
        else{

            $data = $request->all();
            $fileloader = new FileUploadService();
            $path = $fileloader->UploadFile($data['files'][0]);
            $file = GlobalFile::create([
                'path' => $path,
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'],
                'isPublic' => true,
                'state' => 'active',
                'publicId' => Str::random(30),
                'owner_id' => $authUser->id,
                'expireDate' => Carbon::now()->addDays(30)
            ]);
            $authUser->ownedGlobalFiles()->attach($file);
            
        } 
        return 'Public File Uploaded Successfully';
    }
    else{
        return 'No Files Selected';
    }
    }
    static function incrementViews($file)
    {
        $authUser = static::findUser(auth()->id());
        if(!$authUser->viewedGlobalFiles->contains($file->id))
        {
            $views = $file->views + 1;
            $file->update(['views' => $views]);
            $authUser->viewedGlobalFiles()->attach($file->id);
        }
        else{
         //   
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
}
