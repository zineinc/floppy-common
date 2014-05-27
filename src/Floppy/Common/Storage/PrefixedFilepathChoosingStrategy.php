<?php


namespace Floppy\Common\Storage;


use Floppy\Common\FileId;

class PrefixedFilepathChoosingStrategy implements FilepathChoosingStrategy
{
    private $strategy;
    private $prefix;

    public function __construct(FilepathChoosingStrategy $strategy, $prefix)
    {
        $this->strategy = $strategy;
        $this->prefix = (string) $prefix;
    }

    public function filepath(FileId $fileId)
    {
        return ltrim($this->prefix.$this->strategy->filepath($fileId), '/');
    }
}