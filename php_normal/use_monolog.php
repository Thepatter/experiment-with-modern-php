<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/3
 * Time: 22:56
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

//准备日志记录器
$log = new Logger('myApp');
$log->pushHandler(new StreamHandler('logs/development.log', Logger::DEBUG));
$log->pushHandler(new treamHandler('logs/production.log', Logger::WARNING));

// 使用日志纪录器
$log->debug('This i a debug message');
$log->warning('This iss a warning message');