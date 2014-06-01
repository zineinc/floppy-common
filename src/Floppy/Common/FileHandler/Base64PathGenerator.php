<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class Base64PathGenerator implements PathGenerator
{
    private $checksumChecker;
    private $filepathChoosingStrategy;
    private $attributeFilters;

    public function __construct(ChecksumChecker $checksumChecker, FilepathChoosingStrategy $filepathChoosingStrategy, array $attributeFilters = array())
    {
        $this->validateAttributeFilters($attributeFilters);

        $this->checksumChecker = $checksumChecker;
        $this->filepathChoosingStrategy = $filepathChoosingStrategy;
        $this->attributeFilters = $attributeFilters;
    }

    public function generate(FileId $fileId)
    {
        $dataToSign = $fileId->attributes()->all();
        $dataToSign[] = $fileId->id();

        $checksum = $this->checksumChecker->generateChecksum($dataToSign);

        $encodedAttrs = base64_encode(json_encode($this->filterAttributes($fileId->attributes()->all())));

        return sprintf('%s/%s_%s_%s', $this->filepathChoosingStrategy->filepath($fileId), $checksum, $encodedAttrs, $fileId->id());
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