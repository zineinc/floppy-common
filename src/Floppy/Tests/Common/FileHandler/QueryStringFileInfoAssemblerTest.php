<?php


namespace Floppy\Tests\Common\FileHandler;


use Floppy\Common\FileHandler\FileInfo;
use Floppy\Common\FileHandler\QueryStringFileInfoAssembler;
use Floppy\Common\FileId;

class QueryStringFileInfoAssemblerTest extends AbstractFileInfoAssemblerTest
{

    protected function createAssembler()
    {
        return new QueryStringFileInfoAssembler(new \Floppy\Tests\Common\Stub\FilepathChoosingStrategy(self::PATH_PREFIX));
    }

    public function extractFileInfoProvider()
    {
        $encodedAttrs = base64_encode(json_encode(array('width' => 123)));
        return array(
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_file.jpg?attrs='.$encodedAttrs,
                new FileInfo(new FileId('file.jpg'), self::VALID_CHECKSUM, $encodedAttrs),
            ),
            //attrs are missing
            array(
                'some/dirs/to/ignore/'.self::VALID_CHECKSUM.'_file.jpg',
                null,
            ),
            array(
                //checksum missing
                'some/dirs/to/ignore/file.jpg?attrs='.$encodedAttrs,
                null,
            ),
            //accepts original file
            array(
                'some/dirs/file.jpg',
                new FileInfo(new FileId('file.jpg'), null, null),
            ),
            array(
                //query string is ignored
                'some/dirs/'.self::VALID_CHECKSUM.'_file.jpg?some=value&attrs='.$encodedAttrs,
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
                self::PATH_PREFIX.'/'.self::VALID_CHECKSUM.'_'.$id.'?attrs='.$encodedArgs,
            ),
            array(
                new FileInfo(new FileId($id), null, null),
                self::PATH_PREFIX.'/'.$id,
            )
        );
    }
}
 