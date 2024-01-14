<?php

namespace App\Services;

interface ArchiveInterface
{
  public function makeArchive($files, $archive_name);
}