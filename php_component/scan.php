<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/4
 * Time: 18:31
 */
// 使用composer自动加载器
require 'vendor/autoload.php';
// 实例Guzzle HTTP客户端
$client = new \GuzzleHttp\Client();
// 打开并迭代处理CSV
//$csv = \League\Csv\Reader::createFromPath($argv[1]);
$csv = \League\Csv\Reader::createFromPath($argv[1]);

foreach ($csv as $csvRow) {
    try {
        // 发送http options请求
        $httpResponse = $client->options($csvRow[0]);

        // 检查http响应状态码
        if ($httpResponse->getStatusCode() >= 400) {
            throw new \Exception();
        }
    } catch (\Exception $e) {
        // 标准输出死链
        echo $csvRow[0] . PHP_EOL;
    }
}
