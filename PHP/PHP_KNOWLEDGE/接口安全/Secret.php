<?php

namespace App\Http\Middleware;

use App\Handlers\AesDecrypt;
use Closure;
/**
 * Class Secret
 * @package App\Http\Middleware
 * API 传输数据解密中间件
 */
class Secret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
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
        return $next($request);
    }
}
