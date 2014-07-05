<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\FileHandler\Exception\PathMatchingException;
use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

/**
 * FileInfoAssembler that extract or assemble file attributes from/to query string param
 */
class QueryStringFileInfoAssembler extends AbstractFileInfoAssembler
{
    protected function doAssembleFileInfo(FileInfo $fileInfo)
    {
        return sprintf('%s/%s_%s?attrs=%s',
            $this->filepathChoosingStrategy->filepath($fileInfo->fileId()),
            $fileInfo->checksum(),
            $fileInfo->fileId()->id(),
            $fileInfo->encodedAttributes()
        );
    }

    protected function extractParams($filepath)
    {
        $filename = UrlUtils::resolveFilename($filepath);
        $params = explode('_', $filename);

        $qs = parse_url($filepath, PHP_URL_QUERY);

        parse_str($qs, $qsParams);

        if(isset($qsParams['attrs'])) {
            $params[] = (string) $qsParams['attrs'];
        }

        if (!in_array(count($params), array(1, 3))) {
            throw new PathMatchingException(sprintf('Malformed filename, it should be composed by 2 parts separated by "_"
            and "attrs" query string parameter filepath: ' . $filepath));
        }

        return $params;
    }
}