<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/30
 * Time: 23:26
 */

namespace PHP_Design_Patterns\OOP;


class FurryPets
{
    protected $sound;
    protected function fourLegs()
    {
        return "walk on all fours";
    }
    protected function makesSound($petNoise)
    {
        $this->sound = $petNoise;
        return $this->sound;
    }
}