<?php

namespace Paysoul\Contracts;

/**
 * 交易接口
 *
 * 用于规范化标准交易的接口
 */
interface Transaction
{
    public function setSerialId(string $serialId);
    public function setSubject(string $subject);
    public function setAmount(integer $amount);
    public function appendExtra(string $key, string $value);
    public function mergeTo(UniformRequestBody $body);
}
