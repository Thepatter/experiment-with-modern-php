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
        var_dump(self::binSearch(24, [1, 2, 3, 5, 7, 11, 23, 24, 25, 35, 38], 0, 9));
    }

    public static function binSearch($key, $array, $start, $end)
    {
        if ($start > $end) {
            return -1;
        }
        $mid = (int) ($end - $start) / 2 + $start;
        if ($key > $array[$mid]) {
            return self::binSearch($key, $array, $mid + 1, $end);
        } elseif ($key < $array[$mid]) {
            return self::binSearch($key, $array, $start, $mid -1);
        } else {
            return $mid;
        }
    }
}

BinarySearch::main();