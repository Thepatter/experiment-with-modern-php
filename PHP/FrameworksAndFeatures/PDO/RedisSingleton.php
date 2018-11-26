<?php
/**
 * Created by IntelliJ IDEA.
 * User: company
 * Date: 2018/11/26
 * Time: 18:28
 */

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
        self::$link->connect(DB::host);
        self::$link->auth(DB::password);
        self::$link->select(1);
        return self::$link;
    }
}