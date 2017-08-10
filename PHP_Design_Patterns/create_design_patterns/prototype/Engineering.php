<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/10
 * Time: 22:04
 */

//namespace PHP_Design_Patterns\create_design_patterns\prototype;

include_once 'IAcmePrototype.php';

class Engineering extends IAcmePrototype
{
    const UNIT = "Engineering";
    private $development = "programming";
    private $design = "digital artwork";
    private $sysAd = "system administration";

    /**
     * @param mixed $dept
     */
    public function setDept($dept)
    {
        switch ($dept)
        {
            case 301:
                $this->dept = $this->development;
                break;
            case 302:
                $this->dept = $this->design;
                break;
            case 303:
                $this->dept = $this->sysAd;
                break;

            default:
                $this->dept = "Unrecognized Engineering";
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