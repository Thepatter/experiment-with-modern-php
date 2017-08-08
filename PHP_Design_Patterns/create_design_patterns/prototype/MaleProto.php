<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/8
 * Time: 21:44
 */

//namespace PHP_Design_Patterns\create_design_patterns\prototype;

include_once 'IPrototype.php';

class MaleProto extends IPrototype
{
    const gender = "MALE";
    public $mated;
    public function __construct()
    {
        $this->eyeColor = "red";
        $this->wingBeat = "220";
        $this->unitEyes = "760";
    }
    function __clone()
    {
        // TODO: Implement __clone() method.
    }
}