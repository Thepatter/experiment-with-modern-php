<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 22:05
 */

namespace PHP_Design_Patterns\Design_Patterns;

include_once 'IAbstract.php';

class WestRegion extends IAbstract
{
    protected function giveCost(): int
    {
        // TODO: Implement giveCost() method.
        $solarSavings = 2;
        $this->valueNow = 210.54/$solarSavings;
        return $this->valueNow;
    }
    protected function giveCity(): string
    {
        // TODO: Implement giveCity() method.
        return 'Rattlesnake Gulch';
    }
}