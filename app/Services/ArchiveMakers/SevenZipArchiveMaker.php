<?php

namespace App\Services\ArchiveMakers;

use Illuminate\Support\Str;
use App\Services\ArchiveInterface;
use App\Services\FileUploadService;

class SevenZipArchiveMaker implements ArchiveInterface{
    private $fileUploader;

    public function __construct(FileUploadService $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }
public function makeArchive($files, $archive_name){
    // dd('7z',$files, $archive_name);
    if(is_null($archive_name)){
        $archive_name = Str::random(20) . '.7z';
    }
    $filesToArchive = [];
    foreach($files as $file){
        $path = $this->fileUploader->UploadFile($file);
        if($path){
            $filesToArchive[] =  str_replace( ['/', '\\'] , DIRECTORY_SEPARATOR , storage_path($path));
             $this->fileUploader->deleteFile($path);
        }
        else
        {
           dd('An Issue Occured When Uploading This File:' , $file);
        }
    }
    $filesToArchive = array_map(function ($file) {
        return str_replace('\\', '/', $file);
    }, $filesToArchive);
    $destinationPath = storage_path('app/public/files/archives/' . $archive_name);

    // Create the destination archive file
    touch($destinationPath);
    
    // Construct the 7z command
    $command = '7z a "' . $destinationPath . '" ' . implode(' ', array_map('escapeshellarg', $filesToArchive));
    
    // Capture the output and return value of the exec command
    exec($command, $output, $returnVar);

    // Check if the command executed successfully
    if ($returnVar === 0) {
        return $destinationPath; 
    } else {
        // Handle error
        // dd([
        //     'Error creating 7z archive',
        //     'Command' => $command,
        //     'Output' => $output,
        //     'Return Var' => $returnVar,
        // ]);
    }
}

}