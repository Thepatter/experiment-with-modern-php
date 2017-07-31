<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 22:12
 */

namespace PHP_Design_Patterns\Design_Patterns;

include_once 'NorthRegion.php';
include_once 'WestRegion.php';

class Client
{
    public function __construct()
    {
        $north = new NorthRegion();
        $west = new WestRegion();
        $this->showInterface($north);
        $this->showInterface($west);
    }
    private function showInterface(IAbstract $region)
    {
        echo $region->displayShow() . PHP_EOL;
    }
}
$woker = new Client();