<?php

namespace Floppy\Common\Storage;

use Floppy\Common\FileId;

interface FilepathChoosingStrategy
{
    /**
     * @param \Floppy\Common\FileId $fileId
     *
     * @return string Filepath for $fileId. It is relative path to Storage root path. It shouldn't starts and ends with "/"
     */
    public function filepath(FileId $fileId);
}