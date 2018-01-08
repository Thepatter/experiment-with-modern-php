## Laravel 常用开发工具包
### 测试友好输出工具包 

### `composer require --dev codedungeon/phpunit-result-printer`

#### 配置:在 phpunit.xml 配置 
```
  <?xml version="1.0" encoding="UTF-8"?>
     <phpunit printerClass="Codedungeon\PHPUnitPrettyResultPrinter\Printer">
       // ....
  </phpunit>
```
  或者在命令行运行测试的时候添加参数 `phpunit --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer`

文档地址: https://github.com/mikeerickson/phpunit-pretty-result-printer

  ### Ide 辅助工具代码自动补全及方法跳转 

  ### `composer require barryvdh/laravel-ide-helper`

  #### 配置:

1.在 `config/app.php` 添加以下内容到 providers 数组 

`Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class`

2.接下来运行以上命令生成代码对应文档: `php artisan ide-helper:generate`
3.在 `composer.json` 文件里添加
```config
"scripts":{
    "post-update-cmd": [
        "Illuminate\\Foundation\\ComposerScripts::postUpdate",
        "php artisan ide-helper:generate",
        "php artisan ide-helper:meta",
        "php artisan optimize"
    ]
},
```
4. `php artisan vendor:publish --provider="Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider" --tag=config`
5. phpStorm 配置 `php artisan ide-helper:meta`

#### 使用:

1. 生成默认 model 文件注释 `php artisan ide-helper:models` 默认 model 文件夹为 app/models
2. 文档地址 https://github.com/barryvdh/laravel-ide-helper

### 验证码包 

### `compser require mews/captcha`

页面显示使用 

`<img src={{ captcat_src('flat') }} onclick="this.src='/captcha/flat?'+Math.random()" title='点击图片重新获取验证码'>`

后端验证

```php
Validator::make($data, 
	'captcha' => 'required|captcha',
	[
      	'captcha.required' => '验证码不能为空', 'captcha.captcha' => '请输入正确的验证码',
	]
)
```

文档地址：https://packagist.org/packages/mews/captcha

接口使用 `composer require aishan/lumen-captcha`

配置: 必须启用缓存,验证码和验证码绑定的 `uuid`都是保存在缓存

​	  在 `bootstrap/app.php` 中注册 Captcha Service Provider:

```php
$app->register(Aishan\LumenCaptcha\CaptchaServiceProvider::class);
class_alias('Aishan\LumenCaptcha\Facades\Captcha','Captcha');
```

使用: get 请求 `{站点域名}/captchaInfo/{type?}`

其中`type`就是在配置文件中定义的验证码类型（如果你定义了的话），当然也可以不指定`type`，则默认为`default`，返回信息:

```json
{
  "code": "10000",
  "msg": "success",
  "sub_code": "",
  "sub_msg": "",
  "result": {
    "captchaUrl": "{站点域名}/captcha/default/fc1d7d7f-3d8c-652a-5e92-90e9822740ad",
    "captchaUuid": "fc1d7d7f-3d8c-652a-5e92-90e9822740ad"
  }
}
```

验证验证码

```php
$this->validate($request,[
            'captcha'=>'required|captcha:'.$captchaUuid
        ]);
```

文档地址: https://packagist.org/packages/aishan/lumen-captcha

### 图片处理包 

安装: `composer require "intervention/image"`

配置: `php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"`

使用: 

```
use Image;
public function reduceSize($file_path, $max_widht)
{
  	// 实例化,传参时文件的磁盘物理路径
  	$image = Image:make($file_path);
  	// 进行大小调整的操作
  	$image->resize($max_width, null, function($constraint) {
      	// 设定宽度是 $max_width, 高度等比例双方缩放
      	$constraint->aspectRatio();
      	// 防止裁图
  	})
}
```

文档地址：http://image.intervention.io/

### 页面调试工具包

安装: `composer require "barryvdh/laravel-debugbar:3.1" --dev`

配置: `php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"`

​	 `config/debugbar.php`,将 `enabled` 的值设置为 `enabled => env('APP_DEBUG', false)`

使用:会在网页底部显示状态信息

