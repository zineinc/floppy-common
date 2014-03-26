<?php

namespace Floppy\Common\FileHandler;

use Floppy\Common\FileId;
use Floppy\Common\ChecksumChecker;

class FilePathMatcher extends AbstractPathMatcher
{
    protected function getAttrributes(array $params)
    {
        return array(
            'name' => $params[0],
        );
    }

    protected function getSupportedParamsCount()
    {
        return 3;
    }
}