<?php


namespace ZineInc\Storage\Tests\Common\FileHandler;


use ZineInc\Storage\Common\FileHandler\FilePathGenerator;
use ZineInc\Storage\Common\FileId;

class FilePathGeneratorTest extends \PHPUnit_Framework_TestCase
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
        $this->generator = new FilePathGenerator($this->checksumChecker, $this->filepathChoosingStrategy);
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function testGeneratePath($fileId, $expectedPath, $expectedProcessedName = null)
    {
        //given

        $name = $expectedProcessedName ?: $fileId->attributes()->get('name');

        $this->checksumChecker->expects($this->any())
            ->method('generateChecksum')
            ->with(array(
                $fileId->id(),
                $name,
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
                new FileId('some.doc', array('name' => 'doc')),
                '/'.self::PATH_PREFIX.'/some.doc?name=doc&checksum='.self::CHECKSUM,
            ),
            //urlify utf-8 chars in filename
            array(
                new FileId('some.doc', array('name' => 'some utf8 chars: ąśćół')),
                '/'.self::PATH_PREFIX.'/some.doc?name=some-utf8-chars-ascol&checksum='.self::CHECKSUM,
                'some-utf8-chars-ascol',
            ),
        );
    }

}
 