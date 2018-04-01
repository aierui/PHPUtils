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
     * 合并多个数组
     * @param array[] ...$arrays
     * @return array
     */
    public static function merge(array ... $arrays): array
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
     * @param array $array
     * @param $key_field
     * @param null $value_field
     * @param bool $force_string_key
     * @return array
     */
    public static function hashMap(array $array, $key_field, $value_field = null, $force_string_key = false): array
    {
        if (!$array) {
            return [];
        }
        $result = [];
        if ($value_field) {
            foreach ($array as $value) {
                $key = $force_string_key ? (string)$value[$key_field] : $value[$key_field];
                $result[$key] = $value[$value_field];
            }
        } else {
            foreach ($array as $value) {
                $key = $force_string_key ? (string)$value[$key_field] : $value[$key_field];
                $result[$key] = $value;
            }
        }
        return $result;
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


    /**
     * 将平面的二维数组按照指定的字段转换为树状结构
     * @param array $array
     * @param $key_node_id 节点ID字段名
     * @param string $key_parent_id 节点父ID字段名
     * @param string $key_childrens 保存子节点的字段名
     * @param null $refs 是否在返回结果中包含节点引用
     * @return array
     */
    public static function tree(array $array, $key_node_id, $key_parent_id = 'parent_id', $key_childrens = 'childrens', & $refs = null): array
    {
        $refs = [];
        foreach ($array as $offset => $row) {
            $array[$offset][$key_childrens] = array();
            $refs[$row[$key_node_id]] = &$array[$offset];
        }

        $tree = [];
        foreach ($array as $offset => $row) {
            $parent_id = $row[$key_parent_id];
            if ($parent_id) {
                if (!isset($refs[$parent_id])) {
                    $tree[] = &$array[$offset];
                    continue;
                }
                $parent = &$refs[$parent_id];
                $parent[$key_childrens][] = &$array[$offset];
            } else {
                $tree[] = &$array[$offset];
            }
        }

        return $tree;
    }

    /**
     * 将树形数组展开为平面的数组
     *
     * 这个方法是 tree() 方法的逆向操作。
     *
     * @param array $tree 树形数组
     * @param string $key_childrens 包含子节点的键名
     *
     * @return array 展开后的数组
     */

    public static function treeToArray(array $tree, $key_childrens = 'childrens'): array
    {
        $result = [];
        if (isset($tree[$key_childrens]) && is_array($tree[$key_childrens])) {
            $childrens = $tree[$key_childrens];
            unset($tree[$key_childrens]);
            $result[] = $tree;
            foreach ($childrens as $node) {
                $result = array_merge($result, self::treeToArray($node, $key_childrens));
            }
        } else {
            unset($tree[$key_childrens]);
            $result[] = $tree;
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