<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\FileHandler\Exception\PathMatchingException;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

/**
 * FileInfoAssembler that extract or assemble file attributes from/to filename
 */
class FilenameFileInfoAssembler extends AbstractFileInfoAssembler
{
    protected function doAssembleFileInfo(FileInfo $fileInfo)
    {
        return sprintf('%s/%s_%s_%s',
            $this->filepathChoosingStrategy->filepath($fileInfo->fileId()),
            $fileInfo->checksum(),
            $fileInfo->encodedAttributes(),
            $fileInfo->fileId()->id()
        );
    }

    protected function extractParams($filepath)
    {
        $filename = UrlUtils::resolveFilename($filepath);

        $params = explode('_', $filename);

        if (!in_array(count($params), array(1, 3))) {
            throw new PathMatchingException(sprintf('Malformed filename, it should be composed by 3 parts separated by "_", filename: ' . $filename));
        }

        //change params order to order expected by superclass
        return count($params) === 1 ? $params : array($params[0], $params[2], $params[1]);
    }
}