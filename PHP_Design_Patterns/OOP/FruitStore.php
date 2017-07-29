<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/29
 * Time: 22:49
 */

namespace PHP_Design_Patterns\OOP;

include_once 'IProduct.php';

class FruitStore implements IProduct
{
    public function apples()
    {
        return "FruitStore sew-We have apples. <br>";
    }

    function oranges()
    {
        // TODO: Implement oranges() method.
        return "FruitStore set-We have no citrus fruit. <br>";
    }
}