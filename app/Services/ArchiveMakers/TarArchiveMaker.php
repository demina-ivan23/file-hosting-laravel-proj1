<?php

namespace App\Services\ArchiveMakers;

use Illuminate\Support\Str;
use App\Services\ArchiveInterface;
use App\Services\FileUploadService;
use PharData;



class TarArchiveMaker implements ArchiveInterface{
    private $fileUploader;

    public function __construct(FileUploadService $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }
public function makeArchive(array $files, ?string $archive_name){
    // dd('tar',$files, $archive_name);
    if(is_null($archive_name)){
        $archive_name = Str::random(20) . '.tar';
    }
    $destinationPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR , storage_path('app/public/files/archives/' . $archive_name));
    $phar = new PharData($destinationPath);
    foreach($files as $file)
    {
        $path = $this->fileUploader->UploadFile($file);        
        if($path){
            $fileFullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, str_replace(' ', '', public_path('storage/' . $path)));
            $phar->addFile($fileFullPath, $path);
            $this->fileUploader->deleteFile(str_replace(['/', '\\'], DIRECTORY_SEPARATOR , $path));
        }
        else
        {
            dd('File Upload Failed For: ' . $file);
        }
    }
    return $destinationPath;
}

}