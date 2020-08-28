### laravel

#### 设计理念

运用了 AOP 和 IOC，提供容器来进行服务，一般与 composer 模块相互配合使用

#### 常用模块

laravel 在开发中一般会用到以下模块

##### 图片验证码

###### 安装

`mewbstudio/captcha`

`https://github.com/mewebstudio/captcha`

###### 使用

```php
$captcha = (CaptchaBuilder) $captchaBuilder->build();
// 解析
$code = $captcha->getPhrase();
// 得到 base64 格式字符串 img
$captcha->inline();
```

前端页面显示

```html
<img src={{ captcat_src('flat') }} onclick="this.src='/captcha/flat?'+Math.random()" title='点击刷新验证码'>
```

##### 文本过滤

###### 安装配置

```shell
composer require mews/purifier
# 发布配置
php artisan vendor:publish --provider="mews\Purifier\PurifierServiceProvider"
```

配置 `config/purifier.php`

```php
return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings'      => [
        'user_topic_body' => [
            'HTML.Doctype'             => 'XHTML 1.0 Transitional',
            'HTML.Allowed'             => 'div,b,strong,i,em,a[href|title],ul,ol,ol[start],li,p[style],br,span[style],img[width|height|alt|src],*[style|class],pre,hr,code,h2,h3,h4,h5,h6,blockquote,del,table,thead,tbody,tr,th,td',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,margin,width,height,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],
    ],
];
```

###### 使用

```php
// 源输入，过滤规则
clean($originInput, 'user_topic_body'); 
```

##### 测试友好输出

###### 安装配置

```shell
    composer require --dev codedungeon/phpunit-result-printer
```

*phpunit.xml* 配置

```xml
<?xml version="1.0" encoding="UTF-8"?>
  <phpunit printerClass="Codedungeon\PHPUnitPrettyResultPrinter\Printer">
       // ....
  </phpunit>
```

或者在命令行测试时添加参数

`phpunit --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer`

##### ide 辅助工具

###### 安装

```shell
composer require barryvdh/laravel-ide-helper
```

*config/app.php* 

```php
// 将以下内容添加到 providers 数组
Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class
```

###### 使用

1.生成对应文档 `php artisan ide-helper:generate`

2.在 composer.json 中添加

```json
{
    "scripts": {
        "post-update-cmd": [
            "Illuminate\\Foundation\\ComposerScripts::postUpdate",
            "php artisan ide-helper:generate",
            "php artisan ide-helper:meta",
            "php artisan optimize"
        ] 
    }
}
```

3. 生成配置

```shell script
php artisan vendor:publish --provider="Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider" --tag=config
// phpstorm 配置
php artisan ide-helper:meta
```

##### 图片处理

###### 安装配置

```
composer require "intervention/image
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5
```

###### 使用

```php
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

##### 页面调试工具

###### 安装配置

```php
composer require "barryvdh/laravel-debugbar:3.1" --dev
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider
```

修改 config/debugbar.php, 将 enabled 值设置为 `enabled => env('APP_DEBUG', false)`

##### 编辑器

1.下载 https://github.com/mycolorway/simditor/releases/download/v2.3.6/simditor-2.3.6.zip

​    文档: http://simditor.tower.im/docs/doc-usage.html

2.集成到项目中

​	1.新建文件夹 `resources/assets/editor/css`

​				`resources/assets/editor/js`

​	    将下载的 `simditor.css` 放置于 `resources/assets/editor/css` 文件夹,

​	    将 `hotkeys.js` , `module.js` , `simditor.js` , `uploader.js` ,放置于 `resources/assets/editor/js`

 2.  修改 Mix 配置信息,将编辑器的 CSS 和 JS 文件复制到 `public` 文件夹下,使用 Mix 的 `copyDirectory`

     方法.

     ```php
     mix.js('resources/assets/js/app.js', 'public/js')
        .sass('resources/assets/sass/app.scss', 'public/css')
        .copyDirectory('resources/assets/editor/js', 'public/js')
        .copyDirectory('resources/assets/editor/css', 'public/css')
        ;
     ```

   3.重启 `npm run watch-poll`

4. 主要布局文件中种下锚点 `styles` 和 `scripts`

   ```php+HTML
   <!-- Styles -->
       <link href="{{ asset('css/app.css') }}" rel="stylesheet">
       @yield('styles')
   </head>

   <body>
   .
   .
   .
       <!-- Scripts -->
       <script src="{{ asset('js/app.js') }}"></script>
       @yield('scripts')

   </body>
   </html>

   ```

5. 页面调用:

   ```html
   @section('styles')
       <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
   @stop

   @section('scripts')
       <script type="text/javascript"  src="{{ asset('js/module.js') }}"></script>
       <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
       <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
       <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>

       <script>
       $(document).ready(function(){
           var editor = new Simditor({
               textarea: $('#editor'),
           });
       });
       </script>

   @stop
   ```

   编辑器图片上传:

   JS 脚本调用

   ```js
   @section('scripts')
       <script type="text/javascript"  src="{{ asset('js/module.js') }}"></script>
       <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
       <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
       <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>

       <script>
       $(document).ready(function(){
           var editor = new Simditor({
               textarea: $('#editor'),
               upload: {
                   url: '{{ route('topics.upload_image') }}',
                   params: { _token: '{{ csrf_token() }}' },
                   fileKey: 'upload_file',
                   connectionCount: 3,
                   leaveConfirm: '文件上传中，关闭此页面将取消上传。'
               },
               pasteImage: true,
           });
       });
       </script>

   @stop
   ```

   参数含义:

   `pasteImage` -- 设定是否支持图片黏贴上传，这里我们使用 true 进行开启；

   `url`-- 处理上传图片的 URL

   `params` 表单提交的参数，Laravel 的 POST 请求必须带防止 CSRF 跨站请求伪造的 `_token` 参数；

   `fileKey`  是服务器端获取图片的键值，我们设置为 `upload_file`;

   `connectionCount` -- 最多只能同时上床 3 张图片

   `leaveConfirm` 上传过程中,用户关闭页面时的提醒

   响应参数

   ```json
   {
     "success": true/false,
     "msg": "error message", # optional
     "file_path": "[real file path]"
   }
   ```
   
##### HTTP client

###### 安装

```shell script
composer require "guzzlehttp/guzzle:~6.3
```

###### 使用

```php
   $client = new GuzzleHttp\Client();
   $res = $client->request('GET', 'https://api.github.com/user', [
       'auth' => ['user', 'pass']
   ]);
   echo $res->getStatusCode();
   // "200"
   echo $res->getHeader('content-type');
   // 'application/json; charset=utf8'
   echo $res->getBody();
   // {"type":"User"...'
   
   // Send an asynchronous request.
   $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
   $promise = $client->sendAsync($request)->then(function ($response) {
       echo 'I completed! ' . $response->getBody();
   });
   $promise->wait();
```