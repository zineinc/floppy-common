<?php


namespace Floppy\Tests\Common;


use Symfony\Component\HttpFoundation\File\File;
use Floppy\Common\FileSource;
use Floppy\Common\FileType;

class FileSourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testCreateFromFile()
    {
        //given

        $filepath = __DIR__.'/../Resources/text.txt';
        $file = new \SplFileInfo($filepath);

        //when

        $fileSource = FileSource::fromFile($file);

        //then
        $expectedContent = file_get_contents($filepath);
        $this->assertEquals($expectedContent, $fileSource->content());
        $this->assertEquals(new \Floppy\Common\FileType('text/plain', 'txt'), $fileSource->fileType());
    }
}
 