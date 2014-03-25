<?php


namespace Floppy\Tests\Common;

use Floppy\Common\ErrorCodes;

class ErrorCodesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function convertErrorCodeToMessage($code, $expectedMessage)
    {
        //when

        $actualMessage = ErrorCodes::convertCodeToMessage($code);

        //then

        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function dataProvider()
    {
        return array(
            array(ErrorCodes::ACCESS_DENIED, 'access-denied'),
            array(ErrorCodes::UNSUPPORTED_FILE_TYPE, 'unsupported-file-type'),
        );
    }
}
 