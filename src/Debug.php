<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/16
 * Time: 上午1:31
 */

namespace PHPUtils;

class Debug
{
    // 区间时间信息
    protected static $info = [];
    // 区间内存信息
    protected static $mem = [];

    /**
     * 记录时间（微秒）和内存使用情况
     * @param string $name 标记位置
     * @param mixed $value 标记值 留空则取当前 time 表示仅记录时间 否则同时记录时间和内存
     * @return mixed
     */
    public static function remark($name, $value = '')
    {
        // 记录时间和内存使用
        self::$info[$name] = is_float($value) ? $value : microtime(true);
        if ('time' != $value) {
            self::$mem['mem'][$name] = is_float($value) ? $value : memory_get_usage();
            self::$mem['peak'][$name] = memory_get_peak_usage();
        }
    }

    /**
     * 统计某个区间的时间（微秒）使用情况 返回值以秒为单位
     * @param string $start 开始标签
     * @param string $end 结束标签
     * @param integer|string $dec 小数位
     * @return integer
     */
    public static function getRangeTime($start, $end, $dec = 6)
    {
        if (!isset(self::$info[$end])) {
            self::$info[$end] = microtime(true);
        }
        return number_format((self::$info[$end] - self::$info[$start]), $dec);
    }


    /**
     * 获取当前访问的吞吐率情况
     * @return string
     */
    public static function getRangeRate($start, $end)
    {
        return number_format(1 / self::getRangeTime($start, $end), 2) . 'req/s';
    }

    /**
     * 记录区间的内存使用情况
     * @param string $start 开始标签
     * @param string $end 结束标签
     * @param integer|string $dec 小数位
     * @return string
     */
    public static function getRangeMem($start, $end, $dec = 2)
    {
        if (!isset(self::$mem['mem'][$end])) {
            self::$mem['mem'][$end] = memory_get_usage();
        }
        $size = self::$mem['mem'][$end] - self::$mem['mem'][$start];
        $a = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }


    /**
     * 统计区间的内存峰值情况
     * @param string $start 开始标签
     * @param string $end 结束标签
     * @param integer|string $dec 小数位
     * @return mixed
     */
    public static function getMemPeak($start, $end, $dec = 2)
    {
        if (!isset(self::$mem['peak'][$end])) {
            self::$mem['peak'][$end] = memory_get_peak_usage();
        }
        $size = self::$mem['peak'][$end] - self::$mem['peak'][$start];
        $a = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, $dec) . " " . $a[$pos];
    }

}
