<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/12/29
 * Time: 16:50
 */

$n = 50000000;
ini_set('memory_limit', '4096m');
$start = microtime(true);
$b = [];
$count = 0;
for ($i = 2; $i <= $n; $i++) {
    $b[] = $i;
}
$i = 2;
while ($i * $i <= $n) {
    if (isset($b[$i])) {
        $count++;
        $k = 2 * $i;
        while ($k <= $n) {
            unset($b[$k]);
            $k += $i;
        }
    }
    $i++;
}
while ($i <= $n) {
    if (isset($b[$i])) {
        $count++;
    }
    $i++;
}
$end = microtime(true);
echo "primes: " . $count . PHP_EOL;
echo "milliseconds: " . ($end - $start) * 1000;