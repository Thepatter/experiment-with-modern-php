<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/2
 * Time: 22:04
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params;

/**
 * Class CountryFactory
 * @package PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params
 * 具体的创建者类CountryCreator实现了factoryMethod()，并提供了代码提示要求的参数
 */
include_once 'Creator.php';
class CountryFactory extends Creator
{
    private $country;
    protected  function factoryMethod(Product $product)
    {
        // TODO: Implement factoryMethod() method.
        $this->country = $product;
        return $this->country->getProperties();
    }
}