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
        $this->mfgProduct = "<!doctype html><html><head><meta charset='UTF-8'>";
        $this->mfgProduct .= "<title>Map Factory</title>";
        $this->mfgProduct .= "</head><body>";
        $this->mfgProduct .= "<img src='Mail.png' width='500' height='500' />";

        $this->mfgProduct .= "</body></html>";
        return $this->mfgProduct;
    }
}