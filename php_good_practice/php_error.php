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
// PHP 错误转换成ErrorException对象
/**
 * 设置用户自定义的错误处理函数
 * mixed set_error_handler (callable $error_handle [, int $error_types = E_ALL | E_STRICT])
 * error_handle
 * params error_handle 以下格式的回调：可以传入NULL 重置处理程序到默认状态。除了可以传入函数名，还可以传入引用对象和对象方法名的数组
 *        bool handle(int $errno, string $errstr [, string $errfile [, int $errline [, array $errcontext]]])
 * return 如果之前有定义过错误处理程序，则返回该程序名称的string；如果是内置的错误处理程序，则返回null。
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        // error_reporting() 指令没有设置这个错误，所以将其忽略
        return;
    }
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});
// 还原成之前的错误处理程序
restore_error_handler();