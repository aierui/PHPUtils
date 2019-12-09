<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/29
 * Time: 下午11:55
 */

namespace PHPUtils;


/**
 * 经过测试，几种排序算法效率从高到低为：
 *
 * 如果数据足够复杂最可能是：
 * quick > merge > insertion > selection > bubble
 *
 * 当然如果数据足够简单或已经按照一定顺序，效果也就不一样
 */
class Sort
{


    public static function swap(&$array, $i, $j)
    {
        $temp = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $temp;
    }

    /**
     * 冒泡排序
     *
     * 时间复杂度 平均 最好 最坏 O(n2) O(n) O(n2)
     * 空间复杂度 O(1)
     *
     * @param array $array
     * @return array
     */
    public static function bubble(array $array): array
    {
        $len = count($array);

        if ($len <= 1) return $array;

        for ($i = 0; $i < $len - 1; $i++) {

            for ($j = 0; $j < $len - 1 - $i; $j++) {
                $array[$j] > $array[$j + 1] && self::swap($array, $j, $j + 1);
            }

        }

        return $array;
    }


    /**
     * 选择排序（Selection Sort）与冒泡排序类似不同之处在于，它不是每比较一次就调换位置，
     * 而是一轮比较完毕，找到最大值（或最小值）之后，将其放在正确的位置，其他数的位置不变。
     *
     * 选择排序
     *
     * 时间复杂度 平均 最好 最坏 O(n2) O(n2) O(n2)
     * 空间复杂度 O(1)
     *
     * @param array $array
     * @return array
     */
    public static function selection(array $array): array
    {
        $len = count($array);

        if ($len <= 1) return $array;

        for ($i = 0; $i < $len; $i++) {
            // 将当前位置设为最小值
            $min = $i;//

            for ($j = $i + 1; $j < $len; $j++) {
                $array[$j] < $array[$min] && $min = $j;
            }

            $min != $i && self::swap($array, $min, $i);
        }

        return $array;
    }

    /**
     * 插入排序（insertion sort）比前面两种排序方法都更有效率。
     * 它将数组分成“已排序”和“未排序”两部分，一开始的时候，“已排序”的部分只有一个元素，然后将它后面一个元素从“未排序”部分插入“已排序”部分，
     * 从而“已排序”部分增加一个元素，“未排序”部分减少一个元素。以此类推，完成全部排序。
     *
     * 插入排序
     *
     * 时间复杂度 平均 最好 最坏 O(n2) O(n2) O(n2)
     * 空间复杂度 O(1)
     *
     * @param array $array
     * @return array
     */
    public static function insertion(array $array): array
    {
        $len = count($array);

        if ($len <= 1) return $array;

        for ($i = 0; $i < $len; $i++) {

            $value = $array[$i];// 储存当前位置的值
            /**
             * 当已排序部分的当前元素大于value，
             * 就将当前元素向后移一位，再将前一位与value比较
             */
            for ($j = $i - 1; $j > -1 && $array[$j] > $value; $j--) {
                $array[$j + 1] = $array[$j];
            }

            $array[$j + 1] = $value;
        }

        return $array;
    }


    /**
     * 合并排序（Merge sort）则是一种被广泛使用的排序方法。
     * 将两个已经排序的数组合并，要比从头开始排序所有元素来得快。因此，
     * 可以将数组拆开，分成n个只有一个元素的数组，然后不断地两两合并，直到全部排序完成。
     *
     * 空间换时间
     *
     * 时间复杂度 平均 最好 最坏 O(nlog2n) O(nlog2n) O(nlog2n)
     * 空间复杂度 O(n)
     *
     * @param array $array
     * @return array
     */
    public static function merge(array $array): array
    {
        $len = count($array);

        if ($len <= 1) return $array;

        $mid = floor($len / 2);

        $left = array_slice($array, 0, $mid);
        $right = array_slice($array, $mid);

        return self::mergeSwap(self::merge($left), self::merge($right));
    }

    public static function mergeSwap($left, $right)
    {
        $result = [];
        $i = 0;
        $j = 0;

        while ($i < count($left) && $j < count($right)) {
            $left[$i] < $right[$j] ? array_push($result, $left[$i++]) : array_push($result, $right[$j++]);
        }

        return array_merge($result, array_splice($left, $i), array_splice($right, $j));
    }

