<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/10
 * Time: 21:59
 */

//namespace PHP_Design_Patterns\create_design_patterns\prototype;

include_once 'IAcmePrototype.php';

class Management extends IAcmePrototype
{
    const UNIT = "Management";
    private $research = "research";
    private $plan = "planning";
    private $operations = "operations";

    public function setDept($orgCode)
    {
        // TODO: Implement setDept() method.
        switch ($orgCode)
        {
            case 201:
                $this->dept = $this->research;
                break;
            case 202:
                $this->dept = $this->plan;
                break;
            case 203:
                $this->dept = $this->operations;
                break;
            default:
                $this->dept = "Unrecognized Management";
        }
    }
    public function getDept()
    {
        // TODO: Implement getDept() method.
        return $this->dept;
    }
    function __clone()
    {
        // TODO: Implement __clone() method.
    }
}