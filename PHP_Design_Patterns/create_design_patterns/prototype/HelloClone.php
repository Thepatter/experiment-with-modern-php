<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/8
 * Time: 21:29
 */

//namespace PHP_Design_Patterns\create_design_patterns\prototype;


class HelloClone
{
    private $builtInConstructor;
    public function __construct()
    {
        echo "Hello, clone!</br>";
        $this->builtInConstructor = "Constructor creation</br>";
    }
    public function doWork()
    {
        echo $this->builtInConstructor;
        echo "I'm doing the work ";
    }
}
$original = new HelloClone();
$original->doWork();
$cloneIt = clone $original;
$cloneIt->doWork();