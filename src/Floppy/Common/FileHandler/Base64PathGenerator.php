<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class Base64PathGenerator implements PathGenerator
{
    private $checksumChecker;
    private $filepathChoosingStrategy;

    public function __construct(ChecksumChecker $checksumChecker, FilepathChoosingStrategy $filepathChoosingStrategy)
    {
        $this->checksumChecker = $checksumChecker;
        $this->filepathChoosingStrategy = $filepathChoosingStrategy;
    }

    public function generate(FileId $fileId)
    {
        $dataToSign = $fileId->attributes()->all();
        $dataToSign[] = $fileId->id();

        $checksum = $this->checksumChecker->generateChecksum($dataToSign);

        $encodedAttrs = base64_encode(json_encode($fileId->attributes()->all()));

        return sprintf('%s/%s_%s_%s', $this->filepathChoosingStrategy->filepath($fileId), $checksum, $encodedAttrs, $fileId->id());
    }
}