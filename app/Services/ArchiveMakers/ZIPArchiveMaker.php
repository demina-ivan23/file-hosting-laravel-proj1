<?php

namespace App\Services\ArchiveMakers;




use ZipArchive;
use App\Services\ArchiveInterface;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ZipArchiveMaker implements ArchiveInterface
{
    private $fileUploader;

    public function __construct(FileUploadService $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function makeArchive(array $files, ?string $archive_name)
    {
        $paths = [];
        Storage::makeDirectory('public/files/archives');
        if(is_null($archive_name))
        {
            $archive_name = Str::random(20) . '.zip';
        }
        $zipFileName = $archive_name;
        $zipFilePath = 'public/files/archives/' . $zipFileName;

        $zip = new ZipArchive();

        // Create a temporary file for the zip contents
        $tempZipFile = tempnam(sys_get_temp_dir(), 'zip_temp');

        if ($zip->open($tempZipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            // Add files to the zip archive
            foreach ($files as $file) {
                $path = $this->fileUploader->UploadFile($file);
                
                if ($path) {
                    $fileFullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, str_replace(' ', '', public_path('storage/' . $path)));
                    $zip->addFile($fileFullPath, $path);
                    $paths[] = $path; 
                } else {
                    // Handle the case where file upload failed
                    dd('File upload failed for: ' . $file);
                }
            }

            $zip->setCompressionIndex(0, ZipArchive::CM_STORE);
            $zip->setCompressionIndex(20, ZipArchive::CM_DEFLATE);

            // Close the zip archive
            $zip->close();

            // foreach($paths as  $path){
            //     $this->fileUploader->deleteFile(str_replace(['/', '\\'], DIRECTORY_SEPARATOR , $path));
            // }

            // Write the zip contents to the storage file
            Storage::put($zipFilePath, file_get_contents($tempZipFile));

            // Remove the temporary file
            unlink($tempZipFile);

            // Check if the file exists after moving
            // dd(Storage::exists($zipFilePath));
            return $zipFilePath;
        } else {
            // Handle the case where the zip archive couldn't be created
            dd('It didn\'t work well');
        }
    }
}
