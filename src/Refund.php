<?php

namespace Doubear\Paysoul;

use Doubear\Paysoul\Contracts\Jsonable;

class Refund implements Jsonable
{
    private $out_trade_no;

    private $amount;

    private $reason;

    private $request_no;

    public function __construct($tradeNo, $amount, $reason = null, $request_no = null)
    {
        $this->out_trade_no = $tradeNo;
        $this->amount       = $amount;
        $this->reason       = $reason;
        $this->request_no   = $request_no;
    }

    public function toJson()
    {
        return json_encode([
            'out_trade_no'   => $this->out_trade_no,
            'refund_amount'  => $this->amount / 100,
            'refund_reason'  => $this->reason,
            'out_request_no' => $this->out_request_no,
        ]);
    }
}
