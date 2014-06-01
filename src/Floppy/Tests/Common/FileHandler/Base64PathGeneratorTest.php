<?php


namespace Floppy\Tests\Common\FileHandler;


use Floppy\Common\FileHandler\Base64PathGenerator;
use Floppy\Common\FileId;

class Base64PathGeneratorTest extends \PHPUnit_Framework_TestCase
{
    const VALID_CHECKSUM = 'valid';
    const INVALID_CHECKSUM = 'invalid';
    const PATH_PREFIX = 'some/prefix';

    private $generator;
    private $checksumChecker;

    protected function setUp()
    {
        $this->checksumChecker = $this->getMock('Floppy\Common\ChecksumChecker');
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function testGeneratePath(FileId $fileId, $expectedPath, array $attributeFilters = array())
    {
        //given

        $this->checksumChecker->expects($this->atLeastOnce())
            ->method('generateChecksum')
            ->with(array($fileId->id()) + $fileId->attributes()->all())
            ->will($this->returnValue(self::VALID_CHECKSUM));

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
                )
            ),
        );
    }

    /**
     * @return Base64PathGenerator
     */
    protected function createGenerator(array $attributeFilters)
    {
        return new Base64PathGenerator($this->checksumChecker, new \Floppy\Tests\Common\Stub\FilepathChoosingStrategy(self::PATH_PREFIX), $attributeFilters);
    }
}
 