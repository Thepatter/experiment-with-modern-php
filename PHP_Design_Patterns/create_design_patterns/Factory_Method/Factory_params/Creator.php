<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/2
 * Time: 21:57
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params;

/**
 * Class NewCreator
 * @package PHP_Design_Patterns\create_design_patterns\Factory_Method
 * 参数化工厂
 * 客户只需要处理一个具体工厂，工厂方法操作有一个参数，指示需要创建的产品。而在原来的设计中，每个产品都要有自己的工厂，
 * 不需要另外传递参数；产品实现依赖于各个产品的特定工厂。
 * 要从参数化工厂方法设计模式实现多个产品，只需要使用Product接口实现多个具体产品。
 */
abstract class Creator
{
    protected abstract function factoryMethod(Product $product);
    public function doFactory($productNow)
    {
        $countryProduct = $productNow;
        $mfg = $this->factoryMethod($countryProduct);
        return $mfg;
    }
}