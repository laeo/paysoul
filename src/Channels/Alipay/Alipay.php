<?php

namespace Doubear\Paysoul\Channels\Alipay;

use Doubear\Paysoul\Channels\Alipay\Interfaces\ScanInterface;
use Doubear\Paysoul\Exceptions\ChannelInterfaceNotFoundException;
use Doubear\Paysoul\Exceptions\UnsupportedActionException;
use Doubear\Paysoul\Utils\SensitiveArray;

class Alipay
{
    /**
     * 注册收单接口
     *
     * @var array
     */
    protected $interfaces = [
        'alipay.scan' => ScanInterface::class,
    ];

    protected $channel;

    /**
     * 接口配置信息
     *
     * @var SensitiveArray
     */
    protected $config;

    /**
     * 构造支付宝分发实例
     *
     * @param string $channel
     * @param array  $config
     */
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

        throw new UnsupportedActionException('unsupported alipay channel action ' . $m);
    }
}
