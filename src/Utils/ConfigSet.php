<?php

namespace Paysoul\Utils;

class ConfigSet
{
    private $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
