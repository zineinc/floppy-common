<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class ImagePathGenerator extends AbstractPathGenerator
{
    protected function getUrlParams(FileId $fileId)
    {
        $params = array(
            (string) $fileId->attributes()->get('width'),
            (string) $fileId->attributes()->get('height'),
            (string) $fileId->attributes()->get('cropBackgroundColor', 'ffffff'),
            (string) ($fileId->attributes()->get('crop', false) ? 1 : 0),
        );
        return $params;
    }
}