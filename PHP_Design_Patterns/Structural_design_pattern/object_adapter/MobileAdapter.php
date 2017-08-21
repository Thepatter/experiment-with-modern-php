<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/8/21
 * Time: 22:51
 */
include_once 'IFormat.php';
include_once 'Mobile.php';
class MobileAdapter implements IFormat
{
    private $mobile;
    public function __construct(IMobileFormat $mobileNow)
    {
        $this->mobile = $mobileNow;
    }
    public function formatCSS()
    {
        $this->mobile->formatCSS();
        // TODO: Implement formatCSS() method.
    }
    public function formatGraphics()
    {
        // TODO: Implement formatGraphics() method.
        $this->mobile->formatGraphics();
    }
    public function horizontalLayout()
    {
        // TODO: Implement horizontalLayout() method.
        $this->mobile->verticalLayout();
    }
}