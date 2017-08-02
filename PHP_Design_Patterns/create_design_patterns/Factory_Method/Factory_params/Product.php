<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/2
 * Time: 22:10
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method\Factory_params;

/** 具体的产品变化并不会改变原来的Product接口 */
interface Product
{
    public function getProperties();
}