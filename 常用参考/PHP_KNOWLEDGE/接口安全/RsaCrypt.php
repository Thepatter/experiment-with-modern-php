<?php
/**
 * RSA 公私钥加密
 * Created by PhpStorm.
 * User: 76073
 * Date: 2018/4/15
 * Time: 21:43
 */

class RsaCrypt
{
    private $privateKey = "file://privateKey.pem";
    private $publicKey = "file://publicKey.pem";

    public function generateRSAKey()
    {
        $rsa = openssl_pkey_new(['digest_alg' => 'sha512', 'private_key_bits' => 512, 'private_key_type' => OPENSSL_KEYTYPE_DSA]);

        openssl_pkey_export($rsa, $privateKey);

        $publicKey = openssl_pkey_get_details($rsa);

        $publicKey = $publicKey['key'];

        file_put_contents('privateKey.pem', $privateKey);
        file_put_contents('publicKey,pem', $publicKey);
    }

    public function rsaEncrypt($string, $tag)
    {
        switch ($tag){
            case 'private':
                openssl_private_encrypt($string, $secret, $this->privateKey);
                return $secret;
            case 'public';
                openssl_public_encrypt($string,$secret, $this->publicKey);
                return $secret;
        }
    }

    public function rsaDecrypt($string, $tag)
    {
        if ($tag === 'private') {
            openssl_private_encrypt($string, $secret, $this->privateKey);
            return $secret;
        }
        if ($tag === 'public') {
            openssl_public_encrypt($string, $secret, $this->publicKey);
            return $secret;
        }
    }

    public function signByRSAPrivateKey($string)
    {
        $privateKey = "file://privateKey.pem";

        openssl_sign($string, $signature, $privateKey);

        return base64_encode($signature);
    }

    public function verifySignByRSAPublicKey($stringAndSign)
    {
        $publicKey = "file://publicKey.pem";

        $tokenArray = explode('.', $stringAndSign);

        $result = openssl_verify($tokenArray[0], base64_decode($tokenArray[1]), $publicKey);

        if ($result === 1) {
            return true;
        }

        return false;
    }
}