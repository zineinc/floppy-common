<?php

namespace Floppy\Common\Stream\Exception;

use Exception;
use Floppy\Common\ErrorCodes;
use Floppy\Common\Exception\StorageException;

class IOException extends Exception implements StorageException
{
    public function __construct($message = null, Exception $previous = null)
    {
        parent::__construct($message, ErrorCodes::IO, $previous);
    }
}