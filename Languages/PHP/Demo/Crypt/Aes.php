<?php

class RequestApi
{
    const APP_ID = '4f30e5156a404dd2b7f5d86eccedeac4';

    const KEY = 'VxwJRypPI5S7GBY9';

    private $requestParam;

    public function __construct()
    {

    }
    public function getCrypt(array $origin)
    {
        return $this->encrypt(json_encode($origin));
    }
    public function setRequestParam(array $origin)
    {
        $request['appId'] = static::APP_ID;
        $request['data'] = $this->encrypt(json_encode($origin));
        $this->requestParam = http_build_query($request);
        return $this;
    }
    public function setRequestParamByOpenssl(array $origin)
    {
        $jsonData = json_encode($origin);
        $pkcs5padding = $this->pkcs5_pad(json_encode($origin), 32);
//        return bin2hex(openssl_encrypt($pkcs5padding, 'AES-128-ECB', static::KEY, 3));
        return bin2hex(openssl_encrypt($jsonData, 'AES-128-ECB', static::KEY, OPENSSL_RAW_DATA));
    }
    public function decryptOpenssl($crypt)
    {
        return openssl_decrypt($this->hexToStr($crypt),'AES-128-ECB', static::KEY, 1);
    }
    public function requestApi($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->requestParam,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            var_dump($err);exit;
        }
        return $response;

    }
    public function encrypt($input) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, static::KEY, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = bin2hex($data);
        return $data;
    }
    private function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    public function decrypt($sStr) {
        $decrypted= mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            static::KEY,
            $this->hexToStr($sStr),
            MCRYPT_MODE_ECB
        );
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }
    private function hexToStr($hex) {
        $string="";
        for($i=0;$i<strlen($hex)-1;$i+=2)
            $string.=chr(hexdec($hex[$i].$hex[$i+1]));
        return  $string;
    }
    public function pkcs7En($arr)
    {
        $data = json_encode($arr);
        $pkcs7 = $this->aes256Padding($data);
        return bin2hex(openssl_encrypt($pkcs7, 'AES-128-ECB', static::KEY, OPENSSL_NO_PADDING));
    }
    private function aes256Padding($data, $blockSize = 32)
    {
        $pad = $blockSize - (strlen($data) % $blockSize);
        return $data . str_repeat(chr($pad), $pad);
    }
}