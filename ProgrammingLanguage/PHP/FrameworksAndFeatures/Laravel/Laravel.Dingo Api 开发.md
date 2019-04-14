## DingoAPI

#### 安装

手动添加 `"dingo/api": "2.0.0-alpha1"` 到 composer.json 的 `require` 部分,执行 `composer update`

#### 配置

* 发布配置文件 `php artisan vendor:publish`

* api.config 文件配置
```ini
API_STANDARDS_TREE 有三个值可选,x: 本地开发版本, prs: 未对外发布版本,提供给公司, vnd: 对外发布,开发给所有用户
API_SUBTYPE 项目简称
API_PREFIX api 前缀 www.laravel.com/api
API_DOMAIN api 子域名
API_VERSION 默认 API 版本,当没传 Accept 头的时候,默认访问该版本的 API
API_STRICT 是否开启严格模式,如果开启,则必须使用 Accept 头才可以访问
```

#### 使用

`routes/api.php` 路由文件编写规则

```php
<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {
    // 中间件 throttle 调用频率中间件
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => 1,
        'expires' => 1,
    ], function ($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
    });
});
```
#### dingo 基类 controller
`php artisan make:controller Api/Controller`
```php
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    use Helpers;
}
```

#### dingo API 表单请求验证基类
```php
<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
```