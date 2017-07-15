<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/15
 * Time: 21:12
 */
// 生产环境中使用monolog纪录日志
require 'vendor/autoload.php';

// 导入monolog的命名空间
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;

// 设置MonoLog提供的日志记录器
$log = new Logger('my-app-name');
// 日志纪录器会把Logger::WARNING 及以上等级的日志消息写入 path/to/your.log文件
$log->pushHandler(new StreamHandler('path/to/your.log', Logger::WARNING));

// 让swiftMailer通过电邮发送错误消息
date_default_timezone_set('Asia/Shanghai');
// 添加SwiftMailer处理程序，让它处理重要数据
$transport = \Swift_SmtpTransport::newInstance('smtp.example.com', 587)
                                    ->setUsername('username')
                                    ->setPassword('password');
$mailer = \Swift_Mailer::newInstance($transport);
$message = \Swift_Message::newInstance()
            ->setSubject('Website error')
            ->setFrom(array('daemon@example.com' => 'john Doe'))
            ->setTo(array('admin@example.com'));
$log->pushHandler(new SwiftMailerHandler($mailer, $message, Logger::CRITICAL));
$log->critical('The server is no fire');


