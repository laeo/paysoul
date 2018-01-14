<?php

namespace Tests;

use Laeo\Paysoul\Notify;

class NotifyTest extends TestCase
{
    protected $notify;

    public function setUp()
    {
        $this->notify = new Notify('1', '2', 3);
    }

    public function testTypeAssertion()
    {
        $this->assertInstanceOf(Notify::class, $this->notify);
    }

    public function testIdGetter()
    {
        $this->assertEquals('1', $this->notify->id());
    }

    public function testSnGetter()
    {
        $this->assertEquals('2', $this->notify->sn());
    }

    public function testAmountGetter()
    {
        $this->assertEquals(3, $this->notify->amount());
    }

    public function testExtraGetter()
    {
        $this->assertNull($this->notify->getExtra('extra'));
        $this->assertNull($this->notify->extra);
    }
}
