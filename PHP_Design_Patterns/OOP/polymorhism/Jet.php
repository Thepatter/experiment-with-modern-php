<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/30
 * Time: 23:47
 */

namespace PHP_Design_Patterns\OOP\polymorhism;

include_once 'Poly.php';
class Jet implements ISpeed
{
    function slow()
    {
        return 120;
    }
    function cruise()
    {
        return 1200;
    }
    function fast()
    {
        return 1500;
    }
}
$f22 = new Jet();
$jetSlow = $f22->slow();
$jetCruise = $f22->cruise();
$jetFast = $f22->fast();