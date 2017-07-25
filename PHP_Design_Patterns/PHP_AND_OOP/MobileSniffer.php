<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/25
 * Time: 23:39
 */
namespace PHP_Design_Patterns\PHP_AND_OOP;

// 用户代理作为对象属性
class MobileSniffer
{
    private $userAgent;
    private $device;
    private $browser;
    private $deviceLength;
    private $browserLength;

    public function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->userAgent = strtolower($this->userAgent);
        $this->device = ['iphone', 'ipad', 'android', 'silk', 'blcakberry', 'touch'];
        $this->browser = ['firefox', 'chrome', 'opera', 'msie', 'safari', 'blackberry', 'trident'];
        $this->deviceLength = count($this->device);
        $this->browserLength = count($this->browser);
    }

    public function findDevice()
    {
        for ($uaSniff = 0; $uaSniff < $this->deviceLength; $uaSniff ++) {
            /**
             * strstr -- 查找字符串首次出现
             * string strstr(string $haystack, mixed $needle [, bool $before_needle = false])
             * 返回haystack字符串从needle第一次出现的位置开始到haystack结尾的字符串
             * 该函数区分大小写，不区分大小写为stristr()
             * haystack 输入字符串
             * needle  如果needle不是一个字符串，那么它将被转化为整型并且作为字符的序号来使用
             * before_needle    如果true，将返回 needle 在 haystack 中的位置之前的部分
             * 返回字符串的一部分或者false(未找到needle）
              *
             */
            if (strstr($this->userAgent, $this->device[$uaSniff])) {
                return $this->device[$uaSniff];
            }
        }
    }

    public function findBrowser()
    {
        for ($uaSniff = 0; $uaSniff < $this->browserLength; $uaSniff ++) {
            if (strstr($this->userAgent, $this->browser[$uaSniff])) {
                return $this->browser[$uaSniff];
            }
        }
    }
}