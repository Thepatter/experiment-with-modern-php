<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/14
 * Time: 23:45
 */
require 'vendor/autoload.php';

// 设置whoops提供的处理程序
$whoops = new \Whoops\Run();
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
$whoops->register();
function fooBar()
{
    throw new Exception('Something broke');
}

function bar()
{
    fooBar();
}
bar();