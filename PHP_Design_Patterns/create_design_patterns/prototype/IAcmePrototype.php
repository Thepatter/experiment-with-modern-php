<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/9
 * Time: 22:08
 */

//namespace PHP_Design_Patterns\create_design_patterns\prototype;


abstract class IAcmePrototype
{
    protected $name;
    protected $id;
    protected $employeePic;
    protected $dept;

    //dept
    abstract function setDept($orgCode);
    abstract function getDept();
    // name
    public function setName($emName)
    {
        $this->name = $emName;
    }
    public function getName()
    {
        return $this->name;
    }
    // id
    public function setId($emID)
    {
        $this->id = $emID;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setPic($ePic)
    {
        $this->employeePic = $ePic;
    }
    public function getPic()
    {
        return $this->employeePic;
    }
    abstract function __clone();
}