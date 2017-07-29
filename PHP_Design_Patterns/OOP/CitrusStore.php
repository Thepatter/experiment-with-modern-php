<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/29
 * Time: 22:52
 */

namespace PHP_Design_Patterns\OOP;

require_once 'IProduct.php';
class CitrusStore implements IProduct
{
    function oranges()
    {
        // TODO: Implement oranges() method.
        return "CitrusStore sea-we not sell apple. <br/>";
    }
    function apples()
    {
        // TODO: Implement apples() method.
        return "CitrusStore sez-We have citrus fruit .<br>";
    }
}