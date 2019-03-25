<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2019/3/25
 * Time: 16:40
 */

class BubbleSort
{
    public static function main()
    {
        $sourceArray = range(23, 33);
        shuffle($sourceArray);
        $sortArray = self::sort($sourceArray);
        foreach ($sortArray as $item) {
            echo $item . ' ' . PHP_EOL;
        }
    }
    public static function sort(array $array)
    {
        if (empty($array)) {
            return $array;
        }
        $arrayLength = count($array);
        for ($i = 0; $i < $arrayLength; $i++) {
            $exchange = false;
            for ($l = $i + 1; $l < $arrayLength; $l++) {
                $tmp = 0;
                if ($array[$i] > $array[$l]) {
                    $tmp = $array[$i];
                    $array[$i] = $array[$l];
                    $array[$l] = $tmp;
                    $exchange = true;
                }
            }
            if (!$exchange) {
                break;
            }
        }
        return $array;
    }
}

BubbleSort::main();