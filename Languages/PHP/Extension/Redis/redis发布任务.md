## redis发布

```php
<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/10/10
 * Time: 22:27
 */

/**
 * 发布耗时任务
 */
$redis = new \Redis;
$redis->connect('127.0.0.1');
for ($i = 0; $i < 20; $i++) {
    $redis->publish('redisPubAndSub', json_encode([
        'timeHex' => dechex(time()),
        'timeString' => date('Y-m-d H:i:s', time()),
        'timeDex' => time(),
        'useTime' => 20 + $i,
    ]));
}
$redis->close();
```