<?php

namespace Doubear\Paysoul\Channels\Alipay;

use GuzzleHttp\Client;

class InterfaceRequest
{
    protected $target;

    protected $queries = [];

    public function __construct($target, array $queries = [])
    {
        $this->target  = $target;
        $this->queries = $queries;
    }

    public function setQuery(string $key, string $value)
    {
        $this->queries[$key] = $value;
    }

    protected function getFinallyURL(): string
    {
        return $this->target . '?' . http_build_query($this->queries);
    }

    public function execute(): InterfaceResponse
    {
        $response = (new Client())->get($this->getFinallyURL());
        return new InterfaceResponse((string) $response->getBody());
    }
}
