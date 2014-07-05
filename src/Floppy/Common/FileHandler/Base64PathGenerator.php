<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class Base64PathGenerator implements PathGenerator
{
    private $checksumChecker;
    private $attributeFilters;
    private $fileInfoAssembler;

    public function __construct(ChecksumChecker $checksumChecker, FileInfoAssembler $fileInfoAssembler, array $attributeFilters = array())
    {
        $this->validateAttributeFilters($attributeFilters);

        $this->checksumChecker = $checksumChecker;
        $this->fileInfoAssembler = $fileInfoAssembler;
        $this->attributeFilters = $attributeFilters;
    }

    public function generate(FileId $fileId)
    {
        $attributes = $this->filterAttributes($fileId->attributes()->all());

        if($attributes) {
            $dataToSign = $attributes;
            $dataToSign[] = $fileId->id();

            $checksum = $this->checksumChecker->generateChecksum($dataToSign);

            $encodedAttrs = base64_encode(json_encode($attributes));
        } else {
            $checksum = null;
            $encodedAttrs = null;
        }

        return $this->fileInfoAssembler->assembleFileInfo(new FileInfo($fileId, $checksum, $encodedAttrs));
    }

    private function filterAttributes(array $attributes)
    {
        foreach($this->attributeFilters as $name => $filter) {
            if(isset($attributes[$name])) {
                $attributes[$name] = $filter($attributes[$name]);
            }
        }
        return $attributes;
    }

    private function validateAttributeFilters(array $attributeFilters)
    {
        foreach ($attributeFilters as $filter) {
            if (!is_callable($filter)) {
                throw new \InvalidArgumentException('$attributeFilters should be an array of callables');
            }
        }
    }
}