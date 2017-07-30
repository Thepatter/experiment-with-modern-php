<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/30
 * Time: 23:36
 */

namespace PHP_Design_Patterns\OOP;

include_once 'Dogs.php';
include_once 'Cats.php';

class Client
{
    function __construct()
    {
        $dogs = new Dogs();
        $cats = new Cats();
    }
}
$worker = new Client();