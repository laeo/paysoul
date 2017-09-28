<?php

namespace Paysoul\Channels\Alipay;

use Paysoul\Contracts\Transaction;

class UniformRequestBody
{
    private $body = [];

    public function __construct(Transaction $trans)
    {
        $trans->mergeTo($this);
    }

    public function append($key, $value)
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
