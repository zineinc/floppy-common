<?php


namespace Floppy\Common\FileHandler;


use Floppy\Common\FileId;
use Floppy\Common\Storage\FilepathChoosingStrategy;

abstract class AbstractFileInfoAssembler implements FileInfoAssembler
{
    protected $filepathChoosingStrategy;

    public function __construct(FilepathChoosingStrategy $filepathChoosingStrategy)
    {
        $this->filepathChoosingStrategy = $filepathChoosingStrategy;
    }

    public function assembleFileInfo(FileInfo $fileInfo)
    {
        if($fileInfo->encodedAttributes()) {
            return $this->doAssembleFileInfo($fileInfo);
        } else {
            return sprintf('%s/%s',
                $this->filepathChoosingStrategy->filepath($fileInfo->fileId()),
                $fileInfo->fileId()->id()
            );
        }
    }

    abstract protected function doAssembleFileInfo(FileInfo $fileInfo);

    /**
     * @param $filepath
     * @return FileInfo
     */
    public function extractFileInfo($filepath)
    {
        $params = $this->extractParams($filepath);

        if(count($params) === 1) {
            return new FileInfo(new FileId($params[0]), null, null);
        }

        list($checksum, $id, $encodedAttrs) = $params;

        return new FileInfo(new FileId($id), $checksum, $encodedAttrs);
    }

    /**
     * @param $filepath
     * @return array Array of params: [checksum, id, encodedAttrs] or [id]
     */
    abstract protected function extractParams($filepath);
}