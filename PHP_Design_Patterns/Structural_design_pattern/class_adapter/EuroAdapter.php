<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/16
 * Time: 21:44
 */
include_once 'EuroCalc.php';
include_once 'ITarget.php';
/** 欧元适配器 */
class EuroAdapter extends EuroCalc implements ITarget
{
    public function __construct()
    {
        $this->requester();
    }

    function requester()
    {
        $this->rate = 0.8111;
        return $this->rate;
    }
}