<?php

interface IPrototype
{
    const PROTO = "IPrototype";
    function __clone();
}
class DynamicObjectNaming implements IPrototype
{
    const CONCRETE = "[Concrete] DynamicObjectNaming";
    function __clone()
    {
        echo "This was dynamically created." . PHP_EOL;
    }
    public function __construct()
    {
        echo "This was dynamically created" . PHP_EOL;
    }
    public function doWork()
    {
        echo PHP_EOL . 'this is the assigned task' . PHP_EOL;
    }
}
$employeeData = array('DynamicObjectNaming', 'Tess', 'mar', 'John', 'eng', 'Olivia', 'man');
$don = $employeeData[0];
$employeeData[6] = new $don;
echo $employeeData[6]::CONCRETE;
$employeeData[6]->doWork();
$employeeName = $employeeData[5];
$employeeName = clone $employeeData[6];
echo $employeeName->doWork();
echo 'This is a clone of' . $employeeName::CONCRETE . PHP_EOL;
echo 'Child of: ' . $employeeName::PROTO;