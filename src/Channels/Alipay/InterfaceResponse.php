<?php

namespace Doubear\Paysoul\Channels\Alipay;

use Doubear\Paysoul\Exceptions\ChannelRequestException;

class InterfaceResponse
{
    private $body;

    private $data;

    public function __construct(string $body)
    {
        $this->body = $body;

        $this->data = json_decode($body, true);

        if (!$this->data) {
            throw new ChannelRequestException('response data parses failed.');
        }
    }
}
