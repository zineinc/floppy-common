<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class FilePathGenerator implements PathGenerator
{
    private $checksumChecker;
    private $filepathChoosingStrategy;

    function __construct(ChecksumChecker $checksumChecker, FilepathChoosingStrategy $filepathChoosingStrategy)
    {
        $this->checksumChecker = $checksumChecker;
        $this->filepathChoosingStrategy = $filepathChoosingStrategy;
    }


    public function generate(FileId $fileId)
    {
        $name = \URLify::filter($fileId->attributes()->get('name'), 120);
        $params = array(
            $fileId->id(),
            $name,
        );

        $checksum = $this->checksumChecker->generateChecksum($params);

        return $this->filepathChoosingStrategy->filepath($fileId).sprintf('/%s?name=%s&checksum=%s', $fileId->id(), $name, $checksum);
    }
}