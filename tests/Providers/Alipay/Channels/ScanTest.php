<?php

namespace Tests\Providers\Alipay\Channels;

use Laeo\Paysoul\Providers\Alipay\Channels\Scan;
use Laeo\Paysoul\Utils\SensitiveArray;
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
