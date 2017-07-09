<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/7/9
 * Time: 23:44
 */
// 将 PHP错误转换成ErrorException对象
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
   if (!(error_reporting() & $errno)) {
       // error_reporting指令没有设置这个错误,所以将其忽略
       return;
   }
   throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});
// 设置全局错误处理程序
// 注册错误处理程序
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
   if (!(error_reporting() & errno)) {
       // error_reporting指令没有设置这个错误,将其忽略
       return;
   }
   throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});
// 其他代码

//还原成之前的错误处理程序
restore_error_handler();