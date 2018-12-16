<?php
/**
 * Created by PhpStorm.
 * User: zhangyaowen
 * Date: 2018-12-16
 * Time: 18:20
 */

$originalArray = ['a', 'b', 'c', 'd', 'e', 'f', 'j'];
$originalArrayLength = count($originalArray);
unset($originalArray[1]);

var_dump($originalArray);

$newArray = [];
foreach ($originalArray as $key => $value) {
    echo $key . ' => ' . $value . ' ';
    $newArray[] = $value;
}

var_dump($newArray);