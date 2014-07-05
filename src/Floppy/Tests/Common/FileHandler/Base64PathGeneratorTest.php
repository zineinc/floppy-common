<?php


namespace Floppy\Tests\Common\FileHandler;


use Floppy\Common\FileHandler\Base64PathGenerator;
use Floppy\Common\FileHandler\FilenameFileInfoAssembler;
use Floppy\Common\FileId;

class Base64PathGeneratorTest extends \PHPUnit_Framework_TestCase
{
    const VALID_CHECKSUM = 'valid';
    const INVALID_CHECKSUM = 'invalid';
    const PATH_PREFIX = 'some/prefix';

    private $checksumChecker;

    protected function setUp()
    {
        $this->checksumChecker = $this->getMock('Floppy\Common\ChecksumChecker');
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function testGeneratePath(FileId $fileId, $expectedPath, array $attributeFilters = array(), array $filteredAttributes = null)
    {
        //given

        if($fileId->attributes()->all() || $filteredAttributes) {
            $this->checksumChecker->expects($this->atLeastOnce())
                ->method('generateChecksum')
                ->with(array($fileId->id()) + ($filteredAttributes ?: $fileId->attributes()->all()))
                ->will($this->returnValue(self::VALID_CHECKSUM));
        }

        //when

        $path = $this->createGenerator($attributeFilters)->generate($fileId);

        //then

        $this->assertEquals($expectedPath, $path);
    }

    public function dataProvider()
    {
        $id = 'some.jpg';
        $args = array('width' => 50, 'height' => 40);
        return array(
            array(
                new FileId($id, $args),
                self::PATH_PREFIX.'/'.self::VALID_CHECKSUM.'_'.base64_encode(json_encode($args)).'_'.$id,
            ),
            array(
                new FileId($id, $args),
                self::PATH_PREFIX.'/'.self::VALID_CHECKSUM.'_'.base64_encode(json_encode(array('width' => 51) + $args)).'_'.$id,
                array(
                    'width' => function($width) {
                        return $width+1;
                    }
                ),
                array(
                    'width' => 51, 'height' => 40
                )
            ),
            array(
                new FileId($id),
                self::PATH_PREFIX.'/'.$id,
            )
        );
    }

    /**
     * @return Base64PathGenerator
     */
    protected function createGenerator(array $attributeFilters)
    {
        return new Base64PathGenerator(
            $this->checksumChecker,
            new FilenameFileInfoAssembler(new \Floppy\Tests\Common\Stub\FilepathChoosingStrategy(self::PATH_PREFIX)),
            $attributeFilters
        );
    }
}
 