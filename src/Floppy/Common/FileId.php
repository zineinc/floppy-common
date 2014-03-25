<?php

namespace Floppy\Common;

final class FileId
{
    private $id;
    private $attributes;
    private $filename;

    public function __construct($id, array $attributes = array(), $filename = null)
    {
        $this->id = (string)$id;
        $this->attributes = new AttributesBag($attributes);
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return AttributesBag
     */
    public function attributes()
    {
        return $this->attributes;
    }

    public function filename()
    {
        return $this->filename ?: $this->id;
    }

    public function isVariant()
    {
        return count($this->attributes->all()) > 0 || $this->filename() !== $this->id;
    }

    /**
     * Creates variant with given attributes of this file
     *
     * @param array $attrs Variant attributes - it might be image size, file name etc.
     *
     * @return FileId
     */
    public function variant(array $attrs)
    {
        return new self($this->id, $attrs);
    }

    /**
     * Creates id to original file.
     *
     * @return FileId
     */
    public function original()
    {
        return new self($this->id);
    }
}