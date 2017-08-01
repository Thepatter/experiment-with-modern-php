<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 23:14
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method;

/**
 * Class Creator
 * @package PHP_Design_Patterns\create_design_patterns\Factory_Method
 * 这个例子只返回文本。这是一个涉及地图和文本文字的项目，开发人员知道必须为这个项目创建不同的文本和图像元素。但他并不知道究竟需要创建
 * 多少个图像-文本对，甚至不确定客户希望增加什么。客户只是告诉他要有一个地图图像，还要增加相应的描述性文本。
 * 首先创建一个很小的工厂方法涉及，在屏幕上输出文本。分别显示图像信息和文本信息。下一步修改这个项目适应任意数目的文本和图像
 */
/** 建立工厂 Creator接口，这个实现中，使用了一个抽象类作为Creator接口*/
abstract class Creator
{
    protected abstract function factoryMethod();
    public function startFactory()
    {
        $mfg = $this->factoryMethod();
        return $mfg;
    }
}