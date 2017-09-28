<?php

namespace Doubear\Paysoul\Channels\Alipay\Interfaces;

use Doubear\Paysoul\Channels\Alipay\FakeOpenSSL;
use Doubear\Paysoul\Channels\Alipay\InterfaceRequest;
use Doubear\Paysoul\Channels\Alipay\UniformRequestBody;
use Doubear\Paysoul\Contracts\ChannelInterface;
use Doubear\Paysoul\Transaction;
use Doubear\Paysoul\Utils\ConfigSet;

abstract class AbstractAlipayTrade implements ChannelInterface
{
    /**
     * 正式环境的支付宝交易网关
     *
     * @var string
     */
    protected $gateway = 'https://openapi.alipay.com/gateway.do';

    /**
     * 支付渠道配置信息
     *
     * @var ConfigSet
     */
    protected $config;

    /**
     * @var FakeOpenSSL
     */
    protected $openssl;

    /**
     * 创建交易接口实例
     */
    final public function __construct(ConfigSet $config)
    {
        $this->config = $config;

        //判断是否有配置启用沙盒模式
        if ($this->config->get('sandbox', false)) {
            $this->sandbox();
        }

        $this->openssl = new FakeOpenSSL($config->get('merchant_private_key'), $config->get('alipay_public_key'));
    }

    /**
     * 启用沙盒模式
     *
     * @return void
     */
    final public function sandbox()
    {
        $this->gateway = 'https://openapi.alipaydev.com/gateway.do';
    }

    /**
     * 获取网关接口地址
     *
     * @return string
     */
    final public function gateway(): string
    {
        return $this->gateway;
    }

    /**
     * 发起支付请求
     *
     * @param  Transaction $trans
     * @return [type]
     */
    public function deal(Transaction $trans)
    {
        $payload = [
            'app_id'      => $this->config->get('app_id'),
            'method'      => $this->getMethod(),
            'format'      => 'JSON',
            'charset'     => 'utf-8',
            'sign_type'   => 'RSA2',
            // 'sign'        => '',
            'timestamp'   => date('Y-m-d H:i:s'),
            'version'     => '1.0',
            'notify_url'  => $this->config->get('notify_url'),
            // 'app_auth_token' => '',
            'biz_content' => (new UniformRequestBody($trans))->toString(),
        ];

        $payload['sign'] = $this->openssl->sign($payload);

        $request  = new InterfaceRequest($this->gateway, $payload);
        $response = $request->execute();
    }

    /**
     * 返回接口调用名
     *
     * @return string
     */
    abstract public function getMethod(): string;
}
