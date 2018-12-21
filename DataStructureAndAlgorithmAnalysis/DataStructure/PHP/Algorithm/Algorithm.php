<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/21
 * Time: 9:46
 */

namespace DataStructure\PHP\Algorithm;


class Algorithm
{
    /**
     * 回文字符串判断O(logN);
     * @param string $phraseString
     * @return bool
     */
    public static function isPhraseString(string $phraseString)
    {
        $phraseStringLength = strlen($phraseString);
        for ($i = 0; $i < $phraseStringLength; $i++) {
            if ($phraseString[$i] !== $phraseString[$phraseStringLength - 1 - $i]) {
                return false;
            }
            if ($i === $phraseStringLength - 1 - $i) {
                break;
            }
        }
        return true;
    }

    /**
     * 冒泡排序 O(n^2)
     * @param array $sortArray
     * @return array
     */
    public static function bubbleSort(array $sortArray)
    {
        if (empty($sortArray)) {
            return $sortArray;
        }
        $sortArrayLength = count($sortArray);
        for ($i = 0; $i < $sortArrayLength; $i++) {
            for ($j = 0; $j < $sortArrayLength - 1 - $i; $j++) {
                if ($sortArray[$j] > $sortArray[$j +1 ]) {
                    $temp = $sortArray[$j];
                    $sortArray[$j] = $sortArray[$j + 1];
                    $sortArray[$j + 1] = $temp;
                }
            }
        }
        return $sortArray;
    }
}

var_dump(Algorithm::isPhraseString('level'));

var_dump(Algorithm::bubbleSort([23, 4, 12, 435, 21, 99]));