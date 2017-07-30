<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/30
 * Time: 23:32
 */

namespace PHP_Design_Patterns\OOP;

require_once 'FurryPets.php';

class Cats extends FurryPets
{
    function __construct()
    {
        echo 'Cats ' . $this->fourLegs() . PHP_EOL;
        echo $this->makesSound("Meow, purrr") . "<br>";
    }

    private function ownsHouse()
    {
        return "I 'll just walk on this keyBoard" . PHP_EOL;
    }
}