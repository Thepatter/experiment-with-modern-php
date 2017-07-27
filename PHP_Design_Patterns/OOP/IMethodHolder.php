<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/27
 * Time: 23:20
 */
namespace PHP_Design_Patterns\OOP;

interface IMethodHolder
{
    public function getInfo($info);
    public function sendInfo($info);
    public function calculate($first, $second);
}