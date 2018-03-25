<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/25
 * Time: 上午10:57
 */

namespace PHPUtils;

require dirname(__FILE__) . "/vendor/autoload.php";


if (!function_exists('length')) {
    function length(string $value, $encoding = null)
    {
        return Str::length($value, $encoding);
    }
}




