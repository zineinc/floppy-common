<?php


namespace Floppy\Common\FileHandler;


final class UrlUtils
{
    private function __construct()
    {}

    public static function resolveFilename($filepath)
    {
        $parsedUrl = parse_url(basename($filepath));
        $filename = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        return $filename;
    }
} 