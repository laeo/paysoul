<?php

namespace Doubear\Paysoul\Channels\Alipay\Interfaces;

use ArrayObject;
use Closure;
use Doubear\Paysoul\Channels\Alipay\FakeOpenSSL;
use Doubear\Paysoul\Close;
use Doubear\Paysoul\Contracts\ChannelInterface;
use Doubear\Paysoul\Exceptions\HttpException;
use Doubear\Paysoul\Query;
use Doubear\Paysoul\Refund;
use Doubear\Paysoul\Trade;
use Doubear\Paysoul\Utils\ConfigSet;
use Doubear\Paysoul\with;
use GuzzleHttp\Client;

class ScanInterface implements ChannelInterface
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

    protected function getRequestBody(string $method)
    {
        return [
            'app_id'         => $this->config->get('app_id'),
            'method'         => $method,
            'format'         => 'JSON',
            'charset'        => 'utf-8',
            'sign_type'      => 'RSA2',
            'sign'           => '',
            'timestamp'      => date('Y-m-d H:i:s'),
            'version'        => '1.0',
            'notify_url'     => $this->config->get('notify_url'),
            'app_auth_token' => '',
            'biz_content'    => '',
        ];
    }

    protected function sendHttpRequest(array $payload)
    {
        $url      = $this->gateway . '?' . http_build_query($payload);
        $response = with(new Client())->get($url);

        //智障阿里开发，招的都特么什么鬼
        // $response = with(new Client())->post($this->gateway(), [
        //     'form_params' => $payload,
        // ]);

        if ($response->getStatusCode() !== 200) {
            throw new HttpException($response->getReasonPhrase(), $response->getStatusCode());
        }

        return $response->getBody()->getContents();
    }

    protected function handleHttpResponse(string $responseText, string $key)
    {
        $data = json_decode($responseText, true);

        if (null === $data) {
            throw new HttpException('cannot parses response to JSON: ' . $responseText);
        }

        $dataObj = new ArrayObject($data, 3);

        if ($this->openssl->verify($data, $dataObj->sign, ['sign'])) {
            throw new HttpException('signature verification failed');
        }

        $data = new ArrayObject($data[$key], 3);

        if ($data->code !== '10000') {
            throw new HttpException($data->sub_msg ?: $data->msg, $data->code);
        }

        return $data;
    }

    /**
     * 发起支付请求
     *
     * @param  Trade $trade
     * @return [type]
     */
    public function deal($id, $subject, int $amount, array $extra = [])
    {
        $payload = $this->getRequestBody('alipay.trade.precreate');

        $payload['biz_content'] = json_encode(array_merge([
            'out_trade_no' => $id,
            'subject'      => $subject,
            'total_amount' => $amount,
        ], $extra));

        $payload['sign'] = $this->openssl->sign(array_filter($payload));

        $responseText = $this->sendHttpRequest($payload);

        $response = $this->handleHttpResponse($responseText, 'alipay_trade_precreate_response');

        return $response->qr_code;
    }

    public function refund($id, $reqId, int $amount, int $total)
    {
        $payload = $this->getRequestBody('alipay.trade.refund');

        $payload['biz_content'] = json_encode([
            'out_trade_no'   => $id,
            'refund_amount'  => number_format($amount / 100, 2),
            'out_request_no' => $reqId,
        ]);

        $payload['sign'] = $this->openssl->sign(array_filter($payload));

        $responseText = $this->sendHttpRequest($payload);

        return $this->handleHttpResponse($responseText, 'alipay_trade_refund_response');
    }

    public function refundQuery($reqId)
    {
        $payload                = $this->getRequestBody('alipay.trade.fastpay.refund.query');
        $payload['biz_content'] = json_encode(['out_request_no' => $reqId]);
        $payload['sign']        = $this->openssl->sign(array_filter($payload));

        $responseText = $this->sendHttpRequest($payload);

        return $this->handleHttpResponse($responseText, 'alipay_trade_fastpay_refund_query_response');
    }

    public function query($id)
    {
        $payload                = $this->getRequestBody('alipay.trade.query');
        $payload['biz_content'] = json_encode(['out_trade_no' => $id]);
        $payload['sign']        = $this->openssl->sign(array_filter($payload));

        $responseText = $this->sendHttpRequest($payload);

        return $this->handleHttpResponse($responseText, 'alipay_trade_query_response');
    }

    public function close($id)
    {
        $payload                = $this->getRequestBody('alipay.trade.close');
        $payload['biz_content'] = json_encode(['out_trade_no' => $id]);
        $payload['sign']        = $this->openssl->sign(array_filter($payload));

        $responseText = $this->sendHttpRequest($payload);

        $response = $this->handleHttpResponse($responseText, 'alipay_trade_close_response');

        return true;
    }

    public function verify($args)
    {
        if (false === is_array($args)) {
            return false;
        }

        if (false === isset($args['sign'])) {
            return false;
        }

        return $this->openssl->verify($args, $args['sign'], ['sign']);
    }

    public function notify($payload, Closure $success, Closure $failure)
    {
        try {
            $response = $this->handleHttpResponse($payload);
            return $success($this, $response);
        } catch (HttpException $e) {
            return $failure($this, $e);
        }
    }

    public function respond($ok = false)
    {
        return $ok ? 'success' : 'failure';
    }
}
