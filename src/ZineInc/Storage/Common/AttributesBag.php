<?php

namespace ZineInc\Storage\Common;

class AttributesBag
{
    private $attrs;

    public function __construct(array $attrs)
    {
        $this->attrs = $attrs;
    }

    public function get($name, $default = null)
    {
        return isset($this->attrs[$name]) ? $this->attrs[$name] : $default;
    }

    public function all()
    {
        return $this->attrs;
    }
}