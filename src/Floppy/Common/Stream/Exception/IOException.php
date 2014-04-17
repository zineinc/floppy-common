<?php

namespace Floppy\Common\Stream\Exception;

use Exception;
use Floppy\Common\Exception\StorageException;

class IOException extends Exception implements StorageException
{
}