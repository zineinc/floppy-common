<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileHandler\Exception\PathMatchingException;
use Floppy\Common\FileId;

class Base64PathMatcher implements PathMatcher
{
    private $checksumChecker;
    private $fileInfoAssembler;
    private $extensions;

    public function __construct(ChecksumChecker $checksumChecker, FileInfoAssembler $fileInfoAssembler, array $extensions)
    {
        $this->checksumChecker = $checksumChecker;
        $this->extensions = $extensions;
        $this->fileInfoAssembler = $fileInfoAssembler;
    }

    public function match($filepath)
    {
        $filename = $this->resolveFilename($filepath);

        if(!$this->isExtensionSupported($filename)) {
            throw new PathMatchingException(sprintf('File with given extension is unsupported, supported extensions: "%s", given filename: "%s"',
                implode(', ', $this->extensions), $filename));
        }

        $fileInfo = $this->extractFileInfo($filepath);

        if(!$fileInfo->checksum() && !$fileInfo->encodedAttributes()) {
            return $fileInfo->fileId();
        }

        $attributes = $this->decodeAttributes($fileInfo->encodedAttributes(), $filename);

        $signedData = $attributes;
        $signedData[] = $fileInfo->fileId()->id();

        if(!$this->checksumChecker->isChecksumValid($fileInfo->checksum(), $signedData)) {
            throw new PathMatchingException(sprintf('checksum is invalid for file: "%s"', $filename));
        }

        return new FileId($fileInfo->fileId()->id(), $attributes, $filename);
    }

    private function extractFileInfo($filepath)
    {
        return $this->fileInfoAssembler->extractFileInfo($filepath);
    }

    private function resolveFilename($filepath)
    {
        return UrlUtils::resolveFilename($filepath);
    }

    public function matches($variantFilepath)
    {
        return $this->isExtensionSupported($variantFilepath) && in_array(count(explode('_', $variantFilepath)), array(1, 3));
    }

    private function isExtensionSupported($filename)
    {
        $ext = pathinfo($this->resolveFilename($filename), PATHINFO_EXTENSION);

        return in_array($ext, $this->extensions);
    }

    private function decodeAttributes($encodedAttrs, $filename)
    {
        $json = base64_decode($encodedAttrs);

        if ($json === false) {
            throw new PathMatchingException(sprintf('Malformed arguments for file: %s', $filename));
        }

        $attributes = json_decode($json, true);

        if ($attributes === null || !is_array($attributes)) {
            throw new PathMatchingException(sprintf('Malformed arguments for file: %s', $filename));
        }

        return $attributes;
    }
}