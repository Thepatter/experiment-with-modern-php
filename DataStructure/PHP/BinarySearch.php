<?php
/**
 * Created by IntelliJ IDEA.
 * User: z
 * Date: 2019/3/17
 * Time: 21:43
 */

class BinarySearch
{
    public static function main()
    {
        $sortArray = range(11, 33);
        if (self::binarySearchInRecursive(24, $sortArray, 0, count($sortArray) - 1) == self::binarySearchInLoop(24, $sortArray)) {
            echo "相等";
        } else {
            echo "不等";
        }
    }

    public static function binarySearchInRecursive($key, $array, $start, $end)
    {
        if ($start > $end) {
            return -1;
        }
        $mid = (int) ($end - $start) / 2 + $start;
        if ($key > $array[$mid]) {
            return self::binarySearchInRecursive($key, $array, $mid + 1, $end);
        } elseif ($key < $array[$mid]) {
            return self::binarySearchInRecursive($key, $array, $start, $mid -1);
        } else {
            return $mid;
        }
    }

    public static function binarySearchInLoop($key, $array)
    {
        $start = 0;
        $end = count($array) - 1;
        while ($start <= $end) {
            $mid = ($end - $start) / 2 + $start;
            if ($key > $array[$mid]) {
                $start = $mid + 1;
            } elseif ($key < $array[$mid]) {
                $end = $mid - 1;
            } else {
                return $mid;
            }
        }
        return -1;
    }
}

BinarySearch::main();