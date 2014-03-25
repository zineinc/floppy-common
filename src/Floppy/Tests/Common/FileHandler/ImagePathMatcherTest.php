<?php

namespace Floppy\Tests\Common\FileHandler;

use Floppy\Common\FileHandler\ImagePathMatcher;
use Floppy\Common\FileId;
use Floppy\Tests\Common\FileHandler\AbstractPathMatcherTest;
use Floppy\Tests\Common\Stub\ChecksumChecker;

class ImagePathMatcherTest extends AbstractPathMatcherTest
{
    protected function createVariantMatcher(ChecksumChecker $checksumChecker)
    {
        return new \Floppy\Common\FileHandler\ImagePathMatcher($checksumChecker);
    }

    public function matchDataProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_900_502_ffffff_0_fileid.jpeg',
                false,
                new FileId('fileid.jpeg', array(
                    'width' => 900,
                    'height' => 502,
                    'cropBackgroundColor' => 'ffffff',
                    'crop' => false,
                ), self::VALID_CHECKSUM . '_900_502_ffffff_0_fileid.jpeg')
            ),
            array(
                'some/dirs/to/ignore/' . self::INVALID_CHECKSUM . '_900_502_ffffff_0_fileid.jpeg',
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_0_0_fileid.jpeg',
                true,
                null,
            ),
        );
    }

    public function matchesDataProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/' . self::INVALID_CHECKSUM . '_900_502_ffffff_0_fileid.jpeg',
                true,
            ),
            //some params missing
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_502_ffffff_0_fileid.jpeg',
                false,
            ),
        );
    }
}