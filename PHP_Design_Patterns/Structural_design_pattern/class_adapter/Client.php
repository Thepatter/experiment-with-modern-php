<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/16
 * Time: 21:59
 */
include_once 'EuroAdapter.php';
include_once 'DollarCalc.php';

class Client
{
    private $requestNow;
    private $dollarRequest;

    public function __construct()
    {
        $this->requestNow = new \experuse_interface\PHP_Design_Patterns\Structural_design_pattern\class_adapter\EuroAdapter();
    }
}