<?php

namespace Floppy\Tests\Common\FileHandler;

use PHPUnit_Framework_TestCase;
use Floppy\Tests\Common\Stub\ChecksumChecker;

abstract class AbstractPathMatcherTest extends PHPUnit_Framework_TestCase
{
    const VALID_CHECKSUM = 'validChecksum';
    const INVALID_CHECKSUM = 'invalidChecksum';

    private $matcher;

    protected function setUp()
    {
        $this->matcher = $this->createVariantMatcher(new ChecksumChecker(self::VALID_CHECKSUM));
    }

    protected abstract function createVariantMatcher(ChecksumChecker $checksumChecker);

    /**
     * @test
     * @dataProvider matchDataProvider
     */
    public function testMatch($variantFilename, $expectedException, $expectedFileId)
    {
        if ($expectedException) {
            $this->setExpectedException('Floppy\Common\FileHandler\Exception\PathMatchingException');
        }

        $actualFileId = $this->matcher->match($variantFilename);

        $this->assertEquals($expectedFileId, $actualFileId);
    }

    public abstract function matchDataProvider();

    /**
     * @test
     * @dataProvider matchesDataProvider
     */
    public function testMatches($variantFilename, $expectedMatches)
    {
        $this->assertEquals($expectedMatches, $this->matcher->matches($variantFilename));
    }

    public abstract function matchesDataProvider();

    /**
     * @test
     * @dataProvider matchesDataProvider
     */
    public function testMatch_throwExceptionWhenMatchesReturnFalse($variantFilepath, $expectedMatches)
    {
        if ($expectedMatches) {
            //skip, this tests only condition when PathMatcher::matches return false
            return;
        }

        if (!$expectedMatches) {
            $this->setExpectedException('Floppy\Common\FileHandler\Exception\PathMatchingException');
        }

        $this->matcher->match($variantFilepath);
    }
}