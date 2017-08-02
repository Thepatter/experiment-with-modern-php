<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/2
 * Time: 22:20
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params;

include_once 'CountryFactory.php';
include_once 'KyrgyzstanProduct.php';

class client
{
    private $countryFactory;
    public function __construct()
    {
        $this->countryFactory = new CountryFactory();
        echo $this->countryFactory->doFactory(new KyrgyzstanProduct());
    }
}
$worker = new Client();