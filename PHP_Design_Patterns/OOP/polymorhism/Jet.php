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
        // TODO: Implement slow() method.
        return 120;
    }
    function cruise()
    {
        // TODO: Implement cruise() method.
        return 1200;
    }
    function fast()
    {
        return 1500;
        // TODO: Implement fast() method.
    }
}
$f22 = new Jet();
$jetSlow = $f22->slow();
$jetCruise = $f22->cruise();
$jetFast = $f22->fast();