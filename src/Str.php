<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/1
 * Time: 下午6:14
 */

namespace PHPUtils;

class Str
{

    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of camel-cased words.
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];


    /**
     * Return the length of the given string.
     *
     * @param  string $value
     * @param  string $encoding
     * @return int
     */
    public static function length(string $value, $encoding = null): int
    {
        if ($encoding) {
            return mb_strlen($value, $encoding);
        }

        return mb_strlen($value);
    }

    /**
     * Limit the number of characters in a string.
     *
     * @param  string $value
     * @param  int $limit
     * @param  string $end
     * @return string
     */
    public static function limit(string $value, $limit = 100, $end = '...'): string
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }

    /**
     * Limit the number of words in a string.
     *
     * @param  string $value
     * @param  int $words
     * @param  string $end
     * @return string
     */
    public static function words(string $value, $words = 100, $end = '...'): string
    {
        preg_match('/^\s*+(?:\S++\s*+){1,' . $words . '}/u', $value, $matches);

        if (!isset($matches[0]) || static::length($value) === static::length($matches[0])) {
            return $value;
        }

        return rtrim($matches[0]) . $end;
    }


    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function contains(string $haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $haystack
     * @param $needle
     * @param bool $ignoreCase
     * @param int $offset
     * @return int
     */
    public static function indexOf($haystack, $needle, $ignoreCase = true, $offset = 0)
    {
        if ($ignoreCase) {
            $pos = stripos($haystack, $needle, $offset);
        } else {
            $pos = strpos($haystack, $needle, $offset);
        }

        if ($pos === false) {
            return -1;
        }
        return $pos;
    }


    /**
     * Convert the given string to lower-case.
     *
     * @param  string $value
     * @return string
     */
    public static function lower(string $value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * Convert the given string to upper-case.
     *
     * @param  string $value
     * @return string
     */
    public static function upper(string $value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     * @param int $length
     * @return string
     * @throws \Throwable
     */
    public static function random(int $length = 16): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }


    /**
     * Generate a "random" alpha-numeric string.
     *
     * Should not be considered sufficient for cryptography, etc.
     *
     * @deprecated since version 5.3. Use the "random" method directly.
     *
     * @param int $length
     * @return bool|string
     * @throws \Throwable
     */
    public static function quickRandom(int $length = 16): string
    {
        if (PHP_MAJOR_VERSION > 5) {
            return static::random($length);
        }

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }


    /**
     * Version 4 UUIDs are pseudo-random!
     *
     * @see http://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
     *
     * @return string
     * @throws \Throwable
     */
    public static function uuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            // 16 bits for "time_mid"
            random_int(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            random_int(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            random_int(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff)
        );
    }


    /**
     * Convert a value to camel case.
     *
     * @param  string $value
     * @return string
     */
    public static function camel(string $value): string
    {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }

        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }

    /**
     * Convert a value to studly caps case.
     *
     * @param  string $value
     * @return string
     */
    public static function studly(string $value): string
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }


    /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param  string $string
     * @param  int $start
     * @param  int|null $length
     * @return string
     */
    public static function substr(string $string, int $start, int $length = null): string
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * Make a string's first character uppercase.
     *
     * @param  string $string
     * @return string
     */
    public static function ucfirst(string $string): string
    {
        return static::upper(static::substr($string, 0, 1)) . static::substr($string, 1);
    }

    /**
     * 切割字符串为数组
     * @param string $string 要处理的字符串
     * @param string $delimiter 分隔符
     * @param bool $trim 是否过滤左右空格
     * @param bool $skipEmpty 是否过滤空值
     * @return array
     */
    public static function explode(string $string, $delimiter = ',', $trim = true, $skipEmpty = false): array
    {
        $result = explode($delimiter, $string);
        if ($trim) {
            if ($trim === true) {
                $trim = 'trim';
            } elseif (!is_callable($trim)) {
                $trim = function ($v) use ($trim) {
                    return trim($v, $trim);
                };
            }
            $result = array_map($trim, $result);
        }
        if ($skipEmpty) {
            $result = array_values(array_filter($result));
        }
        return $result;
    }


    /**
     * The intermediate position character of the replacement string is the asterisk
     * @param string $string
     * @param null $start
     * @param null $end
     * @param int $maxStar
     * @param string $dot
     * @param string $charset
     * @return string
     */
    public static function mask(string $string, $start = null, $end = null, $maxStar = 10, $dot = '*', $charset = 'UTF-8'): string
    {
        $L = mb_strlen($string, $charset);
        if (is_null($start) || is_null($end)) {
            $l = intval($L / 4);// * 前后的长度
            $start = $end = $l;
        }
        $start_string = mb_substr($string, 0, $start, $charset);
        $end_string = mb_substr($string, $L - $end, $end, $charset);

        $s = $L - $start - $end;

        $maxStar = $s > $maxStar ? $maxStar : $s;
        $star = str_repeat($dot, $maxStar);

        return $start_string . $star . $end_string;
    }


    /**
     * 转成16进制
     * @param string $string
     * @return string
     */
    public static function string2Hex($string)
    {
        $hex = '';
        $len = strlen($string);
        for ($i = 0; $i < $len; $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }


}
