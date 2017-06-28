<?php
/**
 * Created by PhpStorm.
 * User: 76073
 * Date: 2017/6/28
 * Time: 23:03
 */

namespace experuse_interface\use_interface;

/**
 * Class HtmlDocument
 * @package experuse_interface\use_interface
 * 使用curl从远程URL获取HTML
 */
class HtmlDocument implements Documentable
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->url;
    }

    public function getContent()
    {
        // TODO: Implement getContent() method.
        $ch = curl_init();
        //
        /**
         * bool curl_setopt(resource $ch, int $option, mixed $value)    //设置cURL传输选项
         * params ch 由curl_init()返回的句柄
         * params option    需要设置的CURLOPT_XXX选项
         * params value     将设置在option选项上的值
        **/
        curl_setopt($ch, CURLOPT_URL, $this->url);         // 需要获取的URL地址,也可以在curl_init()初始化会话的时候
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // TRUE将curl_exec()获取的信息以字符串返回, 而不是直接输出
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);   // 在尝试连接等待的秒数, 设置为0, 则无限等待
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // True时将会根据服务器返回http头中的location重定向
        curl_setopt($ch, CURLOPT_MAXREDIRS,3); //指定最多的HTTP重定向次数,这个选项是和CURLOPT_FOLLOWLOCATION一起使用
        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }
}