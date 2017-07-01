<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/1
 * Time: 22:16
 */

namespace experuse_interface\use_closures;


class App
{
    protected $routes = array();
    protected $responseStatus = '200 OK';
    protected $responseContentType = 'text/html';
    protected $responseBody = 'Hello world';

    public function addRoute($routePath,  $routeCallback) {
        $this->routes[$routePath] = $routeCallback->bindTo($this, __CLASS__);
    }

    public function dispatch($currentPath) {
        foreach ($this->routes as $routePath => $callback) {
            if ($routePath === $currentPath) {
                $callback();
            }
        }

        /**
         * void header (string $string [, bool $replace = true, [, int $http_response_code]])
         * header()用于发送原生的HTTP头。
         * params string 头字符串一种以"HTTP/"开头的（case is not significant),将会被用来计算出将要发送
         * 的HTTP状态码。另一种为"Location:"的头信息。它不仅把报文发送给浏览器，而且还奖返回给浏览器
         * 一个REDIRECT（302）的状态码，除非状态码已经事先被设置为了201或者3xx
         * 可选参数replace表明是否用后面的头替换前面相同类型的头。默认情况下会替换，如果传入FALSE，就可以
         * 强制使相同戴的头信息并存。header('www-authticate: NTLM', flasse)
         * http_response_code 强制指定http响应的值，这个参数只有在报文字符串string不为空的情况下才有效
         */
        header('HTTP/1.1 ' . $this->responseStatus);
        header('Content-type: ' . $this->responseContentType);
        header('Content-length ' . mb_strlen($this->responseBody)); // mb_strlen - 获取字符串的长度
        echo $this->responseBody;
    }
}

$app = new App();
$app->addRoute('/users/josh', function () {
    $this->responseContentType = 'application/json;charset=utf8';
    $this->responseBody = '{"name": "Josh"}';
});
$app->dispatch('/users/josh');