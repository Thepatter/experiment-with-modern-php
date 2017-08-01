<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 22:11
 */

namespace PHP_Design_Patterns\UML;


class Proxy extends ISubject
{
    private $realSubject;
    protected function request()
    {
        $this->realSubject = new RealSubject();
        $this->realSubject->request();
    }
    public function login($un, $pw)
    {
        if ($un === 'Professional' && $pw === 'acp')
        {
            $this->request();
        } else {
            print "Cry 'Havoc, and let slip the dogs of war!";
        }

    }
}