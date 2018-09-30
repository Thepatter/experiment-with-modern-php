<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018/9/30
 * Time: 14:31
 */

class AES
{
    const cipherAES128 = 'aes-128-cbc';

    const cipherAES256 = 'aes-256-cbc';

    private $key;

    private $iv;

    public function __construct($key = '', $iv = '')
    {
        $this->key = $key ?? substr(md5(self::cipherAES128, true), -16); // 3559808a5b6aa6a6
        $this->iv = $iv ?? str_repeat('0', openssl_cipher_iv_length(self::cipherAES128)); // 0000000000000000
    }

    /**
     * aes-128-cbc 加密明文填充
     * @param $source
     * @return string
     */
    private function addPKCS7Padding($source) {
        $source = trim($source);
        $block = 16;
        $pad = $block - (strlen($source) % $block);
        if ($pad <= $block) {
            $char = chr($pad);
            $source .= str_repeat($char, $pad);
        }
        return $source;
    }

    /**
     * 移除明文填充
     * @param $source
     * @return bool|string
     */
    private function removePKSC7Padding($source) {
        $char = substr($source, -1);
        $num = ord($char);
        $source = substr($source, 0, -$num);
        return $source;
    }

    /**
     * aes-128-cbc 加密
     * @param $origin
     * @return string
     */
    public function aes128Encrypt($origin)
    {
        return base64_encode(openssl_encrypt($this->addPKCS7Padding($origin), self::cipherAES128, $this->key, OPENSSL_NO_PADDING, $this->iv));
    }

    /**
     * aes-128-cbc 解密
     * @param $encrypt
     * @return bool|string
     */
    public function aes128Decrypt($encrypt)
    {
        return $this->removePKSC7Padding(openssl_decrypt(base64_decode($encrypt), self::cipherAES128, $this->key, OPENSSL_NO_PADDING, $this->iv));
    }

    /**
     * aes-256-cbc 加密
     * @param $origin
     * @return string
     */
    public function aes256Encrypt($origin)
    {
        return base64_encode(openssl_encrypt($this->aes256Padding($origin), self::cipherAES256, $this->key, OPENSSL_NO_PADDING, $this->iv));
    }

    /**
     * aes-256-cbc 解密
     * @param $encrypt
     * @return bool|string
     */
    public function aes256Decrypt($encrypt)
    {
        return $this->unPadding(openssl_decrypt(base64_decode($encrypt), self::cipherAES128, $this->key, OPENSSL_NO_PADDING, $this->iv));
    }

    /**
     * aes-256-cbc 填充
     * @param $data
     * @param int $blockSize
     * @return string
     */
    private function aes256Padding($data, $blockSize = 32)
    {
        $pad = $blockSize - (strlen($data) % $blockSize);
        return $data . str_repeat(chr($pad), $pad);
    }

    private function unPadding($text)
    {
        $pad = ord($text[strlen($text) - 1]);

        if ($pad > strlen($text))
        {
            return false;
        }

        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
        {
            return false;
        }

        return substr($text, 0, -1 * $pad);
    }

    /**
     * 0 填充
     * @param $data
     * @param int $blockSize
     * @return string
     */
    private function paddingZero($data, $blockSize = 16)
    {
        $pad = $blockSize - (strlen($data) % $blockSize);
        return $data . str_repeat("\0", $pad);
    }

    /**
     * 移除 0 填充
     * @param $data
     * @return string
     */
    private function unPaddingZero($data)
    {
        return rtrim($data, "\0");
    }

}