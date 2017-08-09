<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/9
 * Time: 22:08
 */

namespace PHP_Design_Patterns\create_design_patterns\prototype;


abstract class IAcmePrototype
{
    protected $name;
    protected $id;
    protected $employeePic;
    protected $dept;

    //dept
    abstract function setDept($orgCode);
}