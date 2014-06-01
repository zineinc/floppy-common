<?php


namespace Floppy\Tests\Common;


use Floppy\Common\ChecksumCheckerImpl;

class ChecksumCheckerImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChecksumCheckerImpl
     */
    private $checksumChecker;

    protected function setUp()
    {
        $this->checksumChecker = new ChecksumCheckerImpl('secretKey');
    }

    /**
     * @test
     */
    public function everyChecksumGenerationWithTheSameDataResultsTheSameChecksum()
    {
        $data = array('some' => 'value');

        $this->assertEquals($this->checksumChecker->generateChecksum($data), $this->checksumChecker->generateChecksum($data));
    }

    /**
     * @test
     */
    public function checksumGenerationWithDifferentDataResultsDifferentChecksum()
    {
        $this->assertNotEquals($this->checksumChecker->generateChecksum('a'), $this->checksumChecker->generateChecksum('b'));
    }

    /**
     * @test
     */
    public function givenDataIsArray_typesOfArrayPrimitivesShouldBeIgnored()
    {
        $this->assertEquals(
            $this->checksumChecker->generateChecksum(array('attr' => 5)),
            $this->checksumChecker->generateChecksum(array('attr' => '5'))
        );
    }

    /**
     * @test
     */
    public function givenDataIsMultidimensionalArray_typesOfArrayPrimitivesShouldBeIgnored()
    {
        $this->assertEquals(
            $this->checksumChecker->generateChecksum(array('attr1' => 5, 'attr2' => array('2'), '3' => '5')),
            $this->checksumChecker->generateChecksum(array('attr1' => '5', 'attr2' => array(2), 3 => 5))
        );
    }

    /**
     * @test
     */
    public function givenDataIsArray_twoArraysHaveALittleDifference_checksumShouldBeDifferent()
    {
        $this->assertNotEquals(
            $this->checksumChecker->generateChecksum(array('some1' => 'value', 'some2' => 'value')),
            $this->checksumChecker->generateChecksum(array('some1' => 'value', 'some2' => 'value2'))
        );
    }
}
 