<?php

/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/6
 * Time: 下午11:14
 */

namespace PHPUtils;

class Json
{
    public static function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() == JSON_ERROR_NONE;
    }

    public static function encode(mixed $value): string
    {
        return json_encode($value);
    }


    public static function decode(string $json, $assoc = true)
    {
        return json_decode($json, $assoc);
    }
}