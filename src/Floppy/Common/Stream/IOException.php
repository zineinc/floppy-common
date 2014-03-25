<?php

namespace Floppy\Common\Stream;

use Exception;
use Floppy\Common\ErrorCodes;
use Floppy\Common\StorageException;

class IOException extends Exception implements StorageException
{
    public function __construct($message = null, Exception $previous = null)
    {
        parent::__construct($message, ErrorCodes::IO, $previous);
    }
}