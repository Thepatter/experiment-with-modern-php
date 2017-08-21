<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/8/21
 * Time: 23:01
 */
include_once 'Mobile.php';
include_once 'MobileAdapter.php';
class Client
{
    private $mobile;
    private $mobileAdapter;
    public function __construct()
    {
        $this->mobile = new Mobile();
        $this->mobileAdapter = new MobileAdapter($this->mobile);
        $this->mobileAdapter->formatCSS();
        $this->mobileAdapter->formatGraphics();
        $this->mobileAdapter->horizontalLayout();
        $this->mobile->closeHTML();
    }
}