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

    /**
     * 数组排序
     * @param array $array
     * @param callable|null $callback
     * @return array
     */
    public static function sort(array $array, callable $callback = null): array
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
    public static function accessible($value): bool
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
    public static function exists(array $array, $key): bool
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
    public static function count(array $array): int
    {
        return count($array);
    }

    /**
     * @param array $array
     * @param bool $keepKeys array_unique 方式去重效率慢的多，不建议使用 在相同测试环境下 array_keys 速度快一倍
     * @return array
     */
    public static function unique(array $array, $keepKeys = false): array
    {
        if ($keepKeys) {
            $array = array_unique($array);
        } else {
            $array = array_keys(array_flip($array));
        }

        return $array;
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
     * 递归的数组合并实现
     * @param $array array
     * @param $array1 array
     * @return array
     */
    public static function array_replace_recursive(array $array, array $array1)
    {
        if (function_exists('array_replace_recursive')) {
            return array_replace_recursive($array, $array1);
        }
        return self::_array_replace_recursive($array, $array1);
    }

    /**
     * 递归的数组合并实现
     * @param $array array
     * @param $array1 array
     * @return array
     */
    private static function _array_replace_recursive(array $array, array $array1)
    {
        foreach ($array1 as $key => $value) {
            // create new key in $array, if it is empty or not an array
            if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) {
                $array[$key] = array();
            }

            // overwrite the value in the base array
            if (is_array($value)) {
                $value = self::_array_replace_recursive($array[$key], $value);
            }
            $array[$key] = $value;
        }
        return $array;
    }


    /**
     * 移除数组中空白的元素
     * @param array $array
     * @param bool $trim
     */
    public static function removeEmpty(array & $array, $trim = true)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                self::removeEmpty($array[$key]);
            } else {
                $trim && $value = trim($value);
                if ($value == '') {
                    unset($array[$key]);
                }
            }
        }
    }


    /**
     * @param $array
     * @return array
     * $links http://php.net/manual/zh/function.array-filter.php
     */
    public static function removeEmptyV2($array)
    {
        return array_filter($array);
    }


    /**
     * 过滤空字符，并重建数字索引
     * @param array $array
     * @param bool $trim
     * @return array
     */
    public static function filterEmpty(array $array, $trim = true): array
    {
        $result = [];

        foreach ($array as $value) {
            // 检测变量是否是一个标量
            // 标量变量是指那些包含了 integer、float、string 或 boolean的变量，而 array、object 和 resource 则不是标量。
            if (is_scalar($value)) {
                $trim && $value = trim($value);
                $value && $result[] = $value;
            } else {
                $value && $result[] = $value;
            }
        }

        return $result;
    }


    /**
     * 过滤只保留数组指定的字段
     * @param array $data 数据数组
     * @param array $allow_field 指定的字段
     * @return array
     */
    public static function filterField(array $data, array $allow_field): array
    {
        $result = [];
        foreach ($allow_field as $value) {
            isset($data[$value]) && $result[$value] = $data[$value];
        }
        return $result;
    }


    /**
     * 移除数组中某个健
     * @param array $array
     * @param $keys
     */
    public static function except(array &$array, $keys)
    {
        $keys = (array)$keys;

        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);
            }
        }
    }


    /**
     * 获取二维数组中指定键的所有值
     * @param $array
     * @param $col
     * @return array
     */
    public static function cols(array $array, $col): array
    {
        $result = [];
        foreach ($array as $value) {
            if (isset($value[$col])) {
                $result[] = $value[$col];
            }
        }
        return $result;
    }

    /**
     * 获取二维数组中指定键的所有值
     * 这个方法优于上面 cols
     * @param array $array
     * @param $column
     * @return array
     */
    public static function column(array $array, $column)
    {
        if (function_exists('array_column')) {
            return array_column($array, $column);
        } else {
            array_map(function ($value) use ($column, &$arr) {
                $arr[] = $value[$column];
            }, $array);
            return $arr;
        }
    }



    /**
     * 将二维数组按照指定字段的值分组
     * @param array $array
     * @param string $key_field
     * @return array
     */
    public static function groupBy(array $array, string $key_field): array
    {
        $result = [];
        foreach ($array as $value) {
            $key = $value[$key_field];
            $result[$key][] = $value;
        }
        return $result;
    }


    public static function sortByMultiCols($multi_array, $keyName, $sort = SORT_ASC)
    {

        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$keyName];
                }
            }
        } else {
            return false;
        }
        if (empty($key_array)) {
            return false;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }


}