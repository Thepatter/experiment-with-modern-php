                                                                                                                                                                                                                                                                                                                      ____## Laravel 常用开发工具包
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

文档: https://github.com/barryvdh/laravel-debugbar

### Bootstrap 框架导航栏组件

安装: `compser require "hieu-le/active:~3.5"`

使用:

```html
<div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li class="{{ active_class(if_route('topics.index')) }}"><a href="{{ route('topics.index') }}">话题</a></li>
                <li class="{{ active_class((if_route('categories.show') && if_route_param('category', 1))) }}"><a href="{{ route('categories.show', 1) }}">分享</a></li>
                <li class="{{ active_class((if_route('categories.show') && if_route_param('category', 2))) }}"><a href="{{ route('categories.show', 2) }}">教程</a></li>
                <li class="{{ active_class((if_route('categories.show') && if_route_param('category', 3))) }}"><a href="{{ route('categories.show', 3) }}">问答</a></li>
                <li class="{{ active_class((if_route('categories.show') && if_route_param('category', 4))) }}"><a href="{{ route('categories.show', 4) }}">公告</a></li>
            </ul>
```

```php
/**
 * Get the active class if the condition is not falsy
 *
 * @param        $condition
 * @param string $activeClass
 * @param string $inactiveClass
 *
 * @return string
 */
function active_class($condition, $activeClass = 'active', $inactiveClass = '')
```

如果传参满足指定条件 (`$condition`) ，此函数将返回 `$activeClass`，否则返回 `$inactiveClass`。

此扩展包提供了一批函数让我们更方便的进行 `$condition` 判断：

1. if_route() - 判断当前对应的路由是否是指定的路由；
2. if_route_param() - 判断当前的 url 有无指定的路由参数。
3. if_query() - 判断指定的 GET 变量是否符合设置的值；
4. if_uri() - 判断当前的 url 是否满足指定的 url；
5. if_route_pattern() - 判断当前的路由是否包含指定的字符；
6. if_uri_pattern() - 判断当前的 url 是否含有指定的字符；

文档: https://getbootstrap.com/docs/3.3/components/#navbar

### 编辑器 Simditor

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

   ### HTMLPurifier for Laravel 5 html 客户端输入过滤包

   1.安装 `composer require "mews/purifier:~2.0"`

   2.配置 `php artisan vendor:publish --provider="Mews\Purifier\PurifierServiceProvider"`

   ```php
   <?php

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

   配置里的 `user_topic_body` 是为话题内容定制,配合 `clean()` 方法使用

   `$topic->body = clean($topic->body, 'user_topic_body')`

   3. 使用在数据入库前进行过滤

      ```php
      <?php
      namespace App\Observers;
      use App\Models\Topic;
      class TopicObserver
      {
        	public function saving(Topic $topic)
        	{
            	$topic->body = clean($topic->body, 'user_topic_body');
            	$topic->excerpt = make_excerpt($topic->body);
        	}
      }
      ```

### PHP HTTP 请求套件

1. 安装 `composer require "guzzlehttp/guzzle:~6.3"`

2. 文档 http://docs.guzzlephp.org/en/stable/

3. 使用 `use GuzzleHttp\Client`

4. DEMO

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

   ​

5. 请求百度翻译接口

```PHP
// 实例化 HTTP 客户端
$http = new Client;
$api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';
// 生成 URL-encode 之后的请求字符串
$query = http_build_query([
            "q"     =>  $text,
            "from"  => "zh",
            "to"    => "en",
            "appid" => $appid,
            "salt"  => $salt,
            "sign"  => $sign,
        ]);
// 发送 HTTP Get 请求
$response = $http->get($api.$query);
```

### redis 队列视图话工具 horizon

1.安装 `composer require "laravel/horizon:~1.0"`

2.配置 `php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"`

3.浏览器访问 http://larabbs.one.test/horizon/dashboard

4.horzion 是一个监控程序,需要常驻运行, 启动 `php artisan horizon`

### laravel migration 修改数据表字段的属性
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('phone')->nullable()->unique()->after('name');
    $table->string('email')->nullable()->change();
});
```
`composer require doctrine/dbal`