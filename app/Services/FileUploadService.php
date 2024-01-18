<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class FileUploadService
{
    public function UploadFile(UploadedFile $file, $disk = 'public', $filename = null)
    {
        $FileName = !is_null($filename) ? $filename : Str::random(10);
        return $file->storeAs(
            'files',
            $FileName . "." . $file->getClientOriginalExtension(),
            $disk
        );
    }
    public function moveUploadedFile($sourcePath, $destinationDirectory)
    {
        // Generate a unique name for the file within the destination directory
        $destinationFileName = uniqid() . '_archive.zip';

        // Move the file to the desired directory within storage
        Storage::move($sourcePath, $destinationDirectory . '/' . $destinationFileName);

        // Return the path to the moved file
        return $destinationDirectory . '/' . $destinationFileName;
    }

    public function deleteFile($path, $disk = 'public')
    {
        Storage::disk($disk)->delete($path);
    }  
}