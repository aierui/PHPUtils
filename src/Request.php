<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/18
 * Time: 下午8:02
 */

namespace PHPUtils;

class Request
{
    public static function domain()
    {
        $domain = '';
        if (isset($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            $domain = $_SERVER['SERVER_NAME'];
        }
        return $domain;
    }


    public static function baseUrl()
    {
        $baseUrl = '';
        if (!empty($domain = self::domain())) {
            $baseUrl = 'http://' . $domain;
        }
        return $baseUrl;
    }

    public static function isSsl()
    {
        return ($_SERVER['REQUEST_SCHEME'] == 'https');
    }

    /**
     * 获取$_SERVER数组中的信息
     * @param $name
     * @param string $default
     * @return string
     */
    public static function server($name, $default='') {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }

    /**
     * 获取$_GET数组中的信息
     * @param $name
     * @param string $default
     * @return string
     */
    public static function get($name, $default='') {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    /**
     * 获取$_POST数组中的信息
     * @param $name
     * @param string $default
     * @return string
     */
    public static function post($name, $default='') {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    /**
     * 获取$_REQUEST数组中的信息
     * @param $name
     * @param string $default
     * @return string
     */
    public static function request($name, $default='') {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    /**
     * 获取指定cookie的值
     * @param $name
     * @param string $default
     * @return string
     */
    public static function cookie($name, $default='') {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }

    /**
     * 获取指定的请求头
     * @param $name
     * @param string $default
     * @return string
     */
    public static function header($name, $default='') {
        $headers = self::getallheaders();
        return isset($headers[$name]) ? $headers[$name] : $default;
    }

    /**
     * 获取所有的请求头
     * @return string
     */
    public static function getallheaders() {
        if(function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * 获取http请求方法。
     * @return string GET/POST/PUT/DELETE/HEAD等
     */
    public static function get_http_method() {
        return self::server('REQUEST_METHOD');
    }

    /**
     * 判断当前请求是否是XMLHttpRequest(AJAX)发起
     * @return boolean
     */
    public static function is_xmlhttprequest() {
        return (self::server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest') ? true : false;
    }

    /**
     * 判断是否ajax请求
     * @return bool
     */
    public static function is_ajax() {
        return self::is_xmlhttprequest();
    }

    /**
     * 检查当前请求是否是https
     * @return bool
     */
    public static function is_https() {
        if (self::server('HTTPS') === 'on'
            || self::server('HTTP_X_FORWARDED_PROTO') === 'https'//为解决教育网https登录的问题，由教育网vip添加一个http头信息
            || self::server('HTTP_X_PROTO') === 'SSL'//当https证书放在负载均衡上时，后端server通过HTTP头来判断前端的访问方式
        ) {
            return true;
        }
        return false;
    }

    /**
     * 检查是否运行在命令行模式
     * @return bool
     */
    public static function is_cli() {
        return PHP_SAPI == 'cli';
    }
    /**
     * 保障时间的统一
     * @return mixed
     */
    public static function get_request_timestamp() {
        return self::server('REQUEST_TIME');
    }

    /**
     * 获取Y-m-d H:i:s 格式的请求时间
     * @return bool|string
     */
    public static function get_request_datatime() {
        static $datatime;
        if ($datatime === null) {
            $datatime = date('Y-m-d H:i:s', self::server('REQUEST_TIME'));
        }
        return $datatime;
    }
}