<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/8/1
 * Time: 23:47
 */

namespace PHP_Design_Patterns\create_design_patterns\Factory_Method;

include_once 'GraphicFactory.php';
include_once 'TextFactory.php';

class Client
{
    private $someGraphicObject;
    private $someTextObject;
    public function __construct()
    {
        $this->someGraphicObject = new GraphicFactory();
        echo $this->someGraphicObject->startFactory() . PHP_EOL;
        $this->someTextObject = new TextFactory();
        echo $this->someTextObject->startFactory() . PHP_EOL;
    }
}
$worker = new Client();