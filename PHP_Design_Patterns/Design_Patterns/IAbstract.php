<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/31
 * Time: 21:47
 */

namespace PHP_Design_Patterns\Design_Patterns;

/**
 * Class IAbstract
 * @package PHP_Design_Patterns\Design_Patterns
 * 两个简单的实现扩展一个简单的抽象类。
 */

abstract class IAbstract
{
    // 对所有实现都可用的属性
    protected $valueNow;
    /**
     * 所有实现都必须包含以下两个方法
     */
    // 必须返回十进制值,返回类型提示
    abstract protected function giveCost(): int;
    // 必须返回字符串值
    abstract protected function giveCity(): string;
    /**
     * 这个具体函数对所有类实现都可用，而不覆盖内容
     */
    public function displayShow()
    {
        $stringCost = $this->giveCost();
        $stringCost = (string)$stringCost;
        $allTogether = ("Cost: $" . $stringCost . "for" . $this->giveCity());
        return $allTogether;
    }

}