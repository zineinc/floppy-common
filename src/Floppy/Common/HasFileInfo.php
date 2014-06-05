<?php


namespace Floppy\Common;


interface HasFileInfo
{
    /**
     * @return AttributesBag
     */
    public function info();

    /**
     * Creates object with the same state but different file info
     *
     * @param array $info
     * @return HasFileInfo
     */
    public function withInfo(array $info);
} 