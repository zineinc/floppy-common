<?php

namespace Floppy\Common;

final class FileId implements HasFileInfo
{
    private $id;
    private $attributes;
    private $filename;
    private $info;

    public function __construct($id, array $attributes = array(), $filename = null, array $info = array())
    {
        $this->id = (string)$id;
        $this->attributes = new AttributesBag($attributes);
        $this->filename = $filename;
        $this->info = new AttributesBag($info);
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Attributes of this FileId, it have meaning to file identity
     *
     * @return AttributesBag
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Informations related to this FileId, it have no meaning to file identity
     *
     * @return AttributesBag
     */
    public function info()
    {
        return $this->info;
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
        return new self($this->id, $attrs, null, $this->info->all());
    }

    /**
     * Creates id to original file.
     *
     * @return FileId
     */
    public function original()
    {
        return new self($this->id, array(), null, $this->info->all());
    }

    /**
     * @param array $info
     * @return FileId
     */
    public function withInfo(array $info)
    {
        return new self($this->id, $this->attributes->all(), $this->filename, $info);
    }
}