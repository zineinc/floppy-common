<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class FilePathGenerator extends AbstractPathGenerator
{
    protected function getUrlParams(FileId $fileId)
    {
        return array(
            \URLify::filter($fileId->attributes()->get('name'), 120),
        );
    }
}