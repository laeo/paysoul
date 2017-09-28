<?php

namespace Doubear\Paysoul\Channels\Alipay;

use Closure;
use Doubear\Paysoul\Exceptions\ChannelRequestException;
use Doubear\Paysoul\Exceptions\InterfaceResponseException;

class InterfaceResponse
{
    private $body;

    private $data;

    public function __construct(string $body, Closure $cb)
    {
        $this->body = $body;

        $this->data = json_decode($body, true);

        if (!$this->data) {
            throw new ChannelRequestException('response data parses failed.');
        }

        if (false === $cb($this->data)) {
            throw new InterfaceResponseException('invalid notify message.');
        }

        $this->data = array_values($this->data)[0];

        if ($this->code !== '10000') {
            throw new InterfaceResponseException($this->sub_msg ?: $this->msg, $this->code);
        }
    }

    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function toArray()
    {
        return $this->data;
    }
}
