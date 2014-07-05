<?php


namespace Floppy\Tests\Common\FileHandler;


use Floppy\Common\FileHandler\FileInfo;
use Floppy\Common\FileHandler\FilenameFileInfoAssembler;
use Floppy\Common\FileId;

class FilenameFileInfoAssemblerTest extends AbstractFileInfoAssemblerTest
{
    protected function createAssembler()
    {
        return new FilenameFileInfoAssembler(new \Floppy\Tests\Common\Stub\FilepathChoosingStrategy(self::PATH_PREFIX));
    }

    public function extractFileInfoProvider()
    {
        return array(
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_'.($encodedAttrs = base64_encode(json_encode(array('width' => 123)))).'_file.jpg',
                new FileInfo(new FileId('file.jpg'), self::VALID_CHECKSUM, $encodedAttrs),
            ),
            array(
                //checksum missing
                'some/dirs/to/ignore/'.base64_encode(json_encode(array())).'_file.jpg',
                null,
            ),
            //accepts original file
            array(
                'some/dirs/file.jpg',
                new FileInfo(new FileId('file.jpg'), null, null),
            ),
            array(
                //query string is ignored
                'some/dirs/'.self::VALID_CHECKSUM.'_'.($encodedAttrs = base64_encode(json_encode(array('a' => 1)))).'_file.jpg?some=value',
                new FileInfo(new FileId('file.jpg'), self::VALID_CHECKSUM, $encodedAttrs),
            ),
        );
    }

    public function assembleFilepathProvider()
    {
        $id = 'some.jpg';
        $encodedArgs = base64_encode(json_encode(array('width' => 50, 'height' => 40)));

        return array(
            array(
                new FileInfo(new FileId($id), self::VALID_CHECKSUM, $encodedArgs),
                self::PATH_PREFIX.'/'.self::VALID_CHECKSUM.'_'.$encodedArgs.'_'.$id,
            ),
            array(
                new FileInfo(new FileId($id), null, null),
                self::PATH_PREFIX.'/'.$id,
            )
        );
    }
}
 