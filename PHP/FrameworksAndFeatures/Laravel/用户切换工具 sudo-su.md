### 用户切换工具 sudo-su

安装 ：`composer require "viacreative/sudo-su:~1.1"`

配置：添加 provider:

```php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }
}
```

发布资源：`php artisan vendor:publish --provider="VIACreative\SudoSu\ServiceProvider"`

会生成：`/public/sudo-su` 前端 CSS 资源存放文件夹 `config/sudosu.php` 配置文件

修改配置文件：`config/sudosu.php`

```php
<?php

return [
    // 允许使用的顶级域名
    'allowed_tlds' => ['dev', 'local', 'test'];
    // 用户模型
    'user_model' => \App\Models\User::class
];
```

使用：在主布局模版中的 Scripts 区块上写入模版调用代码：

`resources/views/layouts/app.blade.php`

```php
@if (app()->isLocal())
	@include('sudosu::user-selector')
@endif

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
```

