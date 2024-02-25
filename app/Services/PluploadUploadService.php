<?php

namespace App\Services;

class PluploadUploadService 
{
    static function storeTempFile($file, $uuid)
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, 'storage/app/public/temp_files/'.$uuid);
        if(!storage_path($path))
        {
            Storage::disk('local')->makeDirectory($path);
        }
    }
}