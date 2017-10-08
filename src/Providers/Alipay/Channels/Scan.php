<?php

namespace Doubear\Paysoul\Providers\Alipay\Channels;

use Closure;
use Doubear\Paysoul\Channels\Alipay\FakeOpenSSL;
use Doubear\Paysoul\Contracts\Channel;
use Doubear\Paysoul\Exceptions\HttpException;
use Doubear\Paysoul\Notify;
use Doubear\Paysoul\Utils\HttpClient;
use Doubear\Paysoul\Utils\SensitiveArray;

class Scan implements Channel
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
     * @var SensitiveArray
     */
    protected $config;

    /**
     * @var FakeOpenSSL
     */
    protected $openssl;

    /**
     * @var HttpClient
     */
    protected $http;

    /**
     * 创建交易接口实例
     */
    final public function __construct(SensitiveArray $config)
    {
        $this->config = $config;

        //判断是否有配置启用沙盒模式
        if ($this->config->get('sandbox', false)) {
            $this->sandbox();
        }

        $this->openssl = new FakeOpenSSL($config->get('merchant_private_key'), $config->get('alipay_public_key'));

        $this->http = new HttpClient([
            CURLOPT_TIMEOUT => 5,
        ]);
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
        $url      = $this->gateway() . '?' . http_build_query($payload);
        $response = $this->http->get($url);

        return $response;
    }

    protected function handleHttpResponse(string $responseText, string $key)
    {
        $data = json_decode($responseText, true);

        if (null === $data) {
            throw new HttpException('cannot parses response to JSON: ' . $responseText);
        }

        if ($this->openssl->verify($data, isset($data['sign']) ? $data['sign'] : null, ['sign'])) {
            throw new HttpException('signature verification failed');
        }

        $data = new SensitiveArray($data[$key], false);

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
            'total_amount' => bcdiv($amount, '100', 2),
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
            'refund_amount'  => bcdiv($amount, '100', 2),
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

    public function notify($data, Closure $success, Closure $failure)
    {
        if (false === is_array($data)) {
            $data = json_decode($data);
        }

        try {
            if ($this->openssl->verify($data, isset($data['sign']) ? $data['sign'] : null, ['sign'])) {
                throw new HttpException('signature verification failed');
            }

            $response = new Notify(
                $data['out_trade_no']
                , $data['trade_no']
                , intval(bcmul($data['total_amount'], '100', 0))
                , $data
            );

            // if ($data->code !== '10000') {
            //     throw new HttpException($data->sub_msg ?: $data->msg, $data->code);
            // }

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
