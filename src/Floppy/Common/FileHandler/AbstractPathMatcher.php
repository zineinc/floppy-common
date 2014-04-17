<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\ChecksumChecker;
use Floppy\Common\FileHandler\Exception\PathMatchingException;
use Floppy\Common\FileId;

abstract class AbstractPathMatcher implements PathMatcher
{
    private $checksumChecker;
    private $supportedExtensions;

    public function __construct(ChecksumChecker $checksumChecker, array $supportedExtensions = array())
    {
        $this->checksumChecker = $checksumChecker;
        $this->supportedExtensions = $supportedExtensions;
    }

    public function match($variantFilepath)
    {
        $variantFilepath = basename($variantFilepath);

        $filename = $this->resolveFilename($variantFilepath);

        $params = explode('_', $filename);

        if(count($params) > 1) {
            if (count($params) !== $this->getSupportedParamsCount()) {
                throw new PathMatchingException(sprintf('Invalid variant filepath format, given: "%s"', $variantFilepath));
            }

            $checksum = array_shift($params);

            if (!$this->checksumChecker->isChecksumValid($checksum, $params)) {
                throw new PathMatchingException(sprintf('checksum is invalid for variant: "%s"', $variantFilepath));
            }

            $variantAttrs = $this->getAttrributes($params);
        } else {
            $variantAttrs = array();
        }

        $id = array_pop($params);

        if(!$this->isExtensionSupported($id)) {
            throw new PathMatchingException(sprintf('File with given extension is unsupported, supported extensions: "%s", given filename: "%s"',
                implode(', ', $this->supportedExtensions), $id));
        }


        return new FileId($id, $variantAttrs, $filename);
    }

    private function isExtensionSupported($filepath)
    {
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        return $this->supportedExtensions && in_array($extension, $this->supportedExtensions);
    }

    abstract protected function getAttrributes(array $params);

    /**
     * @param $filename
     *
     * @return boolean
     */
    public function matches($filename)
    {
        $filename = $this->resolveFilename($filename);

        if(!$this->isExtensionSupported($filename)) return false;

        $params = explode('_', $filename);
        $paramsCount = count($params);

        return $paramsCount === 1 || $paramsCount === $this->getSupportedParamsCount();
    }

    abstract protected function getSupportedParamsCount();

    private function resolveFilename($filepath)
    {
        $parsedUrl = parse_url($filepath);
        $filename = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        return $filename;
    }
} 