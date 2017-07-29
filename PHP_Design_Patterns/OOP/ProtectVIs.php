<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/29
 * Time: 23:18
 */

namespace PHP_Design_Patterns\OOP;


abstract class ProtectVIs
{
    abstract protected function countMoney();
    protected $wage;

    protected function setHourly($hourly)
    {
        $money = $hourly;
        return $money;
    }
}