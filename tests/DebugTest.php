<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/16
 * Time: 上午1:54
 */

namespace Tests;

use PHPUtils\Debug;
use PHPUnit\Framework\TestCase;


class DebugTest extends TestCase
{
    public function testDump()
    {

        var_dump(Debug::dump('PHP_SAPI == \'cli\''));
    }

}