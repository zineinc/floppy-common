<?php

namespace Floppy\Tests\Common\Stub;

use Floppy\Common\ChecksumChecker as ChecksumCheckerInterface;

class ChecksumChecker implements ChecksumCheckerInterface
{
    private $validChecksum;

    public function __construct($validChecksum)
    {
        $this->validChecksum = $validChecksum;
    }

    public function isChecksumValid($checksum, $data)
    {
        return $checksum == $this->validChecksum;
    }

    public function generateChecksum($data)
    {
        return $this->validChecksum;
    }
}