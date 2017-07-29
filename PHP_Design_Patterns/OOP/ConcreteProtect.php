<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/29
 * Time: 23:20
 */

namespace PHP_Design_Patterns\OOP;

include_once 'ProtectVIs.php';
class ConcreteProtect extends ProtectVIs
{
    protected function countMoney()
    {
        // TODO: Implement countMoney() method.
        $this->wage = "Your hourly wage is $";
        echo $this->wage . $this->setHourly(36);
    }
    public function __construct()
    {
        $this->countMoney();
    }
}
$worker = new ConcreteProtect();