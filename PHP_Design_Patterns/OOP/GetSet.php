<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/29
 * Time: 23:40
 */

namespace PHP_Design_Patterns\OOP;

// 使用获取方法和设置方法
class GetSet
{
    private $dataWareHouse;

    function __construct($data)
    {
        $this->setter($data);
        $got = $this->getter();
        echo $got;
    }

    private function getter()
    {
        return $this->dataWareHouse;
    }
    private function setter($setValue)
    {
        $this->dataWareHouse = $setValue;
    }
}
$worker = new GetSet(400);