<?php

namespace ZineInc\Storage\Common\Stream;

class StringInputStream implements InputStream
{
    protected $bytes;
    private $filepath;
    private $closed = false;

    public function __construct($buffer, $filepath = null)
    {
        $this->bytes = $buffer === null ? null : (string) $buffer;
        $this->filepath = $filepath;
    }

    public function close()
    {
        $this->closed = true;
    }

    public function read()
    {
        $this->ensureOpened();

        return $this->getBytes();
    }

    protected function getBytes()
    {
        return $this->bytes;
    }

    private function ensureOpened()
    {
        if ($this->closed) {
            throw new IOException('Stream is closed');
        }
    }

    public function filepath()
    {
        return $this->filepath;
    }
}