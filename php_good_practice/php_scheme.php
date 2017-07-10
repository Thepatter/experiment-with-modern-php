<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/10
 * Time: 22:20
 */
// 使用HTTP流封装协议与Flick API 通信
/**
 * file_get_contents() 函数的字符串参数起始是一个流标识符。http 协议会让PHP使用 HTTP 流封装协议。在这个参数中，http 之后是流的目标。
 * 流的目标之所以看起来像是普通的网页URL，是因为 HTTP 流封装协议就是这样规定的。其他流封装协议可能不是这样。
 */
//$json = file_get_contents(
//    'http://api.flickr.com/services/feeds/photo_public.gne?format=json'
//);

// 使用file：// 流封装协议创建一个读写 ／etc/hosts文件的流
// 隐式时尚使用file:// 流封装协议
//$handle = fopen('/etc/hosts', 'rb');
//while (feof($handle) !== false) {
//    echo fgets($handle);
//}
//fclose($handle);
// 显式使用file:// 流封装协议
//$handle = fopen('file:///etc/hots', 'rb');
//while (feof($handle) !== false) {
//    echo fgets($handle);
//}
//
//fclose($handle);

// 流上下文。使用流上下文对象来使用，file_get_contents() 函数发送 HTTP POST 请求。
try {
    $requestBody = '{"username":"josh"}';
    /**
     * stream_context_create - 创建资源流上下文
     * resource stream_context_create([array $options [, array $params]])
     * 创建并返回一个资源流上下文，该资源流中包含了options中提前设定的所有参数的值
     * params  options  鼻血是一个二维关联数组，格式为：$arr['wrapper']['option'] = $value，默认是一个空数组
     *         params   必须是$arr['parameter'] = $value 格式的关联数组。参考 context parameters 里的比标准资源流参数列表
     * return  上下文资源流，类型为resource
     */
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/json;charset=utf-8;\r\n" .
                        "Content-Length: " . strlen($requestBody),
            'content' => $requestBody
        )
    ));
    $response = file_get_contents('http://sample.app/test/http',false, $context);
    //echo json_encode($response);
} catch (Exception $e) {
    echo $e->getMessage();
}
//$opts = array(
//    'http' => array(
//        'method' => 'GET',
//        'header' => "Accept-language: en\r\n" .
//                    "Cookie: foo=bar\r\n"
//
//    )
//);
//$context = stream_context_create($opts);
//$fp = fopen('http://www.qq.com', 'r', false, $context);
//fpassthru($fp);
//fclose($fp);