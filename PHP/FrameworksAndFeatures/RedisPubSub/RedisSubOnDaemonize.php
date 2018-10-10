<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/10/10
 * Time: 22:45
 */

/** 守护进程化订阅消息 */
$pid = pcntl_fork();

if ($pid) {
    exit(0);
} elseif ($pid == -1) {
    exit('该运行环境不支持多进程');
}

posix_setsid();

$pid = pcntl_fork();

if ($pid == -1) {
    exit('该运行环境不支持多进程');
} elseif ($pid) {
    ini_set('default_socket_timeout', -1);
    $redis = new \Redis;
    $redis->pconnect('127.0.0.1');
    $channel = 'redisPubAndSub';
    $fun = function ($redis, $chan, $msg) use ($channel) {
        if ($chan === $channel) {
            $pid = pcntl_fork();
            if ($pid) {
                pcntl_wait($status);
            } elseif ($pid == 0) {
                $originMsg = json_decode($msg);
                // 单独的 redis 来存储数据进 redis
                $subRedis = new \Redis;
                $subRedis->connect('127.0.0.1');
                sleep($originMsg['useTime']);
                $originMsg['pid'] = posix_getpid();
                $originMsg['ppid'] = posix_getppid();
                /** 生成唯一 key */
                $cacheKey = dechex(time()) . substr(hash('md5', gethostname()), 6) . dechex(posix_getpid());
                $subRedis->set($cacheKey, json_encode($originMsg));
                $subRedis->close();
            }
        }

    };
    $redis->subscribe([$channel], $fun);
}