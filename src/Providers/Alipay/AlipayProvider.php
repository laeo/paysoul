<?php

namespace Doubear\Paysoul\Providers\Alipay;

use Doubear\Paysoul\Providers\Alipay\Channels\Scan;
use Doubear\Paysoul\Utils\SensitiveArray;
use RuntimeException;

class AlipayProvider
{
    /**
     * 注册收单接口
     *
     * @var array
     */
    protected $commands = [
        'alipay.scan' => Scan::class,
    ];

    /**
     * 渠道选择器
     *
     * @var string
     */
    protected $command;

    /**
     * 接口配置信息
     *
     * @var SensitiveArray
     */
    protected $config;

    /**
     * 构造支付宝提供商实例
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
        if (false === isset($this->$commands[$this->command])) {
            throw new RuntimeException('alipay channel called ' . $this->command . ' not found.');
        }

        $invoked = new $this->$commands[$this->command]($this->config);

        if (method_exists($invoked, $m)) {
            return call_user_func_array([$invoked, $m], $args);
        }

        throw new RuntimeException('unsupported alipay channel action ' . $m);
    }
}
