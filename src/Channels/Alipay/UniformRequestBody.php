<?php

namespace Doubear\Paysoul\Channels\Alipay;

use Doubear\Paysoul\Contracts\Transaction;
use Doubear\Paysoul\Contracts\UniformRequestBody as UniformRequestBodyContract;

class UniformRequestBody implements UniformRequestBodyContract
{
    private $body = [];

    public function __construct(Transaction $trans)
    {
        $trans->mergeTo($this);
    }

    public function append(string $key, string $value)
    {
        $this->body[$key] = $value;
    }

    public function map(array $body)
    {
        $this->body = array_merge($this->body, $body);
    }

    public function toArray(): array
    {
        return $this->body;
    }

    public function toString()
    {
        return json_encode($this->body);
    }
}
