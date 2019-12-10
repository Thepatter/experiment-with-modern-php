<?php

function binarySearchRecursive(array $order, int $searchValue, $start, $end): int
{
    $middleIndex = $start + (int) (($end - $start) / 2);
    if ($searchValue > $order[$middleIndex]) {
        return binarySearchRecursive($order, $searchValue, $middleIndex + 1, $end);
    } elseif ($searchValue < $order[$middleIndex]) {
        return binarySearchRecursive($order, $searchValue, $start, $middleIndex - 1);
    } else {
        return $middleIndex;
    }
}

function binarySearchLoop(array $order, int $searchValue)
{
    $startIndex = 0;
    $endIndex = count($order) - 1;
    while ($startIndex <= $endIndex) {
        $middleIndex = $startIndex + (int) (($endIndex - $startIndex) / 2);
        if ($searchValue > $order[$middleIndex]) {
            $startIndex = $middleIndex + 1;
        } elseif ($searchValue < $order[$middleIndex]) {
            $endIndex = $middleIndex - 1;
        } else {
            return $middleIndex;
        }
    }
    return -1;
}

$orderArray = [0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => 9, 9 => 11, 10 => 13, 11 => 15];

$searchValue = 3;

echo binarySearchRecursive($orderArray, $searchValue, 0, 10) . PHP_EOL;

echo binarySearchLoop($orderArray, $searchValue) . PHP_EOL;