<?php


namespace ZineInc\Storage\Common\FileHandler;


use ZineInc\Storage\Common\ChecksumChecker;
use ZineInc\Storage\Common\FileId;
use ZineInc\Storage\Common\Storage\FilepathChoosingStrategy;

class FilePathGenerator implements PathGenerator
{
    private $checksumChecker;
    private $filepathChoosingStrategy;
    private $rootPath;

    function __construct(ChecksumChecker $checksumChecker, FilepathChoosingStrategy $filepathChoosingStrategy, $rootPath = '')
    {
        $this->checksumChecker = $checksumChecker;
        $this->filepathChoosingStrategy = $filepathChoosingStrategy;
        $this->rootPath = (string) $rootPath;
    }


    public function generate(FileId $fileId)
    {
        $name = \URLify::filter($fileId->attributes()->get('name'), 120);
        $params = array(
            $fileId->id(),
            $name,
        );

        $checksum = $this->checksumChecker->generateChecksum($params);

        return $this->rootPath.'/'.$this->filepathChoosingStrategy->filepath($fileId).sprintf('/%s?name=%s&checksum=%s', $fileId->id(), $name, $checksum);
    }
}