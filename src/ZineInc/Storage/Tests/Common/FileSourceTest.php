<?php


namespace ZineInc\Storage\Tests\Common;


use Symfony\Component\HttpFoundation\File\File;
use ZineInc\Storage\Common\FileSource;
use ZineInc\Storage\Common\FileType;

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
        $this->assertEquals(new \ZineInc\Storage\Common\FileType('text/plain', 'txt'), $fileSource->fileType());
    }
}
 