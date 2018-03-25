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


class StrTest extends TestCase
{

    public function testLength()
    {
        $this->assertTrue(6 === Str::length('fooBar'));
        $this->assertTrue(6 === Str::length('fooBar'));
        $this->assertTrue(6 === Str::length('123456'));
        $this->assertTrue(strlen('fooBar') === Str::length('fooBar'));
    }

    public function testLimit()
    {
        $this->assertSame('FooBa...', Str::limit('FooBar', 5));
        $this->assertSame('PHP，即“PHP: Hypertext Preproc...', Str::limit('PHP，即“PHP: Hypertext Preprocessor”，是一种被广泛应用的开源通用脚本语言，尤其适用于 Web 开发并可嵌入 HTML 中去。它的语法利用了 C、Java 和 Perl，易于学习。该语言的主要目标是允许 web 开发人员快速编写动态生成的 web 页面，但 PHP 的用途远不只于此。', 30));
    }


    public function testContains()
    {
        $this->assertTrue(true === Str::contains('FooBar', 'B'));
        $this->assertTrue(false === Str::contains('FooBar', 'b'));
    }

    public function testIndexOf()
    {
        $this->assertSame(3, Str::indexOf('FooBar', 'B'));
        $this->assertSame(0, Str::indexOf('FooBar', 'F'));
        $this->assertSame(-1, Str::indexOf('FooBar', 'b', false));
        $this->assertSame(-1, Str::indexOf('FooBar', 'f', false));
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

    public function testMask()
    {
        $this->assertSame('F****r', Str::mask('FooBar'));
        $this->assertSame('PH******ar', Str::mask('PHP-FooBar'));
        //Phone Num Mask
        $this->assertSame('133****0101', Str::mask('13312340101', 3, 4));

        //ID Card Mask
        $this->assertSame('1409271997****4367', Str::mask('140927199706154367', 10, 4));

        // 中文
        $this->assertSame('甲乙******壬癸', Str::mask('甲乙丙丁戊己庚辛壬癸'));
        $this->assertSame('甲A乙B丙**********2壬3癸4', Str::mask('甲A乙B丙C丁D戊E己F庚1辛2壬3癸4'));
    }


}