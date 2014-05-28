<?php


namespace Floppy\Common;

final class StringUtils {
    private function __construct() {
        throw new \BadMethodCallException();
    }

    public static function endsWith($string, $suffix) {
        return substr($string, strlen($string) - strlen($suffix)) === $suffix;
    }
} 