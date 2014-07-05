<?php


namespace Floppy\Tests\Common\FileHandler;


use Floppy\Common\FileHandler\FileInfo;
use Floppy\Common\FileHandler\FileInfoAssembler;

abstract class AbstractFileInfoAssemblerTest extends \PHPUnit_Framework_TestCase
{
    const PATH_PREFIX = 'some/prefix';
    const VALID_CHECKSUM = 'validChecksum';

    /**
     * @var FileInfoAssembler
     */
    private $assembler;

    protected function setUp()
    {
        $this->assembler = $this->createAssembler();
    }

    abstract protected function createAssembler();

    /**
     * @test
     * @dataProvider extractFileInfoProvider
     */
    public function extractFileInfo($filepath, $expectedFileInfo)
    {
        if($expectedFileInfo === null) {
            $this->setExpectedException('Floppy\Common\FileHandler\Exception\PathMatchingException');
        }

        //when

        $actualFileInfo = $this->assembler->extractFileInfo($filepath);


        //then

        $this->assertEquals($expectedFileInfo, $actualFileInfo);
    }

    abstract public function extractFileInfoProvider();

    /**
     * @test
     * @dataProvider assembleFilepathProvider
     */
    public function assembleFilepath(FileInfo $fileInfo, $expectedFilepath)
    {
        $actualFilepath = $this->assembler->assembleFileInfo($fileInfo);

        $this->assertEquals($expectedFilepath, $actualFilepath);
    }

    abstract public function assembleFilepathProvider();
} 