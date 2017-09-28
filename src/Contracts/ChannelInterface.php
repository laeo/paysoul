<?php

namespace Doubear\Paysoul\Contracts;

use Doubear\Paysoul\Trade;

/**
 * 交易接口抽象接口类
 */
interface ChannelInterface
{
    public function deal(Trade $trade);
    // public function refund();
    // public function cancel();
    // public function close();
}
