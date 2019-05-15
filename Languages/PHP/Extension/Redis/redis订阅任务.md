## redis订阅任务

### 后台执行订阅任务

```php
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
    /** @var  $fun  该代码最多只能以两个子进程运行, 无法满足有新消息就开个子进程来处理的需求*/
    $twoProcess = function ($redis, $chan, $msg) use ($channel) {
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
                sleep($originMsg['use']);
                exit;
            }
        }

    };
    $redis->subscribe([$channel], $fun);
}
/** @var $f 该回调可以满足当消息来时新开进程来处理 */
$multiProcess = function ($redis, $chan, $msg) {
    if ($chan === 'pubAndSubChan') {
        $pid = pcntl_fork();
        if ($pid) {
            pcntl_wait($status);
        } elseif ($pid == 0) {
            $ppid = pcntl_fork();
            if ($ppid) {
                exit;
            } elseif ($ppid == 0) {
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
                sleep($originMsg['use']);
                exit;
            }
        }
    }
};

// 不过两个回调都会在最后一个消息处理完成后产生一个僵尸子进程,但当新消息来时会自动回收该僵尸进程,该文件中的类子进程能够调用
```