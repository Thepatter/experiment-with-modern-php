<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/1
 * Time: 19:55
 */
// 一个简单的生成器
function myGenerator() {
    yield 'value1';
    yield 'value2';
    yield 'value3';
}

foreach (myGenerator() as $yieldedValue) {
    echo $yieldedValue, PHP_EOL;
}
//生成一个范围内的数值
function makeRange($length) {
    $data = [];
    for ($i = 0; $i < $length; $i++) {
        $data[] = $i;
    }
    return $data;
}

$customRange = makeRange(1000000);
foreach ($customRange as $item) {
    //echo $item, PHP_EOL;
}
//使用生成器
function makeYield($length) {
    for ($i = 0; $i < $length; $i++) {
        yield $i;
    }
}
foreach (makeYield(1000000) as $item) {
    //echo $item, 'this is yield', PHP_EOL;
}
//使用生成器处理CSV文件
function getRows($file) {
    $handle = fopen($file, 'rb');
    if ($handle === false) {
        throw new Exception();
    }
    while (feof($handle) === false) {
        yield fgetcsv($handle);
    }
    fclose($handle);
}
foreach (getRows('data.csv') as $row) {
    print_r($row);
}