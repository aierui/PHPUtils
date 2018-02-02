<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/2
 * Time: 上午9:33
 */

namespace Tests;

use PHPUtils\Str;
use PHPUnit\Framework\TestCase;


class StrTest extends TestCase {

    public function testLength()
    {
        $this->assertTrue(6 === Str::length('fooBar'));
        $this->assertTrue(6 === Str::length('fooBar'));
        $this->assertTrue(strlen('fooBar') === Str::length('fooBar'));
    }

    public function testCamel()
    {
        $this->assertSame('fooBar', Str::camel('FooBar'));
        $this->assertSame('fooBar', Str::camel('FooBar')); // cached
        $this->assertSame('fooBar', Str::camel('foo_bar'));
        $this->assertSame('fooBar', Str::camel('_foo_bar'));
        $this->assertSame('fooBar', Str::camel('_foo_bar_'));
    }

    public function testStudly()
    {
        $this->assertSame('FooBar', Str::studly('fooBar'));
        $this->assertSame('FooBar', Str::studly('_foo_bar'));
        $this->assertSame('FooBar', Str::studly('_foo_bar_'));
        $this->assertSame('FooBar', Str::studly('_foo_bar_'));
    }
}