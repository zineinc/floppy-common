<?php

namespace Floppy\Common\FileHandler\Exception;

use Exception;
use Floppy\Common\Exception\StorageException;

class PathMatchingException extends \Exception implements StorageException
{
}