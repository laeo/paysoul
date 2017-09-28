<?php

namespace Doubear\Paysoul;

use Doubear\Paysoul\Contracts\Transaction as TransactionContract;
use Doubear\Paysoul\Contracts\UniformRequestBody;

/**
 * 交易参数抽象对象，便于快速创建标准交易
 */
class Transaction implements TransactionContract
{
    /**
     * 订单序列号
     *
     * @var null
     */
    private $serialId;

    /**
     * 交易类目
     *
     * @var string
     */
    private $subject;

    /**
     * 交易金额，单位分
     *
     * @var integer
     */
    private $amount = 0;

    private $extraData = [];

    /**
     * 构建新交易实例
     *
     * @param string $serialId 商户本地订单序列号
     * @param string $subject 交易类目
     * @param integer $amount 交易金额，单位分
     */
    public function __construct(string $serialId, string $subject, integer $amount)
    {
        $this->serialId = $serialId;
        $this->subject  = $subject;
        $this->amount   = $amount;
    }

    /**
     * 设置商户本地订单序列号
     *
     * @param string $serialId
     */
    public function setSerialId(string $serialId)
    {
        $this->serialId = $serialId;
    }

    /**
     * 设置交易类目
     *
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * 设置交易金额
     *
     * @param integer $amount 单位分
     */
    public function setAmount(integer $amount)
    {
        $this->amount = $amount;
    }

    public function appendExtra(string $key, string $value)
    {
        $this->extraData[$key] = $value;
    }

    /**
     * 将自身交易信息合并到给定请求体接口中
     *
     * @param  UniformRequestBody $body
     *
     * @return void
     */
    public function mergeTo(UniformRequestBody $body)
    {
        $body->append('out_trade_no', $this->serialId);
        $body->append('subject', $this->subject);
        $body->append('total_amount', $this->amount);

        if ($this->extraData) {
            $body->map($this->extraData);
        }
    }
}
