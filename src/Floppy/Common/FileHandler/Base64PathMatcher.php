<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileHandler\Exception\PathMatchingException;
use Floppy\Common\FileId;

class Base64PathMatcher implements PathMatcher
{
    private $checksumChecker;
    private $extensions;

    public function __construct(ChecksumChecker $checksumChecker, array $extensions)
    {
        $this->checksumChecker = $checksumChecker;
        $this->extensions = $extensions;
    }

    public function match($filepath)
    {
        $filename = $this->resolveFilename($filepath);

        if(!$this->isExtensionSupported($filename)) {
            throw new PathMatchingException(sprintf('File with given extension is unsupported, supported extensions: "%s", given filename: "%s"',
                implode(', ', $this->extensions), $filename));
        }

        $params = explode('_', $filename);

        if(!in_array(count($params), array(1, 3))) {
            throw new PathMatchingException(sprintf('Malformed filename, it should be composed by 3 parts separated by "_", filename: '.$filename));
        }

        if(count($params) === 1) {
            return new FileId($params[0]);
        }

        list($checksum, $encodedAttrs, $id) = $params;

        $json = base64_decode($encodedAttrs);

        if($json === false) {
            throw new PathMatchingException(sprintf('Malformed arguments for file: %s', $filename));
        }

        $attributes = json_decode($json, true);

        if($attributes === null || !is_array($attributes)) {
            throw new PathMatchingException(sprintf('Malformed arguments for file: %s', $filename));
        }

        $attrsToSign = $attributes;
        $attrsToSign[] = $id;

        if(!$this->checksumChecker->isChecksumValid($checksum, $attrsToSign)) {
            throw new PathMatchingException(sprintf('checksum is invalid for file: "%s"', $filename));
        }

        return new FileId($id, $attributes);
    }

    private function resolveFilename($filepath)
    {
        $parsedUrl = parse_url(basename($filepath));
        $filename = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        return $filename;
    }

    public function matches($variantFilepath)
    {
        return $this->isExtensionSupported($variantFilepath) && in_array(count(explode('_', $variantFilepath)), array(1, 3));
    }

    private function isExtensionSupported($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        return in_array($ext, $this->extensions);
    }
}