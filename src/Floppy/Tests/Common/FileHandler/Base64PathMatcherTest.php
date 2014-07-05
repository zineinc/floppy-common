<?php


namespace Floppy\Tests\Common\FileHandler;


use Floppy\Common\FileHandler\Base64PathMatcher;
use Floppy\Common\FileHandler\FilenameFileInfoAssembler;
use Floppy\Common\FileId;
use Floppy\Tests\Common\Stub\ChecksumChecker;
use Floppy\Tests\Common\Stub\FilepathChoosingStrategy;

class Base64PathMatcherTest extends AbstractPathMatcherTest
{
    const VALID_EXT = 'jpg';
    const INVALID_EXT = 'png';

    protected function createVariantMatcher(ChecksumChecker $checksumChecker)
    {
        return new Base64PathMatcher(
            new ChecksumChecker(self::VALID_CHECKSUM),
            new FilenameFileInfoAssembler(new FilepathChoosingStrategy('')),
            array(self::VALID_EXT)
        );
    }

    public function matchDataProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/'.($filename = self::VALID_CHECKSUM.'_'.base64_encode(json_encode(array('width' => 123))).'_file.'.self::VALID_EXT),
                false,
                new FileId('file.'.self::VALID_EXT, array('width' => 123), $filename),
            ),
            array(
                'some/dirs/to/ignore/'.self::INVALID_CHECKSUM.'_'.base64_encode(json_encode(array('width' => 123))).'_file.'.self::VALID_EXT,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_'.base64_encode(json_encode(array('width' => 123))).'_file.'.self::INVALID_EXT,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_invalidbase64_file.'.self::VALID_EXT,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_'.base64_encode('invalid_json').'_file.'.self::VALID_EXT,
                true,
                null,
            ),
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_'.base64_encode(json_encode('invalid json type')).'_file.'.self::VALID_EXT,
                true,
                null,
            ),
            array(
                //checksum missing
                'some/dirs/to/ignore/'.base64_encode(json_encode(array())).'_file.'.self::VALID_EXT,
                true,
                null,
            ),
            //accepts original file
            array(
                'some/dirs/file.'.self::VALID_EXT,
                false,
                new FileId('file.'.self::VALID_EXT),
            ),
            array(
                //query string is ignored
                'some/dirs/'.($filename = self::VALID_CHECKSUM.'_'.base64_encode(json_encode(array('a' => 1))).'_file.'.self::VALID_EXT).'?some=value',
                false,
                new FileId('file.'.self::VALID_EXT, array('a' => 1), $filename),
            ),
        );
    }

    public function matchesDataProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/'.self::INVALID_CHECKSUM.'_somebase64_file.'.self::VALID_EXT,
                true,
            ),
            //invalid ext
            array(
                'some/dirs/to/ignore/'.self::INVALID_CHECKSUM.'_somebase64_file.'.self::INVALID_EXT,
                false,
            ),
            //checksum missing
            array(
                'some/dirs/to/ignore/somebase64_file.'.self::VALID_EXT,
                false
            ),
            array(
                'some/dirs/file.'.self::VALID_EXT,
                true,
            ),
            //query string is ignored
            array(
                'some/dirs/file.'.self::VALID_EXT.'?some=value',
                true,
            )
        );
    }
}
 