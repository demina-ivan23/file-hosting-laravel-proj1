<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class FileUploadService{
    public function UploadFile(UploadedFile $file, $disk = 'public', $filename = null)
    {
        $FileName = !is_null($filename) ? $filename : Str::random(10);
        return $file->storeAs(
            'files',
            $FileName . "." . $file->getClientOriginalExtension(),
            $disk
        );
    }

    public function deleteFile($path, $disk = 'public')
    {
        Storage::disk($disk)->delete($path);
    }  
}