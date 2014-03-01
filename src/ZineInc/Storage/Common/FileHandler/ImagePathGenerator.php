<?php


namespace ZineInc\Storage\Common\FileHandler;


use ZineInc\Storage\Common\ChecksumChecker;
use ZineInc\Storage\Common\FileId;
use ZineInc\Storage\Common\Storage\FilepathChoosingStrategy;

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
        $params = array(
            (string) $fileId->attributes()->get('width'),
            (string) $fileId->attributes()->get('height'),
            (string) $fileId->attributes()->get('cropBackgroundColor', 'ffffff'),
            (string) ($fileId->attributes()->get('crop', false) ? 1 : 0),
            $fileId->id(),
        );

        $checksum = $this->checksumChecker->generateChecksum($params);

        return $this->filepathChoosingStrategy->filepath($fileId).sprintf('/%s_%d_%d_%s_%d_%s', $checksum, $params[0], $params[1], $params[2], $params[3], $fileId->id());
    }
}