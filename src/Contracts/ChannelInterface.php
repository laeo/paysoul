<?php

namespace Doubear\Paysoul\Contracts;

use Doubear\Paysoul\Cancel;
use Doubear\Paysoul\Close;
use Doubear\Paysoul\Query;
use Doubear\Paysoul\Refund;
use Doubear\Paysoul\Trade;

/**
 * 交易接口抽象接口类
 */
interface ChannelInterface
{
    public function deal(Trade $trade);
    public function refund(Refund $refund);
    public function cancel(Cancel $cancel);
    public function close(Close $close);
    public function query(Query $query);
    public function verify(array $args);
}
