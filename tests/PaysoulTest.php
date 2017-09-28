<?php

use Paysoul\Paysoul;
use Paysoul\Transaction;

class PaysoulTest
{
    public function testPaysoul()
    {
        $config = [
            'alipay' => [
                'app_id'               => '',
                'alipay_public_key'    => '',
                'merchant_private_key' => '',
                'notify_url'           => '',
            ],
        ];

        $trans = new Transaction('123456789', '一年VIP服务', 10000);

        with(new Paysoul($config))->channel('alipay.scan')->deal($trans);
    }
}

function with($value)
{
    return $value;
}
