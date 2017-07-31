<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 22:03
 */

namespace PHP_Design_Patterns\Design_Patterns;

include_once 'IAbstract.php';
class NorthRegion extends IAbstract
{
    protected function giveCity(): string
    {
        // TODO: Implement giveCity() method.
        return 'Moose Breath';
    }
    protected function giveCost(): int
    {
        // TODO: Implement giveCost() method.
        return 210.54;
    }
}