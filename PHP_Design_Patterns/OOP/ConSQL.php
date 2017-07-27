<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/27
 * Time: 23:43
 */

namespace PHP_Design_Patterns\OOP;

include 'IConnectInfo.php';
class ConSQL implements IConnectInfo
{
    /**
     * 使用作用域解析操作符传递值
     */
    private $server = IConnectInfo::HOST;
    private $currentDB = IConnectInfo::DBNAME;
    private $user = IConnectInfo::UNAME;
    private $pass = IConnectInfo::PW;

    public function testConnection()
    {
        // TODO: Implement testConnection() method.
        $hookup = new \mysqli($this->server, $this->user, $this->pass, $this->currentDB);

        if (mysqli_connect_error()) {
            die("bad mojo");
        }
        print "You're hooked up Ace! <br/>" . $hookup->host_info;
        $hookup->close();
    }
}

$useConstant  = new ConSQL();
$useConstant->testConnection();