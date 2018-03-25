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
    private static $uabase = [
        'Windows NT 6.2' => 'WP8', // Window 8 PC or Pad
        'Windows NT 6.3' => 'WP8', // win8.1
        'Windows Phone 8.0' => 'WP8',
        'iPad' => 'iPad', // ipad 系列
        'iPhone' => 'iPhone', // iPhone系列
        'iPod' => 'iPhone', // iPhone系列
        'Android' => 'Android', // Android系列
        'Series60/5' => 'S60V5', // S60V5
        'Series60/3' => 'S60V3', // S60V3
        'Windows Phone OS 7' => 'WP7', // Windows Phone 7
        'MSIE ' => 'WM', // Windows Mobile
        'Kindle' => 'Kindle', // Kindle
        'MeeGo' => 'MeeGo', // Nokia MeeGo, N9, N950
        'Windows NT' => 'Windows',
        'Macintosh' => 'Mac',
        'Linux' => 'Linux',
        'Nokia' => 'Symbian',  // 其他Symbian平台
    ];

    private static $uabrowser = [
        'Firefox' => 'Firefox',
        'Chrome' => 'Chrome',
        'MicroMessenger' => 'WeiXin',
        'UCBrowser' => 'UC',
        'MSIE' => 'IE',
        'Opera' => 'Opera',
    ];


    private static $deviceType = null;
    private static $browserType = null;

    public static function ip()
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


    public static function deviceType()
    {
        if (!empty(self::$deviceType)) return self::$deviceType;
        $uaToPlatform = self::$uabase;
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            foreach ($uaToPlatform as $ua => $platform) {
                if (strpos($_SERVER['HTTP_USER_AGENT'], $ua) !== false) {
                    self::$deviceType = $platform;
                    return self::$deviceType;
                }
            }
        }
        self::$deviceType = 'UNKNOWN';
        return self::$deviceType;
    }


    public static function browserType()
    {
        if (!empty(self::$browserType)) return self::$browserType;
        $uaToBrowser = self::$uabrowser;

        if (!empty($_SERVER['HTTP_USER_AGENT'])) {

            foreach ($uaToBrowser as $ua => $browserType) {
                if (preg_match('#' . $ua . '#', $_SERVER['HTTP_USER_AGENT'])) {
                    self::$browserType = $browserType;

                    return self::$browserType;
                }
            }

        }

        self::$browserType = 'UNKNOWN';
        return self::$browserType;
    }


    public static function isiPhone()
    {
        $deviceType = self::deviceType();

        return ($deviceType == 'iPhone');
    }

    public static function isiPad()
    {
        $deviceType = self::deviceType();

        return ($deviceType == 'iPad');
    }


    public static function isAndroid()
    {
        $deviceType = self::deviceType();

        return ($deviceType == 'Android');
    }

    public static function isKindle()
    {
        $deviceType = self::deviceType();
        return ($deviceType == 'Kindle');
    }


    public static function isWeixin()
    {
        $browserType = self::browserType();
        return ($browserType == 'WeiXin');
    }

    public static function isChrome()
    {
        $browserType = self::browserType();
        return ($browserType == 'Chrome');
    }

    public static function isOpera()
    {
        $browserType = self::browserType();
        return ($browserType == 'Opera');
    }

    public static function isUC()
    {
        $browserType = self::browserType();
        return ($browserType == 'UC');
    }

    public static function isFirefox()
    {
        $browserType = self::browserType();
        return ($browserType == 'Firefox');
    }

    public static function isIE()
    {
        $browserType = self::browserType();
        return ($browserType == 'IE');
    }

    public static function isWP7()
    {
        $deviceType = self::deviceType();
        return ($deviceType == 'WP7');
    }

    public static function isWP8()
    {
        $deviceType = self::deviceType();
        return ($deviceType == 'WP8');
    }

    public static function isH5UA()
    {
        return (self::isAndroid() || self::isiPhone() || self::isiPad() || self::isWP8());
    }

    /**
     * 判断是否爬虫
     */
    public static function isSpider()
    {
        return preg_match('#Googlebot|Baiduspider|Yahoo! Slurp|YodaoBot|msnbot#i', $_SERVER['HTTP_USER_AGENT']);
    }

    public static function isWebBrowser()
    {
        return preg_match('#Windows 98|Windows NT|Macintosh|Linux.*x11|x11.*Linux#i', $_SERVER ['HTTP_USER_AGENT']);
    }

}