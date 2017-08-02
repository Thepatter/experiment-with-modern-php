<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 23:37
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method;

include_once 'Product.php';
class TextProduct implements Product
{
    private $mfgProduct;

    public function getProperties()
    {
        // 开始heredoc格式化
        $this->mfgProduct = <<<MAIL
<!doctype html>
<html>
<head>
<style type="text/css">
header {
color: #900;
font-weight: bold;
font-size: 24px;
font-family: Verdana,Geneva, sans-serif;
}
p {
font-family: Verdana, Geneva,sans-serif;
font-size:12px;
}
</style>
<meta charset="UTF-8"><title>Mail</title></head>
</head>
MAIL;

        return $this->mfgProduct;
    }
}