<?php

/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/16
 * Time: 21:34
 */
/** 计算美元价格的类 */
class DollarCalc
{
    private $dollar;
    private $product;
    private $service;
    public $rate = 1;
    public function requestCalc($productNow, $serviceNow)
    {
        $this->product = $productNow;
        $this->service = $serviceNow;
        $this->dollar = $this->product + $this->service;
        return $this->requestTotal();
    }
    public function requestTotal()
    {
        $this->dollar *= $this->rate;
        return $this->dollar;
    }
}