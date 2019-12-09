<?php

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

class Secret
{
    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('token')) {
            $request->encrypt = false;
        }
        if (count($source = $request->all()) === 2 && (array_key_exists('secret', $source) && array_key_exists('data', $source))) {
            $secret = $source['secret'];
            $data = $source['data'];
            if ($request->method() === 'GET') {
                $secret = str_replace(' ', '+', $secret);
                $data = str_replace(' ', '+', $data);
            }
            $secretArray = explode('.', $secret);
            if (count($secretArray) !== 2) {
                return response()->json(createResponseData($request, 3000));
            }
            $aesKey = $secretArray[0];
            $key = rsaDecryptByPrivate($aesKey);
            $iv = base64_decode($secretArray[1]);
            $source = json_decode(base64_decode((new AesDecrypt($key, $iv))->aesDecrypt($data)));
            if (!$source) {
                return response()->json(createResponseData($request, 3000));
            }
            if (md5($source->data) !== $source->sign) {
                return response()->json(createResponseData($request, 3000));
            }
            $client = json_decode(base64_decode($source->data), true);
            if (!empty($client)) {
                foreach ($client as $key => $value) {
                    $request->{$key} = $value;
                    $request->offsetSet($key, $value);
                }
            }
            $request->encrypt = true;
        }
        //return $next($request);
    }
}