## redis连接

### 使用 pecl-redis 扩展
```php
<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/11/26
 * Time: 18:28
 */

interface redisConfig
{
    const host = '127.0.0.1';
    const db = 1;
    const password = '';
}

class RedisSingleton
{
    private static $link = null;

    private function __construct()
    {
    }

    public static function getConnection()
    {
        if (self::$link) {
            return self::$link;
        }
        self::$link = new \Redis();
        self::$link->connect(redisConfig::host);
        self::$link->auth(redisConfig::password);
        self::$link->select(redisConfig::db);
        return self::$link;
    }
}
```
