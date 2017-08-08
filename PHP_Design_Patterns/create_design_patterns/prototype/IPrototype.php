<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/8
 * Time: 21:40
 */

//namespace PHP_Design_Patterns\create_design_patterns\prototype;


abstract class IPrototype
{
    public $eyeColor;
    public $wingBeat;
    public $unitEyes;
    abstract function __clone();
}