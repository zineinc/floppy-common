<?php


namespace Floppy\Tests\Common\Stub;


use Floppy\Common\FileId;

class FilepathChoosingStrategy implements \Floppy\Common\Storage\FilepathChoosingStrategy
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function filepath(FileId $fileId)
    {
        return $this->path;
    }
}