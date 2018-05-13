<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/1
 * Time: 下午6:48
 */

namespace PHPUtils;

class Crypt
{

    public static function rc4_encrypt($data, $key) {
        $s = array();
        for ($i=0; $i<256; $i++) {
            $s[$i] = $i;
        }

        $j = 0;
        $key_len = strlen($key);
        for ($i=0; $i<256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $key_len])) % 256;
            //swap
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $ct = '';
        $data_len = strlen($data);
        for ($y=0; $y< $data_len; $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            //swap
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $ct .= $data[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $ct;
    }


    public static function rc4_decrypt($data, $key) {
        return self::rc4_encrypt($data, $key);
    }



}
