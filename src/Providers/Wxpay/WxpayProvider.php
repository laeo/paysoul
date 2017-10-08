<?php

namespace Doubear\Paysoul\Providers\Wxpay;

use Doubear\Paysoul\Providers\Wxpay\Channels\Mweb;
use Doubear\Paysoul\Providers\Wxpay\Channels\Scan;
use Doubear\Paysoul\Utils\SensitiveArray;
use RuntimeException;

class WxpayProvider
{
    /**
     * 微信支付渠道注册表
     *
     * @var string
     */
    protected $commands = [
        'wxpay.scan' => Scan::class,
        'wxpay.mweb' => Mweb::class,
    ];

    /**
     * 微信支付渠道选择器
     *
     * @var string
     */
    protected $command;

    /**
     * 微信支付配置信息
     *
     * @var SensitiveArray
     */
    protected $config;

    /**
     * 构造微信提供商实例
     *
     * @param string         $command 渠道选择器
     * @param SensitiveArray $config  配置信息
     */
    public function __construct(string $command, SensitiveArray $config)
    {
        $this->command = $command;
        $this->config  = $config;
    }

    public function __call($m, $args)
    {
        if (false === isset($this->commands[$this->command])) {
            throw new RuntimeException('wxpay channel called ' . $this->command . ' not found.');
        }

        $invoked = new $this->commands[$this->command]($this->config);

        if (method_exists($invoked, $m)) {
            return call_user_func_array([$invoked, $m], $args);
        }

        throw new RuntimeException('unsupported wxpay channel action ' . $m);
    }
}
