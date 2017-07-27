<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/27
 * Time: 23:41
 */
namespace PHP_Design_Patterns\OOP;

interface IConnectInfo
{
    const HOST = "192.168.10.10";
    const UNAME = "homestead";
    const DBNAME = "wine";
    const PW = "secret";
    function testConnection();
}