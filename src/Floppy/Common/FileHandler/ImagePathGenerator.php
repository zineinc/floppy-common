<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class ImagePathGenerator implements PathGenerator
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

        $checksum = $this->checksumChecker->generateChecksum($params);
        array_unshift($params, $checksum);

        return $this->filepathChoosingStrategy->filepath($fileId).'/'.implode('_', $params);
    }

    protected function getUrlParams(FileId $fileId)
    {
        $params = array(
            (string) $fileId->attributes()->get('width'),
            (string) $fileId->attributes()->get('height'),
            (string) $fileId->attributes()->get('cropBackgroundColor', 'ffffff'),
            (string) ($fileId->attributes()->get('crop', false) ? 1 : 0),
            $fileId->id(),
        );
        return $params;
    }
}