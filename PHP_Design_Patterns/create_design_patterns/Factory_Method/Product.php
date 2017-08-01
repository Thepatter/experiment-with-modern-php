<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 23:32
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method;

/** 工厂方法设计模式中的第二个接口是Product。由于这是第一个实现，也是最简单的实现，所有文本和图像属性都只实现一个方法getProperties() */
interface Product
{
    /**
     * @return mixed
     * 在工厂方法的这个实现中getProperties()方法引入了多态，将用这个方法返回"文本"或"图像"。只要有正确的签名，它就能提供我们想要的结果
     * 同一方法getProperties()有多个（ploy）不同的形态（morphs）,这就是多态。，在这种情况下，其中一种形式返回文本，而另一种返回图像
     */
    public function getProperties();
}