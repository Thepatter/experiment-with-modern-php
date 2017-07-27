<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/27
 * Time: 23:00
 */

namespace PHP_Design_Patterns\OOP;


abstract class OneTrickAbstract
{
    public $storeHere;
    abstract public function trick($whatever);
}