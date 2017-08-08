<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/8
 * Time: 21:17
 */

namespace PHP_Design_Patterns\create_design_patterns\prototype;


abstract class CloneMe
{
    public $name;
    public $picture;
    abstract function __clone();
}
class Person extends CloneMe
{
    public function __clone()
    {

    }
    public function display()
    {
        echo "<img src='$this->picture'>";
        echo "<br /> $this->name <p/>";
    }
    public function __construct()
    {
        $this->picture = "cloneMan.png";
        $this->name = 'Original';
    }
}
$worker = new Person();
$worker->display();
$slacker = clone $worker;
$slacker->name = "Cloned";
$slacker->display();