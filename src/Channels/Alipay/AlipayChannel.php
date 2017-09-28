<?php

namespace Paysoul\Channels\Alipay;

use Paysoul\Contracts\Channel;
use Paysoul\Contracts\Transaction;
use Paysoul\Exceptions\ChannelInterfaceNotFoundException;
use Paysoul\Utils\ConfigSet;

class AlipayChannel implements Channel
{
    /**
     * 注册收单接口
     *
     * @var array
     */
    protected $interfaces = [
        'alipay.scan' => Paysoul\Channels\Alipay\Interfaces\AlipayTradeScan::class,
    ];

    protected $channel;

    /**
     * 接口配置信息
     *
     * @var ConfigSet
     */
    protected $config;

    /**
     * 构造支付宝分发实例
     *
     * @param string $channel
     * @param array  $config
     */
    public function __construct(string $channel, ConfigSet $config)
    {
        $this->channel = $channel;
        $this->config  = $config;
    }

    /**
     * 分发支付宝订单到对应接口
     *
     * @param  Transaction $trans
     *
     * @throws ChannelInterfaceNotFoundException
     *
     * @return mixed
     */
    public function deal(Transaction $trans)
    {
        if (false === isset($this->interfaces[$this->channel])) {
            throw new ChannelInterfaceNotFoundException($this->channel);
        }

        $channelInterface = new $this->interfaces[$this->channel]($this->config);

        return $channelInterface->deal($trans);
    }
}
