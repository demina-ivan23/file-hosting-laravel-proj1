<?php

namespace App\Services\ArchiveMakers;

use App\Services\ArchiveInterface;
use App\Services\FileUploadService;

class RarArchiveMaker implements ArchiveInterface{

    private $fileUploader;

    public function __construct(FileUploadService $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

public function makeArchive($files, $archive_name){
    dd('rar', $files, $archive_name);
}

}