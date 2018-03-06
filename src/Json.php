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
    public static function is_json(String $string)
    {
        json_decode($string);
        return json_last_error() == JSON_ERROR_NONE;
    }
}