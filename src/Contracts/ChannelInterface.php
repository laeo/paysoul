<?php

namespace Doubear\Paysoul\Contracts;

/**
 * 交易接口抽象接口类
 */
interface ChannelInterface
{
    public function deal(Transaction $trans);
    // public function refund();
    // public function cancel();
    // public function close();
}
