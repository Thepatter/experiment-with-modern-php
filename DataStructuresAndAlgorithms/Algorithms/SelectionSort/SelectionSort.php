<?php

function selectSort(array $origin): array
{
    $originCount = count($origin);
    if ($originCount < 2) {
        return $origin;
    }
    for ($index = 0; $index < $originCount; ++$index) {
        $minIndex = $index;
        for ($l = $index + 1; $l < $originCount; ++$l) {
            if ($origin[$l] < $origin[$index]) {
                $minIndex = $l;
            }
        }
        $tmp = $origin[$index];
        $origin[$index] = $origin[$minIndex];
        $origin[$minIndex] = $tmp;
    }
    return $origin;
}

$origin = [
    0 => 123, 1 => 22, 2 => 33, 3 => 113, 4 => 54, 5 => 125, 6 => 181,
    7 => 89, 8 => 129, 9 => 985, 10 => 211, 11 => 251, 12 => 404
];

print_r(selectSort($origin));