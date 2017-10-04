<?php

namespace Doubear\Paysoul\Channels\Wxpay;

use Doubear\Paysoul\Channels\Wxpay\Interfaces\ScanInterface;
use Doubear\Paysoul\Exceptions\ChannelInterfaceNotFoundException;
use Doubear\Paysoul\Exceptions\UnsupportedActionException;
use Doubear\Paysoul\Utils\SensitiveArray;

class Wxpay
{
    protected $interfaces = [
        'wxpay.scan' => ScanInterface::class,
    ];

    protected $channel;
    protected $config;

    public function __construct(string $channel, SensitiveArray $config)
    {
        $this->channel = $channel;
        $this->config  = $config;
    }

    protected function invoke()
    {
        if (false === isset($this->interfaces[$this->channel])) {
            throw new ChannelInterfaceNotFoundException($this->channel);
        }

        return new $this->interfaces[$this->channel]($this->config);
    }

    public function __call($m, $args)
    {
        $invoked = $this->invoke();
        if (method_exists($invoked, $m)) {
            return call_user_func_array([$invoked, $m], $args);
        }

        throw new UnsupportedActionException('unsupported wxpay channel action ' . $m);
    }
}
