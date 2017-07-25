<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 7/18/2017
 * Time: 11:33 PM
 */

require dirname(__DIR__) . './src/Whovian.php';

class WhovianTest extends PHPUnit_Framework_TestCase
{
    // 测试__construct()方法
    public function testSetsDoctorWithConstructor()
    {
        $whovian = new Whovian('Peter Capaldi');
        /**
         * PHPUnit 提供的断言方法 assertAttributeEquals() 接收三个参数.第一个参数是期望值,第二个参数是属性名,第三个参数是
         * 要检查的对象,可以使用PHP的反射功能检查并验证受保护的对象.这个断言方法能检查对象的内部状态,而且不用依赖某个未测试的获取方法
         */
        $this->assertAttributeEquals('Peter Capaldi', 'favoriteDoctor', $whovian);
    }
    /**
     * 测试确认Whovian 实例的say() 方法会返回一个包含最喜欢的医生名字的字符串
     *
     */
    public function testSaysDoctorName()
    {
        $whovian = new Whovian('David Tennant');
        // 断言方法assertEquals 比较两个值,第一个参数是期望值,第二个参数是要检查的值
        $this->assertEquals('The best doctor is David Tennant', $whovian->say());
    }
    public function testRespondToInAgreement()
    {
        $whovian = new Whovian('David Tennant');
        $opinion = 'David Tennant is the best doctor, period';
        $this->assertEquals('I agree!', $whovian->respondTo($opinion));
    }
    // 测试反对respondTo方法
    public function testRespondToInDisagreement()
    {
        $whovian = new Whovian('David Tennant');
        $opinion = 'No way. Matt Smith was awesome!';
        $whovian->respondTo($opinion);
    }

}
