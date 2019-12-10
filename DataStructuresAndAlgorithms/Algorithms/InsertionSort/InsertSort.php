<?php

function insertSort(array $origin): array
{
    $originCount = count($origin);
    if ($originCount < 2) {
        return $origin;
    }
    for ($index = 1; $index < $originCount; ++$index) {
        $temp = $origin[$index];
        $j = $index - 1;
        for (; $j >= 0; --$j) {
            if ($origin[$j] > $temp) {
                $origin[$j + 1] = $origin[$j];
            } else {
                break;
            }
        }
        $origin[$j + 1] = $temp;
    }
    return $origin;
}

$origin = [
    0 => 123, 1 => 22, 2 => 33, 3 => 113, 4 => 54, 5 => 125, 6 => 181,
    7 => 89, 8 => 129, 9 => 985, 10 => 211, 11 => 251, 12 => 404
];

print_r(insertSort($origin));