<?php

namespace Doubear\Paysoul\Contracts;

/**
 * 交易接口抽象接口类
 */
interface ChannelInterface
{
    public function deal();
    public function refund();
    public function cancel();
    public function close();

    /**
     * 用于校验订单回调参数合法性
     *
     * @param array $params 订单回调的参数
     *
     * @return bool
     */
    public function verify(array $params): bool;

    /**
     * 对指定数组进行签名
     *
     * @param  array  $params
     * @return string
     */
    public function sign(array $params): string;
}
