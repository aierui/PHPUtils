<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/24
 * Time: 下午7:55
 */

namespace Tests;

use PHPUtils\Arr;
use PHPUnit\Framework\TestCase;


class ArrayTest extends TestCase
{
    public function testUnique()
    {
        $this->assertSame([1,2,3,4], Arr::unique([1,2,3,4,1,2]));
        $this->assertSame([1,2,3,4], Arr::unique([1,2,3,4,1,2], true));
    }
}