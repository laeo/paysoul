<?php

namespace Doubear\Paysoul;

use Doubear\Paysoul\Contracts\Jsonable;

/**
 * 交易参数抽象对象，便于快速创建标准交易
 */
class Trade implements Jsonable
{
    private $data = [];

    /**
     * 构建新交易实例
     *
     * @param string $serialId 商户本地订单序列号
     * @param string $subject 交易类目
     * @param integer $amount 交易金额，单位分
     */
    public function __construct(string $serialId, string $subject, int $amount)
    {
        $this->data['out_trade_no'] = $serialId;
        $this->data['subject']  = $subject;
        $this->data['total_amount']   = $amount;
    }

    /**
     * 设置商户本地订单序列号
     *
     * @param string $serialId
     */
    public function setSerialId(string $serialId)
    {
        $this->data['out_trade_no'] = $serialId;
    }

    /**
     * 设置交易类目
     *
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->data['subject'] = $subject;
    }

    /**
     * 设置交易金额
     *
     * @param integer $amount 单位分
     */
    public function setAmount(int $amount)
    {
        $this->data['total_amount'] = $amount;
    }

    /**
     * 添加自定义参数
     *
     * @param  string $key
     * @param  string $value
     *
     * @return void
     */
    public function appendExtra(string $key, string $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->data);
    }
}
