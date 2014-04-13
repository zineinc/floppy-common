<?php

namespace Floppy\Tests\Common\FileHandler;

use Floppy\Common\FileHandler\ImagePathMatcher;
use Floppy\Common\FileId;
use Floppy\Tests\Common\FileHandler\AbstractPathMatcherTest;
use Floppy\Tests\Common\Stub\ChecksumChecker;

class ImagePathMatcherTest extends AbstractPathMatcherTest
{
    const SUPPORTED_EXTENSION = 'jpg';
    const UNSUPPORTED_EXTENSION = 'zip';

    protected function createVariantMatcher(ChecksumChecker $checksumChecker)
    {
        return new \Floppy\Common\FileHandler\ImagePathMatcher($checksumChecker, array(self::SUPPORTED_EXTENSION));
    }

    public function matchDataProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION,
                false,
                new FileId('fileid.'.self::SUPPORTED_EXTENSION, array(
                    'width' => 900,
                    'height' => 502,
                    'cropBackgroundColor' => 'ffffff',
                    'crop' => false,
                ), self::VALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION)
            ),
            //query params are ignored
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION.'?some=extra',
                false,
                new FileId('fileid.'.self::SUPPORTED_EXTENSION, array(
                    'width' => 900,
                    'height' => 502,
                    'cropBackgroundColor' => 'ffffff',
                    'crop' => false,
                ), self::VALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION)
            ),
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::UNSUPPORTED_EXTENSION,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/' . self::INVALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_0_0_fileid.'.self::SUPPORTED_EXTENSION,
                true,
                null,
            ),
        );
    }

    public function matchesDataProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/' . self::INVALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION,
                true,
            ),
            //query params are ignored
            array(
                'some/dirs/to/ignore/' . self::INVALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION.'?some=extra',
                true,
            ),
            //some params missing
            array(
                'some/dirs/to/ignore/' . self::VALID_CHECKSUM . '_502_ffffff_0_fileid.'.self::SUPPORTED_EXTENSION,
                false,
            ),
            //invalid extension
            array(
                'some/dirs/to/ignore/' . self::INVALID_CHECKSUM . '_900_502_ffffff_0_fileid.'.self::UNSUPPORTED_EXTENSION,
                false,
            ),
        );
    }
}