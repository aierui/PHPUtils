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

    public function testReplaceToStar()
    {
        $this->assertSame('F***ar', Str::replaceToStar('FooBar'));
        //var_dump(Str::replaceToStar('中华说你民'));
        //$this->assertSame('中***民', Str::replaceToStar('中华说你民'));
    }

    public function testIndexOf()
    {
        $this->assertSame(3, Str::indexOf('FooBar', 'B'));
        $this->assertSame(0, Str::indexOf('FooBar', 'F'));
        $this->assertSame(-1, Str::indexOf('FooBar', 'b', false));
        $this->assertSame(-1, Str::indexOf('FooBar', 'f', false));
    }
}