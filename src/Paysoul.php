<?php

namespace Doubear\Paysoul;

use Doubear\Paysoul\Channels\Alipay\AlipayChannel;
use Doubear\Paysoul\Exceptions\ChannelNotFoundException;
use Doubear\Paysoul\Utils\ConfigSet;

class Paysoul
{
    /**
     * 注册支付渠道入口类
     *
     * @var array
     */
    protected $channels = [
        'alipay' => AlipayChannel::class,
    ];

    /**
     * 支付渠道配置数据
     *
     * @var ConfigSet
     */
    protected $config = [];

    /**
     * 使用给定配置信息构造 Paysoul 实例
     *
     * @param array $config 支付渠道配置数据
     */
    public function __construct(array $config)
    {
        $this->config = new ConfigSet($config);
    }

    /**
     * 动态创建渠道接口实例
     *
     * @param  string $channel 渠道名称
     *
     * @throws Paysoul\Exceptions\ChannelNotFoundException
     *
     * @return Paysoul\Contracts\Channel
     */
    public function channel($channel)
    {
        $c      = $this->clean($channel);
        $config = $this->config->get($c, []);

        if (isset($this->channels[$c])) {
            return new $this->channels[$c](
                $channel
                , new ConfigSet($config)
            );
        }

        throw new ChannelNotFoundException($c);
    }

    private function clean(string $channel): string
    {
        if (false !== $pos = strpos($channel, '.')) {
            return substr($channel, 0, $pos);
        }

        return $channel;
    }
}
