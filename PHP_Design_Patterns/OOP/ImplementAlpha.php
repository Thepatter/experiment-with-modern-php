<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/27
 * Time: 23:22
 */

namespace PHP_Design_Patterns\OOP;

include 'IMethodHolder.php';

class ImplementAlpha implements IMethodHolder
{
    public function getInfo($info)
    {
        // TODO: Implement getInfo() method.
        echo "This is NEWS!" . $info . "<br/>";
    }
    public function calculate($first, $second)
    {
        // TODO: Implement calculate() method.
        $calulated = $first * $second;
        return $calulated;
    }
    public function sendInfo($info)
    {
        // TODO: Implement sendInfo() method.
        return $info;
    }
    public function useMethods()
    {
        $this->getInfo("The sky is falling...");
        echo $this->sendInfo("Vote for Senator Snort!") . "<br/>";
        echo "You make $" . $this->calculate(20, 15) . " in your part-time job <br/>";
    }
}
$worker = new ImplementAlpha();
$worker->useMethods();