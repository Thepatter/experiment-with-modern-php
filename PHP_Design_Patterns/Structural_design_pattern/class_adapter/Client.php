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
        $this->requestNow = new EuroAdapter();
        $this->dollarRequest = new DollarCalc();
        $euro = "&8364";
        echo 'Euros' . $this->makeAdapterRequest($this->requestNow);
        echo 'dollars' , $this->makeDollarRequest($this->dollarRequest);
    }
    // 类型提示为接口的，参数可以为实现接口类的实例
    private function makeAdapterRequest(ITarget $req)
    {
        return $req->requestCalc(40,50);
    }
    private function makeDollarRequest(DollarCalc $req)
    {
        return $req->requestCalc(40, 50);
    }
}
$worker = new Client();