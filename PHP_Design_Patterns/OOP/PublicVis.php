<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/29
 * Time: 23:27
 */

namespace PHP_Design_Patterns\OOP;

//公共方法使用类中的私有方法和属性
class PublicVis
{
    private $password;
    private function openSesame($someData)
    {
        $this->password = $someData;
        if ($this->password == 'secret') {
            echo "You're in !" . PHP_EOL;
        } else {
            echo "Release the hounds!" . PHP_EOL;
        }
    }
    public function unlock($safe)
    {
        $this->openSesame($safe);
    }
    public function setPassword($data)
    {
        $this->password = $data;
        print_r('hello' . $this->password);
    }
}
$worker = new PublicVis();
$worker->unlock("secret");
$worker->unlock("dun");
$worker->setPassword("123456");