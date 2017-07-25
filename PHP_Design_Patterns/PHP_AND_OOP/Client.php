<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/26
 * Time: 00:02
 */

namespace PHP_Design_Patterns\PHP_AND_OOP;

ini_set('display_errors', "1");
ERROR_REPORTING(E_ALL);
use PHP_Design_Patterns\PHP_AND_OOP\MobileSniffer;
include 'MobileSniffer.php';
class Client
{
    private $mobSniff;

    public function __construct()
    {
        $this->mobSniff = new MobileSniffer();
        echo "Device = " . $this->mobSniff->findDevice() . "<br/>";
        echo "Browser = " . $this->mobSniff->findBrowser() . "<br/>";
    }
}

$trigger = new Client();