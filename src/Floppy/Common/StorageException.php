<?php

namespace Floppy\Common;

interface StorageException
{
    public function getMessage();

    public function getCode();
}