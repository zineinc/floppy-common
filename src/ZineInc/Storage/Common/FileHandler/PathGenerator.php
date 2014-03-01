<?php


namespace ZineInc\Storage\Common\FileHandler;

use ZineInc\Storage\Common\FileId;

interface PathGenerator
{
    /**
     * Generates path to given file.
     *
     * Path should contain only path and query parts of URL
     *
     * @param FileId $fileId
     *
     * @return string
     */
    public function generate(FileId $fileId);
} 