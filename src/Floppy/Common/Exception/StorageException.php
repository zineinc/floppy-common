<?php

namespace Floppy\Common\Exception;

interface StorageException
{
    public function getMessage();

    public function getCode();
}