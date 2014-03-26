<?php

namespace Floppy\Tests\Common\FileHandler;

use Floppy\Common\FileHandler\FilePathMatcher;
use Floppy\Common\FileId;
use Floppy\Tests\Common\FileHandler\AbstractPathMatcherTest;
use Floppy\Tests\Common\Stub\ChecksumChecker;

class FilePathMatcherTest extends AbstractPathMatcherTest
{
    const SUPPORTED_EXTENSION = 'zip';
    const UNSUPPORTED_EXTENSION = 'jpg';

    protected function createVariantMatcher(ChecksumChecker $checksumChecker)
    {
        return new \Floppy\Common\FileHandler\FilePathMatcher($checksumChecker, array(self::SUPPORTED_EXTENSION));
    }

    public function matchDataProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_some-name_fileid.'.self::SUPPORTED_EXTENSION,
                false,
                new FileId('fileid.'.self::SUPPORTED_EXTENSION, array(
                    'name' => 'some-name',
                ), self::VALID_CHECKSUM.'_some-name_fileid.'.self::SUPPORTED_EXTENSION)
            ),
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_some-name_fileid.'.self::UNSUPPORTED_EXTENSION,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/'.self::INVALID_CHECKSUM.'_some-name_fileid.'.self::SUPPORTED_EXTENSION,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_fileid.'.self::SUPPORTED_EXTENSION,
                true,
                null,
            ),
        );
    }

    public function matchesDataProvider()
    {

        return array(
            array(
                'some/dirs/to/ignore/'.self::INVALID_CHECKSUM.'_some-name_file.'.self::SUPPORTED_EXTENSION,
                true
            ),
            array(
                'some/dirs/to/ignore/'.self::INVALID_CHECKSUM.'_some-name_file.'.self::UNSUPPORTED_EXTENSION,
                false
            ),
            //name is missing
            array(
                'some/dir/to/ignore/'.self::INVALID_CHECKSUM.'_file.'.self::SUPPORTED_EXTENSION,
                false
            ),
        );
    }
}