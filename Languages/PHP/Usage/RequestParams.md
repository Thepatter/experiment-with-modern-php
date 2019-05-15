## 获取请求

### 获取客户端请求参数

```php
<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 2018/10/12
 * Time: 15:01
 */

class RequestParams
{
    public $requestParams;

    public function __construct()
    {
        $this->requestParams = $this->{strtolower($_SERVER['REQUEST_METHOD']) . 'Params'}();
        if (isset($_SERVER['HTTP_CONTENT_TYPE']) && isset($_SERVER['HTTP_CONTENT_LENGTH'])) {
            $this->requestParams = $this->{explode('/', $_SERVER['HTTP_CONTENT_TYPE'])};
        }
    }

    private function getParams() :array
    {
         return array_map(function ($value) {
             return htmlspecialchars(str_replace(' ', '+', $value));
         }, $_GET);
    }

    private function postParams() :array
    {
        return array_map(function ($value) {
            return htmlspecialchars($value);
        }, $_POST);
    }

    private function restParams() :array
    {
        $input = (file_get_contents('php://input'));
        if ($_SERVER['HTTP_CONTENT_TYPE'] === 'application/x-www-form-urlencoded') {
            $inputParamsArray = explode('&', $input);
            $requestParams = [];
            foreach ($inputParamsArray as $inputParam) {
                $tmpInputKeyAndValue = explode('=', $inputParam);
                $requestParams[$tmpInputKeyAndValue[0]] = htmlspecialchars($tmpInputKeyAndValue[1]);
            }
            return $requestParams;
        }
        $keyParams = explode(' ', $input);
        $requestParams = [];
        foreach ($keyParams as $keyParam) {
            if (preg_match('/^name/', $keyParam)) {
                $key = '';
                $keyParamArray = array_filter(explode('?', preg_replace('/[\f\n\r\t\v ]/', '?', $keyParam)));
                foreach ($keyParamArray as $item) {
                    if (preg_match('/^\-+/', $item) || $item === 'Content-Disposition:') {
                        continue;
                    }
                    if (preg_match('/^name=/', $item)) {
                        $key = trim(explode('=', $item)[1], '"');
                    }
                    $requestParams[$key] = htmlspecialchars($item);
                }
            }
        }
        return $requestParams;
    }

    private function deleteParams() :array
    {
        return $this->restParams();
    }

    private function putParams()
    {
        return $this->restParams();
    }

    private function patchParams()
    {
        return $this->restParams();
    }

    private function __call($name, $arguments) :array
    {
        if ($name === 'xml') {
            return json_decode(json_encode(file_get_contents('php://input')), true);
        }
        if ($name === 'json') {
            return json_decode(file_get_contents('php://input'), true);
        }

    }
}
```