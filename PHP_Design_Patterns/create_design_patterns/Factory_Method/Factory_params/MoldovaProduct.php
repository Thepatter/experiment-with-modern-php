<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/2
 * Time: 22:38
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params;

include_once 'FormatHelper.php';
include_once 'Product.php';

class MoldovaProduct implements Product
{
    private $mfgProduct;
    private $formatHelper;
    private $countryNow;
    public function getProperties()
    {
        // 从外部文本文件加载文本说明
        $this->countryNow = file_get_contents('CountryWriteups/Moldova.txt');
        $this->formatHelper = new FormatHelper();
        $this->mfgProduct = $this->formatHelper->addTop();
        $this->mfgProduct .= "<img src='Countries/Moldova.png' class='pixRight' width='208' height='450'>";
        $this->mfgProduct .= "<header>Moldova</header>";
        $this->mfgProduct .= "<p>$this->countryNow</p>";
        $this->mfgProduct .= $this->formatHelper->closeUp();
        return $this->mfgProduct;
    }
}