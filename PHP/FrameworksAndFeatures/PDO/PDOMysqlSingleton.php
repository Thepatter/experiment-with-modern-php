<?php
/**
 * Created by PhpStorm.
 * User: company
 * Date: 2018/11/24
 * Time: 11:32
 */

interface DB
{
    const host = '127.0.0.1';

    const port = '3306';

    const username = 'debian-sys-maint';

    const password = 'YICNg0T8YYkEzyBD';

    const dbName = 'test';

    const weChatFansComparedTable = 'tx_wechat_fans_compared';
}

class PDOMysqlSingleton
{
   private static $link = null;

   public static function getLink() {
       if (self::$link) {
           return self::$link;
       }
       $dsn = 'mysql:dbname=' . DB::dbName . ';host=' . DB::host . ';port=' . DB::port  . ';charset=UTF8';
       self::$link = new PDO($dsn, DB::username, DB::password);
       return self::$link;
   }

   public static function __callStatic($name, $arguments)
   {
       // TODO: Implement __callStatic() method.
       $callback = [self::getLink(), $name];
       return call_user_func_array($callback, $arguments);
   }
}