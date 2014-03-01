<?php


namespace ZineInc\Storage\Tests\Common\Stub;


use ZineInc\Storage\Common\FileId;

class FilepathChoosingStrategy implements \ZineInc\Storage\Common\Storage\FilepathChoosingStrategy
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