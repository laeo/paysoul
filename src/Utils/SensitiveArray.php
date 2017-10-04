<?php

namespace Doubear\Paysoul\Utils;

use ArrayAccess;
use RuntimeException;

class SensitiveArray implements ArrayAccess
{
    private $__sensitive;
    private $__data = [];

    public function __construct(array $data, $sensitive = true)
    {
        $this->__data      = $data;
        $this->__sensitive = $sensitive;
    }

    public function set(string $key, $value)
    {
        $this->__data[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->__data);
    }

    public function get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return $this->__data[$key];
        }

        if ($this->__sensitive) {
            throw new RuntimeException('unknown array index name "' . $key . '"');
        }

        return $default;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->__data[] = $value;
        } else {
            $this->__data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->__data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->__data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function __get($offset)
    {
        return $this->get($offset);
    }

    public function __set($offset, $value)
    {
        $this->__data[$offset] = $value;
    }

    public function toArray()
    {
        return $this->__data;
    }

    public function toJson()
    {
        return json_encode($this->__data);
    }
}
