<?php

namespace Doubear\Paysoul;

use Doubear\Paysoul\Contracts\Jsonable;

class Close implements Jsonable
{
    private $tradeNo;

    public function __construct($tradeNo)
    {
        $this->tradeNo = $tradeNo;
    }

    public function toJson()
    {
        return json_encode(['out_trade_no' => $this->tradeNo]);
    }
}
