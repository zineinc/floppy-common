<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

abstract class AbstractPathGenerator implements PathGenerator
{
    private $checksumChecker;
    private $filepathChoosingStrategy;

    /**
     * @param ChecksumChecker $checksumChecker
     * @param FilepathChoosingStrategy $filepathChoosingStrategy
     */
    public function __construct(ChecksumChecker $checksumChecker, FilepathChoosingStrategy $filepathChoosingStrategy)
    {
        $this->checksumChecker = $checksumChecker;
        $this->filepathChoosingStrategy = $filepathChoosingStrategy;
    }

    public function generate(FileId $fileId)
    {
        $params = $this->getUrlParams($fileId);
        $params[] = $fileId->id();

        $checksum = $this->checksumChecker->generateChecksum($params);
        array_unshift($params, $checksum);

        return $this->filepathChoosingStrategy->filepath($fileId).'/'.implode('_', $params);
    }

    abstract protected function getUrlParams(FileId $fileId);
}