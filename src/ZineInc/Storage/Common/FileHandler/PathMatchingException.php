<?php

namespace ZineInc\Storage\Common\FileHandler;

use Exception;
use ZineInc\Storage\Common\ErrorCodes;
use ZineInc\Storage\Common\StorageException;

class PathMatchingException extends \Exception implements StorageException
{
    public function __construct($message = "", $code = ErrorCodes::INVALID_CHECKSUM, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}