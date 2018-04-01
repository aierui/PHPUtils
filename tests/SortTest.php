<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/4/1
 * Time: 下午8:17
 */

namespace Tests;

use PHPUtils\Sort;
use PHPUnit\Framework\TestCase;


class SortTest extends TestCase
{
    public function testBubble()
    {
        $array = [1, 3, 57, 1314, 131, 121, 1, 4, 25, 60, 7, 811, 12];
        $this->assertSame([1, 1, 3, 4, 7, 12, 25, 57, 60, 121, 131, 811, 1314], Sort::bubble($array));
    }


    public function testSelection()
    {
        $array = [2, 3, 4, 5, 7, 1, 86, 1323, 23, 90, 43, 23];
        $this->assertSame([1, 2, 3, 4, 5, 7, 23, 23, 43, 86, 90, 1323], Sort::selection($array));
    }


    public function testInsertion()
    {
        $array = [2, 35, 4, 5, 7, 1, 19, 78, 23, 56, 43, 238];
        $this->assertSame([1, 2, 4, 5, 7, 19, 23, 35, 43, 56, 78, 238], Sort::insertion($array));
    }


    public function testMerge()
    {
        $array = [12, 3, 4, 5, 7, 1, 86, 783, 23, 90, 43, 53];
        $this->assertSame([1, 3, 4, 5, 7, 12, 23, 43, 53, 86, 90, 783], Sort::merge($array));
    }


    public function testQuick()
    {
        $array = [2, 3, 41, 5, 6, 75, 81, 10, 1, 4, 66, 7, 8, 9];
        $this->assertSame([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 41, 66, 75, 81], Sort::quick($array));
    }

    public function testShell()
    {
        $array = [2, 3, 4, 5, 7, 1, 86, 54, 98, 38, 765, 246, 23];
        $this->assertSame([1, 2, 3, 4, 5, 7, 23, 38, 54, 86, 98, 246, 765], Sort::shell($array));
    }

    public function testHeap()
    {
        $array = [2, 3, 10, 1, 4, 66, 7, 8, 86, 88, 298, 90, 43, 23];
        $this->assertSame([1, 2, 3, 4, 7, 8, 10, 23, 43, 66, 86, 88, 90, 298], Sort::heap($array));
    }

}