<?php


namespace ZineInc\Storage\Tests\Common\FileHandler;


use ZineInc\Storage\Common\ChecksumChecker;
use ZineInc\Storage\Common\FileHandler\ImagePathGenerator;
use ZineInc\Storage\Common\FileId;
use ZineInc\Storage\Common\Storage\FilepathChoosingStrategy;

class ImagePathGeneratorTest extends \PHPUnit_Framework_TestCase
{
    const CHECKSUM = 'abcddfsdaf';
    const PATH_PREFIX = 'some/path/prefix';

    private $generator;
    private $checksumChecker;
    private $filepathChoosingStrategy;

    protected function setUp()
    {
        $this->checksumChecker = $this->getMock('ZineInc\Storage\Common\ChecksumChecker');
        $this->filepathChoosingStrategy = new \ZineInc\Storage\Tests\Common\Stub\FilepathChoosingStrategy(self::PATH_PREFIX);
        $this->generator = new ImagePathGenerator($this->checksumChecker, $this->filepathChoosingStrategy);
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function testGeneratePath($fileId, $expectedPath)
    {
        //given

        $this->checksumChecker->expects($this->any())
            ->method('generateChecksum')
            ->with(array(
                (string) $fileId->attributes()->get('width'),
                (string) $fileId->attributes()->get('height'),
                (string) $fileId->attributes()->get('cropBackgroundColor', 'ffffff'),
                (string) ($fileId->attributes()->get('crop', false) ? 1 : 0),
                $fileId->id(),
            ))
            ->will($this->returnValue(self::CHECKSUM));

        //when

        $actualPath = $this->generator->generate($fileId);

        //then

        $this->verifyMockObjects();
        $this->assertEquals($expectedPath, $actualPath);
    }

    public function dataProvider()
    {
        return array(
            array(
                new FileId('someid.jpg', array('width' => 100, 'height' => 80)),
                self::PATH_PREFIX.'/'.self::CHECKSUM.'_100_80_ffffff_0_someid.jpg',
            ),
            array(
                new FileId('someid2.jpg', array('width' => 100, 'height' => 80, 'crop' => true, 'cropBackgroundColor' => 'eeeeee')),
                self::PATH_PREFIX.'/'.self::CHECKSUM.'_100_80_eeeeee_1_someid2.jpg',
            ),
        );
    }
}
 