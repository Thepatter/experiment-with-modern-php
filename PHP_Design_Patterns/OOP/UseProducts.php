<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/29
 * Time: 22:54
 */

namespace PHP_Design_Patterns\OOP;

include 'FruitStore.php';
include 'CitrusStore.php';
// 类型提示 接口而非实现
class UseProducts
{
    public function __construct()
    {
        $appleSauce = new FruitStore();
        $orangeJuice = new CitrusStore();
        $this->doInterface($appleSauce);
        $this->doInterface($orangeJuice);
    }

    public function doInterface(IProduct $product)
    {
        echo $product->apples();
        echo $product->oranges();
    }
}

$worker = new UseProducts();