    /**
     * 快排
     *
     * 时间复杂度 平均 最好 最坏 O(nlog2n) O(nlog2n) O(n2)
     * 空间复杂度 O(log2n)、O(n) 不稳定的排序算法
     *
     * @param array $array
     * @return array
     */
    public static function quick(array $array): array
    {
        //排序过程只需要三步：
        //　1.在数据集中，找一个基准点
        //  2.建立两个数组，分别存储左边和右边的数组
        //  3.利用递归进行下次比较

        $length = count($array);
        if ($length <= 1) return $array;

        $num = $array[0];
        //初始化两个数组
        $left = [];
        $right = [];
        for ($i = 1; $i < $length; $i++) {

            $num > $array[$i] ? $left[] = $array[$i] : $right[] = $array[$i];

        }
        //再分别对 左边 和 右边的数组进行相同的排序处理方式
        //递归调用这个函数,并记录结果
        $left = self::quick($left);
        $right = self::quick($right);

        return array_merge($left, [$num], $right);
    }

    /**
     * 希尔排序
     *
     * 时间复杂度 平均 最好 最坏 O(n1.3) ？O(n2)
     * 空间复杂度 O(1) 不稳定的排序算法
     *
     * @param array $array
     * @return array
     */
    public static function shell(array $array): array
    {
        $len = count($array);

        if ($len <= 1) return $array;

        for ($m = floor($len / 2); $m > 0; $m = floor($m / 2)) {

            for ($i = $m; $i < $len; ++$i) {

                for ($j = $i - $m; $j >= 0 && $array[$j + $m] < $array[$j]; $j -= $m) {
                    self::swap($array, $j, $j + $m);
                }

            }
        }

        return $array;
    }

    /**
     * 调整堆
     * @param array $arr 排序数组
     * @param int $i 待调节元素的下标
     * @param int $size 数组大小, 准确来说是数组最大索引值加1
     */
    public static function heapAjust(& $arr, $i, $size)
    {
        $key = $arr[$i];
        // 索引从0开始
        // 左孩子节点为 2i+1, 右孩子节点为 2i+2
        for ($j = 2 * $i + 1; $j < $size; $j = 2 * $j + 1) {
            if ($j + 1 < $size && $arr[$j] < $arr[$j + 1])
                $j++;
            if ($key > $arr[$j])
                break;
            $arr[$i] = $arr[$j]; //调换值
            $i = $j;
        }
        $arr[$i] = $key;
    }


    /**
     * 堆排序
     *
     * 时间复杂度 平均 最好 最坏 O(nlogn) O(nlogn) O(nlogn)
     * 空间复杂度 O(1) 不稳定的排序算法
     *
     * @param array $array
     * @return array
     */
    public static function heap(array $array): array
    {
        $len = count($array);

        if ($len <= 1) return $array;

        // 构建初始大根堆
        for ($i = intval($len / 2); $i >= 0; $i--) {
            self::heapAjust($array, $i, $len);
        }

        // 调换堆顶元素和最后一个元素
        for ($j = $len - 1; $j > 0; $j--) {
            $swap = $array[0];
            $array[0] = $array[$j];
            $array[$j] = $swap;
            self::heapAjust($array, 0, $j); //继续调整剩余元素为大根堆
        }

        return $array;
    }


    public static function eraseOverlapIntervals(array $array): int
    {
        $len = count($array);
        if ($len == 0) return 0;

        // 对区间进行排序，以终止点进行排序, 若终止点相同则，起始点小的靠前  否则终止点小的靠前
        uasort($array, function ($a, $b) {
            if ($a['end'] == $b['end']) {
                return $a['start'] > $b['start'];
            }
            return $a['end'] > $b['end'];
        });

        $res = 1;                               //初始化最终结果
        $pre = 0;                               //记录前一个区间的下标

        for($i=1;$i<$len;++$i){                 //遍历整个数组
            //如果当前的区间起始小于前一个区间的结尾 则为 不重叠区间
            if($array[$i]['start'] >= $array[$pre]['end']){
                $res++;
                $pre = $i;
            }
        }

        return $len - $res;
    }
}