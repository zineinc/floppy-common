<?php

namespace Floppy\Common;

final class ErrorCodes
{
    const UNSUPPORTED_FILE_TYPE = 1;
    const FILE_NOT_FOUND = 2;
    const STORE_ERROR = 3;
    const IO = 4;
    const FILE_PROCESS_ERROR = 5;
    const INVALID_CHECKSUM = 6;
    const ACCESS_DENIED = 7;

    private static $map = null;

    /**
     * @param int $code
     *
     * @return string
     */
    public static function convertCodeToMessage($code)
    {
        if(self::$map === null) {
            self::buildMap();
        }

        if(!isset(self::$map[$code])) {
            return null;
        }

        return self::$map[$code];
    }

    private static function buildMap()
    {
        self::$map = array();

        $class = new \ReflectionClass(__CLASS__);
        $constants = $class->getConstants();

        foreach($constants as $name => $value) {
            self::$map[$value] = strtolower(str_replace('_', '-', $name));
        }
    }
}