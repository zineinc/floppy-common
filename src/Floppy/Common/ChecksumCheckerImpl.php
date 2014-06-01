<?php

namespace Floppy\Common;

class ChecksumCheckerImpl implements ChecksumChecker
{
    private $secretKey;
    private $checksumLength;

    public function __construct($secretKey, $checksumLength = -1)
    {
        $this->secretKey = $secretKey;
        $this->checksumLength = (int)$checksumLength;
    }

    public function isChecksumValid($checksum, $data)
    {
        //TODO: safe string comparison?
        return $checksum === $this->generateChecksum($data);
    }

    public function generateChecksum($data)
    {
        $checksum = md5(json_encode($this->fixData($data)) . $this->secretKey);

        return $this->checksumLength > 0 ? substr($checksum, 0, $this->checksumLength) : $checksum;
    }

    protected function fixData($data)
    {
        if(is_array($data)) {
            foreach($data as $key => $value) {
                unset($data[$key]);
                $data[$this->fixData($key)] = $this->fixData($value);
            }

            return $data;
        } else {
            return (string) $data;
        }
    }
}