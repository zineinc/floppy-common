<?php

namespace Floppy\Common;

final class FileType
{
    private $mimeType;
    private $extension;

    public function __construct($mimeType, $extension)
    {
        $this->mimeType = (string)$mimeType;
        $this->extension = (string)$extension;
    }

    public function mimeType()
    {
        return $this->mimeType;
    }

    public function extension()
    {
        return $this->extension;
    }
}