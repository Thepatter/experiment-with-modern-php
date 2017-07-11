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
//try {
//    $requestBody = '{"username":"josh"}';
//    /**
//     * stream_context_create - 创建资源流上下文
//     * resource stream_context_create([array $options [, array $params]])
//     * 创建并返回一个资源流上下文，该资源流中包含了options中提前设定的所有参数的值
//     * params  options  鼻血是一个二维关联数组，格式为：$arr['wrapper']['option'] = $value，默认是一个空数组
//     *         params   必须是$arr['parameter'] = $value 格式的关联数组。参考 context parameters 里的比标准资源流参数列表
//     * return  上下文资源流，类型为resource
//     */
//    $context = stream_context_create(array(
//        'http' => array(
//            'method' => 'POST',
//            'header' => "Content-Type: application/x-www-form-urlencoded",
//            'content' => $requestBody
//        )
//    ));
//    //$response = file_get_contents('http://sample.app/test/http',false, $context);
//    //echo json_encode($response);
//} catch (Exception $e) {
//    echo $e->getMessage();
//}
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
//function send_post($url, $post) {
//    $post_data = http_build_query($post);
//    $options = array(
//        'http' => array(
//            'method' => 'POST',
//            'header' => 'Content-type:application/x-www-form-urlencoded',
//            'content' => $post_data,
//            'timeout' => 60
//        )
//    );
//    $context = stream_context_create($options);
//    $result = file_get_contents($url, false, $context);
//    return $result;
//}

//$post_data = array(
//    'username' => 'stclair2201',
//    'password' => 'handan'
//);
//send_post('http://sample.app/test/http', $post_data);
// 使用流过滤器string.toupper 将数据全部转为大写
$handle = fopen('data.txt', 'rb');
stream_filter_append($handle, 'string.toupper');
while(feof($handle) !== true) {
    echo fgets($handle);
}
fclose($handle);
// 使用php://filter附加流过滤器 string.toupper
$handle2 = fopen('php://filter/read=string.toupper/resource=data2.txt', 'rb');
while (feof($handle2) !== true) {
    echo fgets($handle2);
}
fclose($handle2);
// 使用DateTime类和流过滤器迭代bzip压缩的日志文件
$dateStart = new \DateTime();
/*
 * 创建一个持续30天的DatePeriod实例，一天一天反向向前推移
 */
$dateInterval = \DateInterval::createFromDateString('-1 day');
$datePeriod = new \DatePeriod($dateStart, $dateInterval,30);
foreach ($datePeriod as $date) {
    // 使用每次迭代DatePeriod实例得到的DateTime实例创建日志文件的文件名
    $file = 'sftp://USER:PASS@rsync.net' . $date->format('Y-m-d') . '.log.bz2';
    if (file_exists($file)) {
        /**
         * 使用SFTP流封装协议打开位于rsync.net上的日志文件流资源。把bzip2.decompress流过滤器附加到日志文件流资源上。
         * 实时解压缩bzip2格式的日志文件
         */
        $handle = fopen($file, 'rb');
        stream_filter_append($handle, 'bzip2.decompress'); //使用bzip2.decompress流过滤器可以在读取同时自动解压缩。
        // 使用PHP原生的问点系统函数迭代解压缩后的日志文件
        while (feof($handle) !== true) {
            $line = fgets($handle);
            //检查各行日志，判断是不会指定域名。如果是把这一行日志写入标准输出
            if (strpos($line, 'www.example.com') !== false) {
                fwrite(STDOUT, $line);
            }
        }
        fclose($handle);
    }
}
/**
 * 自定义DirtyWordsFilter流过滤器
 */
class DirtyWordsFilter extends php_user_filter
{
    /**
     * @params resource $in      流来的桶队列
     * @params resource $out     流走的桶队列
     * @params int  $consumed      处理的字节数
     * @params bool    $closing     是流中最后一个桶队列吗？
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        $words = array('grime', 'dirt', 'grease');
        $wordData = array();
        foreach ($words as $word) {
            $replacement = array_fill(0, mb_strlen($word), '*');
            $wordData[$word] = implode('', $replacement);
        }
        $bad = array_keys($wordData);
        $good = array_values($wordData);
        // 迭代流来的桶队列中的每个桶
        while ($bucket = stream_bucket_make_writeable($in)) {
            // 审查桶数据中的脏字
            $bucket->data = str_replace($bad, $good, $bucket->data);
            //
        }
    }
}