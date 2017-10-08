<?php

namespace Tests\Providers\Wxpay\Channels;

use Doubear\Paysoul\Providers\Wxpay\Channels\Scan;
use Doubear\Paysoul\Utils\SensitiveArray;
use Tests\TestCase;

class ScanTest extends TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testSyntax()
    {
        $scan = new Scan(new SensitiveArray([]));
        $this->assertInstanceOf(Scan::class, $scan);
    }
}
