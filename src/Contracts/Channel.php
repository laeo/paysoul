<?php

namespace Doubear\Paysoul\Contracts;

use Closure;

/**
 * 交易接口抽象接口类
 */
interface Channel
{
    public function deal($id, $subject, int $amount, array $extra = []);
    public function refund($id, $reqId, int $amount, int $total);
    public function refundQuery($reqId);
    public function close($id);
    public function query($id);
    public function respond($ok);
    public function notify($payload, Closure $success, Closure $failure);
}
