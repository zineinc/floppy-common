<?php

namespace Floppy\Common\FileHandler;

use Floppy\Common\FileId;
use Floppy\Common\ChecksumChecker;

class ImagePathMatcher implements PathMatcher
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

        $parsedUrl = parse_url($variantFilepath);
        $filename = $parsedUrl['path'];

        $params = explode('_', $filename);

        if (count($params) !== $this->getSupportedParamsCount()) {
            throw new PathMatchingException(sprintf('Invalid variant filepath format, given: "%s"', $variantFilepath));
        }

        $checksum = array_shift($params);

        if (!$this->checksumChecker->isChecksumValid($checksum, $params)) {
            throw new PathMatchingException(sprintf('checksum is invalid for variant: "%s"', $variantFilepath));
        }

        $id = array_pop($params);

        if(!$this->isExtensionSupported($id)) {
            throw new PathMatchingException(sprintf('File with given extension is unsupported, supported extensions: "%s", given filename: "%s"',
                implode(', ', $this->supportedExtensions), $id));
        }

        return new FileId($id, $this->getAttrributes($params), $filename);
    }

    private function isExtensionSupported($filepath)
    {
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        return $this->supportedExtensions && in_array($extension, $this->supportedExtensions);
    }

    protected function getAttrributes(array $params)
    {
        return array(
            'width' => (int)$params[0],
            'height' => (int)$params[1],
            'cropBackgroundColor' => $params[2],
            'crop' => (boolean) $params[3],
        );
    }

    /**
     * @param $variantFilepath
     *
     * @return boolean
     */
    public function matches($variantFilepath)
    {
        if(!$this->isExtensionSupported($variantFilepath)) return false;

        $variantFilepath = basename($variantFilepath);

        $params = explode('_', $variantFilepath);

        return count($params) === $this->getSupportedParamsCount();
    }

    protected function getSupportedParamsCount()
    {
        return 6;
    }
}