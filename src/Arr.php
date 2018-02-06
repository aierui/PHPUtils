<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/2/1
 * Time: 下午6:36
 */


namespace PHPUtils;

use ArrayAccess;

class Arr
{
    /** 数组排序
     * @param $arr
     * @param callable|null $callback
     * @return array
     */
    public static function sort($array, callable $callback = null): array
    {

        $callback ? uasort($array, $callback) : uasort($array, function ($a, $b) {

            if ($a == $b) {
                return 0;
            }

            return ($a < $b) ? -1 : 1;
        });

        return $array;
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array $array
     * @param  string|int $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * 统计数组元素的个数
     * @param $array
     * @return int
     */
    public static function count($array)
    {
        return count($array);
    }

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param  array $array
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }


    /**
     * 合并多个数组
     * @param array $a1
     * @param array $a2
     * @return array
     */
    public static function merge(array $arr1, array $arr2)
    {
        $result = [];
        $total = func_num_args();
        for ($i = 0; $i < $total; $i++) {
            $arr = func_get_arg($i);
            $assoc = self::isAssoc($arr);
            foreach ($arr as $key => $val) {
                if (isset($result[$key])) {
                    if (is_array($val) && is_array($result[$key])) {
                        if (self::isAssoc($val)) {
                            $result[$key] = self::merge($result[$key], $val);
                        } else {
                            $diff = array_diff($val, $result[$key]);
                            $result[$key] = array_merge($result[$key], $diff);
                        }
                    } else {
                        if ($assoc) {
                            $result[$key] = $val;
                        } elseif (!in_array($val, $result, true)) {
                            $result[] = $val;
                        }
                    }
                } else {
                    $result[$key] = $val;
                }
            }
        }

        return $result;
    }


}