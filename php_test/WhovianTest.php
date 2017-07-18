<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 7/18/2017
 * Time: 11:33 PM
 */
require 'Whovian.php';

class WhovianTest extends PHPUnit\Framework\TestCase
{
    // 测试__construct()方法
    public function testSetsDoctorWithConstructor()
    {
        $whovian = new Whovian('Peter Capaldi');
        $this->assertAttributeEquals('Peter Capaldi', 'favoriteDoctor', $whovian);
    }

}