<?php

namespace Paysoul\Contracts;

use Paysoul\Utils\ConfigSet;

/**
 * Channel Contract
 *
 * 用于规范化支付渠道的接口
 */
interface Channel
{
    public function __construct(string $channel, ConfigSet $config);

    /**
     * 用于分发传入的交易到渠道方
     *
     * @return mixed
     */
    public function deal(Transaction $trans);
}
