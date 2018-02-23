<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/15
 * Time: 下午11:11
 */

namespace PHPUtils;


class Server{

    public static function getIp()
    {
        $ip = $_SERVER['SERVER_ADDR'];
        if(empty($ip)) {
            $ip = empty($_SERVER['SERVER_ADDR']) ? 0 : $_SERVER['SERVER_ADDR'];
        }
        return $ip;
    }

}