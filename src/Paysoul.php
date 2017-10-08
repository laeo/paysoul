<?php

namespace Doubear\Paysoul;

use Doubear\Paysoul\Channels\Alipay\AlipayProvider;
use Doubear\Paysoul\Channels\Wxpay\WxpayProvider;
use Doubear\Paysoul\Utils\SensitiveArray;
use RuntimeException;

class Paysoul
{
    /**
     * 注册支付渠道入口类
     *
     * @var array
     */
    protected $channels = [
        'alipay' => AlipayProvider::class,
        'wxpay'  => WxpayProvider::class,
    ];

    /**
     * 支付渠道配置数据
     *
     * @var SensitiveArray
     */
    protected $config = [];

    /**
     * 使用给定配置信息构造 Paysoul 实例
     *
     * @param array $config 支付渠道配置数据
     */
    public function __construct(array $config)
    {
        $this->config = new SensitiveArray($config);
    }

    /**
     * 动态创建渠道接口实例
     *
     * @param  string $command 渠道调用指令
     *
     * @throws RuntimeException
     *
     * @return Paysoul\Contracts\Channel
     */
    public function channel($command)
    {
        $channel = $this->resolveProviderFrom($command);
        $config  = $this->config->get($channel, []);

        if (isset($this->channels[$channel])) {
            return new $this->channels[$channel](
                $command
                , new SensitiveArray($config)
            );
        }

        throw new RuntimeException('unknown provider called ' . $channel);
    }

    public function resolveProviderFrom(string $command): string
    {
        if (false !== $pos = strpos($command, '.')) {
            return substr($command, 0, $pos);
        }

        return $command;
    }
}
