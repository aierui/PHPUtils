<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/2
 * Time: 上午10:50
 */


if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath('.'));
}

if ($autoload = realpath(ROOT_PATH . '/vendor/autoload.php')) {
    require_once $autoload;
} else {
    exit('Please execute "composer update" !' . PHP_EOL);
}
