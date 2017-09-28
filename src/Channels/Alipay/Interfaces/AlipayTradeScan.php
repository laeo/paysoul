<?php

namespace Paysoul\Channels\Alipay\Interfaces;

/**
 * 支付宝统一收单交易预创建接口
 */
class AlipayTradeScan extends AbstractAlipayTrade
{
    public function getMethod()
    {
        return 'alipay.trade.precreate';
    }
}
