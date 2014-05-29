<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;

class Base64PathGenerator implements PathGenerator
{
    private $checksumChecker;

    public function __construct(ChecksumChecker $checksumChecker)
    {
        $this->checksumChecker = $checksumChecker;
    }

    public function generate(FileId $fileId)
    {
        $dataToSign = $fileId->attributes()->all();
        $dataToSign[] = $fileId->id();

        $checksum = $this->checksumChecker->generateChecksum($dataToSign);

        $encodedAttrs = base64_encode(json_encode($fileId->attributes()->all()));

        return sprintf('%s_%s_%s', $checksum, $encodedAttrs, $fileId->id());
    }
}