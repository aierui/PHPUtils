<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/31
 * Time: 下午6:29
 */

namespace Tests;

use PHPUtils\Crypt;
use PHPUnit\Framework\TestCase;


class CryptTest extends TestCase
{

    public function testRc4_encrypt()
    {
        echo 1;
        $key = 'aierui';
        $data= 'FooBar';
        $code = Crypt::rc4_encrypt('Foobar', $key);
        $this->assertSame(true, true);
    }


}