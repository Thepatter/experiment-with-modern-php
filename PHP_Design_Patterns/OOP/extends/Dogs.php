<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/30
 * Time: 23:29
 */

namespace PHP_Design_Patterns\OOP;

require_once 'FurryPets.php';

class Dogs extends FurryPets
{
    function __construct()
    {
        echo "Dogs " . $this->fourLegs() . "<br/>";
        echo $this->makesSound("Woof, woof") . "<br/>";
        echo $this->guardsHouse() . "<br/>";
    }
    private function guardsHouse()
    {
        return "Grrrrr" . "<br/>";
    }
}