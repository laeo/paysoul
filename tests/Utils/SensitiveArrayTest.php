<?php

namespace Tests\Utils;

use Laeo\Paysoul\Utils\SensitiveArray;
use Tests\TestCase;

class SensitiveArrayTest extends TestCase
{
    protected $src;

    public function setUp()
    {
        $this->src = new SensitiveArray(['id' => 1, 'uid' => null]);
    }

    public function testTypeAssertion()
    {
        $this->assertInstanceOf(SensitiveArray::class, $this->src);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetter()
    {
        $this->assertEquals(1, $this->src->get('id'));
        $this->assertEquals(1, $this->src->id);
        $this->assertEquals(1, $this->src['id']);
        $this->src->getter;
    }

    public function testExistenceChecker()
    {
        $this->assertTrue($this->src->has('id'));
        $this->assertTrue($this->src->has('uid'));
        $this->assertFalse($this->src->has('uuid'));
    }

    public function testSetter()
    {
        $this->assertFalse($this->src->has('setter'));

        $this->src->set('setter', true);
        $this->assertTrue($this->src->has('setter'));

        $this->assertTrue($this->src->setter);
    }
}
