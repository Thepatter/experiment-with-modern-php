<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/1
 * Time: 21:19
 */

$closure = function ($name) {
    /**
     * sprintf - 返回格式化的字符串
     * string sprintf (string $format [, mixed $args [, mixed $...]])
     * 返回根据格式字符串生成的字符串
     */
    return sprintf('hello %s', $name);
};

echo $closure("Josh");

//闭包对象当做回调参数，传给array_map()函数
$num = array_map(function ($number) {
    return $number + 1;
}, [1, 2, 3]);
print_r($num);
// 具名函数回调
function incrementNumber ($number) {
    return $number + 1;
}

$number = array_map('incrementNumber', [11, 12, 13]);
print_r($number);
//闭包附加状态, 手动调用闭包对象的bindTo()方法或者使用use关键字把状态附加到PHP闭包上
// 使用use关键字把变量附加到闭包上时，附加的变量会记住附加时赋给它的值
function enclosePerson($name) {
    return function ($doCommand) use ($name) {
        return sprintf('%s, %s', $name, $doCommand);
    };
}
// 把字符串 "Clay"封装在闭包中
$clay = enclosePerson('Clay');
var_dump($clay);
// 传入参数，调用闭包
echo $clay('get me sweet tea!');
var_dump($clay);



