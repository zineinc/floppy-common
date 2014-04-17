<?php


namespace Floppy\Common\Stream;


use Floppy\Common\Stream\Exception\IOException;

class LazyLoadedInputStream extends StringInputStream
{
    public function __construct($filepath)
    {
        parent::__construct(null, $filepath);
    }

    protected function getBytes()
    {
        if($this->bytes === null) {
            $this->bytes = $this->load();
        }

        return $this->bytes;
    }

    private function load()
    {
        $result = @file_get_contents($this->filepath());

        if($result === false) {
            throw new IOException(sprintf('File "%s" can not be loaded.', $this->filepath()));
        }

        return $result;
    }
}