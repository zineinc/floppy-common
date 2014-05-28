<?php


namespace Floppy\Tests\Common;


use Floppy\Common\StringUtils;

class StringUtilsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @dataProvider endsWithProvider
     */
    public function testEndsWith($string, $suffix, $expected) {
        $this->assertEquals($expected, StringUtils::endsWith($string, $suffix));
    }

    public function endsWithProvider() {
        return array(
            array('some-string', 'string', true),
            array('some-string', 'strin', false),
            array('s', 'string', false),
            array('string', 'string', true),
        );
    }
}