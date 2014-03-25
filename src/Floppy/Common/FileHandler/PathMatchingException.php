<?php

namespace Floppy\Common\FileHandler;

use Exception;
use Floppy\Common\ErrorCodes;
use Floppy\Common\StorageException;

class PathMatchingException extends \Exception implements StorageException
{
    public function __construct($message = "", $code = ErrorCodes::INVALID_CHECKSUM, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}