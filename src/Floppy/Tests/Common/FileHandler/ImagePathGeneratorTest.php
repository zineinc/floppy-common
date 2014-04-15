<?php


namespace Floppy\Tests\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileHandler\ImagePathGenerator;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

class ImagePathGeneratorTest extends \PHPUnit_Framework_TestCase
{
    const CHECKSUM = 'abcddfsdaf';
    const PATH_PREFIX = 'some/path/prefix';

    private $generator;
    private $checksumChecker;
    private $filepathChoosingStrategy;

    protected function setUp()
    {
        $this->checksumChecker = $this->getMock('Floppy\Common\ChecksumChecker');
        $this->filepathChoosingStrategy = new \Floppy\Tests\Common\Stub\FilepathChoosingStrategy(self::PATH_PREFIX);
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
            //parameters should be omitted for original file
            array(
                new FileId('someid.jpg'),
                self::PATH_PREFIX.'/someid.jpg',
            )
        );
    }
}
 