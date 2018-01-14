<?php

namespace Laeo\Paysoul\Providers\Alipay;

class FakeOpenSSL
{
    private $privateCertContent;

    private $publicCertContent;

    public function __construct(string $privateKey, string $publicKey)
    {
        if (is_file($privateKey)) {
            $this->privateCertContent = openssl_get_privatekey($privateKey);
        } else {
            $this->privateCertContent = $this->wrapPrivateKey($privateKey);
        }

        if (is_file($publicKey)) {
            $this->publicCertContent = openssl_get_publickey($publicKey);
        } else {
            $this->publicCertContent = $this->wrapPublicKey($publicKey);
        }
    }

    public function sign(array $params, array $ignore = [])
    {
        openssl_sign($this->buildString($params, $ignore), $sign, $this->privateCertContent, OPENSSL_ALGO_SHA256);

        return base64_encode($sign);
    }

    public function verify(array $data, string $sign, array $ignore = [])
    {
        return 1 === openssl_verify($this->buildString($data, $ignore), base64_decode($sign), $this->publicCertContent, OPENSSL_ALGO_SHA256);
    }

    protected function wrapPrivateKey(string $key)
    {
        return "-----BEGIN RSA PRIVATE KEY-----\n"
        . wordwrap($key, 64, "\n", true)
            . "\n-----END RSA PRIVATE KEY-----";
    }

    protected function wrapPublicKey(string $key)
    {
        return "-----BEGIN PUBLIC KEY-----\n" .
        wordwrap($key, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
    }

    protected function buildString(array $params, $ignore = []): string
    {
        foreach ($ignore as $key) {
            unset($params[$key]);
        }

        ksort($params);

        return urldecode(http_build_query($params));
    }
}
