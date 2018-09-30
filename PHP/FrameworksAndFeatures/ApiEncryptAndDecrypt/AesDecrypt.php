<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018/4/13
 * Time: 16:53
 */

class AesDecrypt
{
    private $key;
    private $iv;

    public function __construct($key, $iv)
    {
        $this->key = $key;
        $this->iv = $iv;
    }

    public function aesEncrypt($str) {
        $str = $this->addPKCS7Padding($str);
        $encrypt_str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $str, MCRYPT_MODE_CBC, $this->iv);
        return base64_encode($encrypt_str);
    }
    // 解密方法
    public function aesDecrypt($str) {
        $str = base64_decode($str);
        $encrypt_str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $str, MCRYPT_MODE_CBC, $this->iv);
        $encrypt_str = $this->removePKSC7Padding($encrypt_str);
        return $encrypt_str;
    }
    // 填充算法
    private function addPKCS7Padding($source) {
        $source = trim($source);
        $block = mcrypt_get_block_size('rijndael-128', 'cbc');
        $pad = $block - (strlen($source) % $block);
        if ($pad <= $block) {
            $char = chr($pad);
            $source .= str_repeat($char, $pad);
        }
        return $source;
    }
    // 移去填充算法
    private function removePKSC7Padding($source) {
        $char = substr($source, -1);
        $num = ord($char);
        $source = substr($source, 0, -$num);
        return $source;
    }
    // 十六进制转字符串
    private function hexToStr($hex) {
        $string="";
        for($i=0;$i<strlen($hex)-1;$i+=2)
            $string.=chr(hexdec($hex[$i].$hex[$i+1]));
        return  $string;
    }
    // 字符串转十六进制
    private function strToHex($string) {
        $hex="";
        $tmp="";
        for($i=0;$i<strlen($string);$i++){
            $tmp = dechex(ord($string[$i]));
            $hex.= strlen($tmp) == 1 ? "0".$tmp : $tmp;
        }
        $hex=strtoupper($hex);
        return $hex;
    }
}