<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 23:14
 */
/**
 * 继承实现
 */
namespace PHP_Design_Patterns\Design_Patterns;

include_once 'InheritMath.php';

class ClientInherit
{
    private $added;
    private $divided;
    private $textNum;
    private $output;

    public function __construct()
    {
        $family = new InheritMath();
        $this->added = $family->simpleAdd(40, 60);
        $this->divided = $family->simpleDivide($this->added, 25);
        $this->textNum = $family->numToText($this->divided);
        $this->output = $family->addFace("Your results", $this->textNum);
        echo $this->output;
    }
}
$worker = new ClientInherit();