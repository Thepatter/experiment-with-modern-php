<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 23:23
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method;

include_once 'Creator.php';
include_once 'TextProduct.php';

/** 工厂类扩展Creator,并实现了factoryMethod()方法。factoryMethod() 实现通过一个Product方法返回一个文本或图像产品 */
class TextFactory extends Creator
{
    protected  function factoryMethod()
    {
        // TODO: Implement factoryMethod() method.
        $product = new TextProduct();
        return $product->getProperties();
    }
}