<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 23:06
 */

namespace PHP_Design_Patterns\Design_Patterns;


class DoMath
{
    private $sum;
    private $quotient;

    public function simpleAdd($first, $second)
    {
        $this->sum = $first + $second;
        return $this->sum;
    }

    public function simpleDivide($dividend, $divisor)
    {
        $this->quotient = $dividend / $divisor;
        return $this->quotient;
    }
}