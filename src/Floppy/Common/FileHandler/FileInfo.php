<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\FileId;

final class FileInfo
{
    private $fileId;
    private $checksum;
    private $encodedAttributes;

    public function __construct(FileId $fileId, $checksum, $encodedAttributes)
    {
        $this->fileId = $fileId;
        $this->checksum = $checksum;
        $this->encodedAttributes = $encodedAttributes;
    }

    public function fileId()
    {
        return $this->fileId;
    }

    public function checksum()
    {
        return $this->checksum;
    }

    public function encodedAttributes()
    {
        return $this->encodedAttributes;
    }
}