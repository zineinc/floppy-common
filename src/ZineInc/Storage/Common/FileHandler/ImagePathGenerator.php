<?php


namespace ZineInc\Storage\Common\FileHandler;


use ZineInc\Storage\Common\ChecksumChecker;
use ZineInc\Storage\Common\FileId;
use ZineInc\Storage\Common\Storage\FilepathChoosingStrategy;

class ImagePathGenerator implements PathGenerator
{
    private $checksumChecker;
    private $filepathChoosingStrategy;
    private $rootPath;

    /**
     * @param ChecksumChecker $checksumChecker
     * @param FilepathChoosingStrategy $filepathChoosingStrategy
     * @param string $rootPath Public path where storage is located. Landing slash should be omitted.
     */
    public function __construct(ChecksumChecker $checksumChecker, FilepathChoosingStrategy $filepathChoosingStrategy, $rootPath = '')
    {
        $this->checksumChecker = $checksumChecker;
        $this->filepathChoosingStrategy = $filepathChoosingStrategy;
        $this->rootPath = $rootPath;
    }

    public function generate(FileId $fileId)
    {
        $params = array(
            (int) $fileId->attributes()->get('width'),
            (int) $fileId->attributes()->get('height'),
            $fileId->attributes()->get('cropBackgroundColor', 'ffffff'),
            (boolean) $fileId->attributes()->get('crop', false),
        );

        $checksum = $this->checksumChecker->generateChecksum($params);

        return $this->rootPath.'/'.$this->filepathChoosingStrategy->filepath($fileId).sprintf('/%s_%d_%d_%s_%d_%s', $checksum, $params[0], $params[1], $params[2], $params[3], $fileId->id());
    }
}