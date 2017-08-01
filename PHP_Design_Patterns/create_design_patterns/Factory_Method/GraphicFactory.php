<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 23:29
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method;

include_once 'Creator.php';
include_once 'GraphicProduct.php';

class GraphicFactory extends Creator
{
    protected function factoryMethod()
    {
        // TODO: Implement factoryMethod() method.
        $product = new GraphicProduct();
        return $product->getProperties();
    }
}