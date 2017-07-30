<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/30
 * Time: 23:49
 */

namespace PHP_Design_Patterns\OOP\polymorhism;

include_once 'Poly.php';
class Car implements ISpeed
{
    function slow()
    {
        // TODO: Implement slow() method.
        $carSlow = 15;
        return $carSlow;
    }
    function cruise()
    {
        // TODO: Implement cruise() method.
        $carCruise = 65;
        return $carCruise;
    }
    function fast()
    {
        // TODO: Implement fast() method.
        $carZoom = 110;
        return $carZoom;
    }
}
