<?php

namespace Laeo\Paysoul;

class Notify
{
    private $__id;
    private $__sn;
    private $__amount;
    private $__extra = [];

    public function __construct(string $id, string $sn, int $amount, array $extra = [])
    {
        $this->__id     = $id;
        $this->__sn     = $sn;
        $this->__amount = $amount;
        $this->__extra  = $extra;
    }

    /**
     * 读取商户本地订单ID
     *
     * @return string
     */
    public function id(): string
    {
        return $this->__id;
    }

    /**
     * 读取网关交易流水号
     *
     * @return string
     */
    public function sn(): string
    {
        return $this->__sn;
    }

    /**
     * 获取异步通知中的交易金额，单位分
     *
     * @return int
     */
    public function amount(): int
    {
        return $this->__amount;
    }

    /**
     * 读取原始通知数据
     *
     * @param  string $key 网关官方文档中定义的回调数据键名
     *
     * @return mixed|null
     */
    public function getExtra($key)
    {
        return isset($this->__extra[$key]) ? $this->__extra[$key] : null;
    }

    public function __get($key)
    {
        return $this->getExtra($key);
    }
}
