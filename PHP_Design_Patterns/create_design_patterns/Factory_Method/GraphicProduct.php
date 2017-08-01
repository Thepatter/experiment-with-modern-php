<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 23:43
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method;

include_once 'Product.php';
class GraphicProduct implements Product
{
    private $mfgProduct;
    public function getProperties()
    {
        // TODO: Implement getProperties() method.
        $this->mfgProduct = "This is a graphic.<3";
        return $this->mfgProduct;
    }
}