<?php

function bubbleSort(array $origin): array
{
    $originCount = count($origin);
    if ($originCount < 2) {
        return $origin;
    }
    for ($index = 0; $index < $originCount; ++$index) {
        for ($l = $index + 1; $l < $originCount; ++$l) {
            if ($origin[$index] > $origin[$l]) {
                $tem = $origin[$index];
                $origin[$index] = $origin[$l];
                $origin[$l] = $tem;
            } elseif ($origin[$index] == $origin[$l]) {
                continue;
            }
        }
    }
    return $origin;
}

$origin = [0 => 23, 1 => 2, 2 => 323, 3 => 53, 4 => 231, 5 => 12, 6 => 11, 7 => 1223, 8 => 55, 9 => 55, 10 => 301, 11 => 985, 12 => 996, 13 => 251, 14 => 404];

$order = bubbleSort($origin);

print_r($order);