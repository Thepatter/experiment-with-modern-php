<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/2
 * Time: 22:12
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params;

use Whoops\Exception\Formatter;

include_once 'FormatHelper.php';
include_once 'Product.php';
class KyrgyzstanProduct
{
    private $mfgProduct;
    private $formatHelper;
    public function getProperties()
    {
        $this->formatHelper = new FormatHelper();
        $this->mfgProduct = $this->formatHelper->addTop();
        $this->mfgProduct .= <<<KYRGYZSTAN
<img src="Countries/Kyrgyzstan.png" class='pixRight' width='600' height='304'>
<header>Kyrgyzstan</header>
<p>A this is </p>
KYRGYZSTAN;
        $this->mfgProduct .= $this->formatHelper->closeUp();
        return $this->mfgProduct;

    }

}