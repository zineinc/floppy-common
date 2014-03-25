<?php

namespace Floppy\Common;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Floppy\Common\Stream\InputStream;
use Floppy\Common\Stream\LazyLoadedInputStream;

final class FileSource
{
    private $stream;
    private $fileType;

    /**
     * @param \SplFileInfo $file
     *
     * @return FileSource
     */
    public static function fromFile(\SplFileInfo $file)
    {
        $extension = $file instanceof UploadedFile ? $file->getClientOriginalExtension() : self::getExtension($file);

        return new self(new LazyLoadedInputStream($file->getPathname()), new FileType(self::guessMimeType($file), strtolower($extension)));
    }

    private static function guessMimeType(\SplFileInfo $file)
    {
        if($file instanceof File) {
            return $file->getMimeType();
        }

        if(function_exists('finfo_open')) {
            $finfo = new \finfo(\FILEINFO_MIME_TYPE);

            return $finfo->file($file->getPathname());
        }

        return 'application/octet-stream';
    }

    private static function getExtension(\SplFileInfo $file)
    {
        if(\is_callable(array($file, 'getExtension'))) {
            return $file->getExtension();
        }

        //fallback for < php 5.3.6
        return \pathinfo($file->getBasename(), PATHINFO_EXTENSION);
    }

    public function __construct(InputStream $stream, FileType $fileType = null)
    {
        $this->stream = $stream;
        $this->fileType = $fileType ?: new FileType(null, null);
    }

    /**
     * @return FileType
     */
    public function fileType()
    {
        return $this->fileType;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->stream->read();
    }

    public function filepath()
    {
        return $this->stream->filepath();
    }

    public function discard()
    {
        $this->stream->close();
    }
}