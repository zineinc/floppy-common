<?php

namespace Floppy\Common\FileHandler;

use Floppy\Common\FileId;
use Floppy\Common\ChecksumChecker;

class ImagePathMatcher extends AbstractPathMatcher
{
    protected function getAttrributes(array $params)
    {
        return array(
            'width' => (int)$params[0],
            'height' => (int)$params[1],
            'cropBackgroundColor' => $params[2],
            'crop' => (boolean) $params[3],
        );
    }

    protected function getSupportedParamsCount()
    {
        return 6;
    }
}