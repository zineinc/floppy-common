<?php

namespace Floppy\Common;

interface ChecksumChecker
{
    /**
     * @param string $checksum
     * @param mixed $data
     *
     * @return boolean
     */
    public function isChecksumValid($checksum, $data);

    /**
     * @param mixed $data
     *
     * @return string
     */
    public function generateChecksum($data);
}