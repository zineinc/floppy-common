<?php


namespace Floppy\Common\FileHandler;


interface FileInfoAssembler
{
    /**
     * @param FileInfo $fileInfo
     * @return string
     */
    public function assembleFileInfo(FileInfo $fileInfo);

    /**
     * @param $filepath
     * @return FileInfo
     */
    public function extractFileInfo($filepath);
} 