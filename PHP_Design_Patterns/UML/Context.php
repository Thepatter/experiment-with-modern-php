<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 22:20
 */

namespace experuse_interface\PHP_Design_Patterns\UML;

interface IStrategy
{
    public function algorithm($elements);
}
class Context
{
    private $strategy;

    public function __construct(IStrategy $strategy)
    {
        $this->strategy = $strategy;
    }
    public function algorithm($elements)
    {
        $this->strategy->algorithm($elements);
    }
}