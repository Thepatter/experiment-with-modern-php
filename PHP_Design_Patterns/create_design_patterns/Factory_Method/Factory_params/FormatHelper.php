<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/2
 * Time: 22:27
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params;


class FormatHelper
{
    private $topper;
    private $bottom;
    public function addTop()
    {
        $this->topper = "<!doctype html><html><head><link rel='stylesheet' type='text/css' href='products.php'/> <meta 
charset='UTF-8'><title>Map Factory</title></head><body>";
        return $this->topper;
    }
    public function closeUp()
    {
        $this->bottom = "</body></html>";
        return $this->bottom;
    }
}