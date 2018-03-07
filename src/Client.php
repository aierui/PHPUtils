<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/6
 * Time: 下午11:52
 */

namespace PHPUtils;

class Client
{

    public static function getIp()
    {
        $uips = array();
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown')) {
            $uips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown')) {
            $uips[] = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['X-Real-IP']) && strcasecmp($_SERVER['X-Real-IP'], 'unknown')) {
            $uips[] = $_SERVER['X-Real-IP'];
        }
        if (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $uips[] = $_SERVER['REMOTE_ADDR'];
        }

        $uip = '';
        foreach ($uips as $ip) {
            $uip = $ip;
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                break;
            }
        }
        return $uip;

    }


    /**
     * 获取当前域名
     * @return string
     */
    public static function getDomainName()
    {
        $domain = '';
        if (isset($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            $domain = $_SERVER['SERVER_NAME'];
        }
        return $domain;
    }

    /**
     * 获取当前站点URL
     * @return string
     */
    public static function getBaseUrl()
    {
        $baseUrl = '';
        if (!empty($domain = self::getDomainName())) {
            $baseUrl = 'http://' . $domain;
        }
        return $baseUrl;

    }

    /**
     * 判断是否微信浏览器访问
     * @return bool
     */
    public static function isWeixinBrowser()
    {
        $request = new Yaf_Request_Http();
        $userAgent = $request->getServer('HTTP_USER_AGENT');
        $isWeixin = false;
        if (strpos(strtolower($userAgent), 'micromessenger') !== false) {
            $isWeixin = true;
        }
        return $isWeixin;
    }

    /**
     * 判断是否爬虫
     * @return bool
     */
    public static function isSpider()
    {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $spiders = ['Googlebot', 'Baiduspider', 'Yahoo! Slurp', 'YodaoBot', 'msnbot'];
        foreach ($spiders as $spider) {
            if (strpos($ua, strtolower($spider)) !== false) {
                return true;
            }
        }
        return false;
    }

}