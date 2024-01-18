<?php

namespace App\Services;

interface ArchiveInterface
{
  public function __construct(FileUploadService $fileUploader);
  public function makeArchive( array $files, ?string $archive_name);
}