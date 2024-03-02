<?php

namespace App\Services;

use FFI\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PluploadUploadService 
{
    static function storeTempFile($file, $uuid)
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, 'temp_files/'.$uuid);
        if(!storage_path($path))
        {
            Storage::disk('local')->makeDirectory($path);
            $newTempFile = Storage::put($path.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '1.part'), file_get_contents($file));
            Log::info($newTempFile);
        
        }
        else {
            //here i want to get all files from the $path folder, and choose a file which has the largest number. How can i do that?
            $files = Storage::disk('local')->files($path);
            $largestNumber = 0;
            
            // Iterate through the files
            foreach ($files as $oldFile) {
                // Extract the file number from the file name
                $fileNumber = (int)pathinfo($oldFile, PATHINFO_FILENAME);
                
                // Update the largest file number and its corresponding file name if the current file has a larger number
                if ($fileNumber > $largestNumber) {
                    $largestNumber = $fileNumber;
                }
            }
            $newNumber = $largestNumber+1;
            $newTempFile = Storage::put($path.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '/'. $newNumber.'.part'), file_get_contents($file));
            Log::info($newNumber);
        }
        
    }
    static function assembleChunks($uuid, $finalFileName)
    {
        try
        {
            $path = 'temp_files/'.$uuid;
            $files = Storage::files($path);
            
            // Sort files by their part number
            natsort($files);
            
            // Create a new file to assemble chunks
            $assembledFile = fopen(storage_path(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, 'app/public/files/'. $finalFileName)), 'wb');
            
            // Concatenate chunks to the assembled file
            foreach ($files as $file) {
                $chunkContent = Storage::get($file);
                fwrite($assembledFile, $chunkContent);
            }
            
            // Close the assembled file
            fclose($assembledFile);
            
            // Delete the temporary files directory
            Storage::deleteDirectory($path);
            return true;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
}