<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/25
 * Time: 23:32
 */
// 类的单一职责性
class TellAll
{
    private $userAgent;

    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        echo $this->userAgent;
    }
}
$tellAll = new TellAll();