##composer
### 安装composer
####`curl -sS https://getcomposer.org/install | php` 这个命令使用curl下载composer的安装脚本，然后使用php
####执行安装脚本，最后在当前工作目录中创建composer.phar文件。composer.phar文件是composer的二进制文件
####`sudo mv composer.phar /usr/local/bin/composer`,`sudo chmod +x /usr/local/bin/composer`
####上述命令将composer变成全局可执行的二进制文件。
####把/usr/local/bin目录加入PATH环境变量中：在~/.bash_profile文件中添加`PATH=/usr/local/bin:$PATH`
### 安装PHP组件
####组件的语言版本：版本号由三个点(.)分数字组成(eg:1.13.2)。第一个数字是主版本号，如果更新破坏了向后兼容性，会提升
####主版本号。第二个数字是次版本号，如果小幅更新了功能，而且没有破坏向后兼容性，会提升次版本号。第三个数字是修订版本号
####如果组件修正了向后兼容戴的缺陷，会提升修订版本号
#### `composer require vendor/package` composer默认安装最新稳定的版本，执行命令后会创建composer.json和composer.lock文件
####这两个文件需要纳入版本管理系统
####composer FAQ 下载及使用composer时候需要代理翻墙，最好使用composer中文镜像
####全局配置`composer config -g repo.packagist composer https://packagist.laravel-china.org`
####仅限当前工程使用`composer config repo.packagist composer https://packagist.laravel-china.org`
####```[Composer\Downloader\TransportException]```
####```Content-Length mismatch```出现这种代码是被墙了
###composer.lock文件
####composer安装项目后，会创建一个composer.lock文件。这个文件会列出项目使用戴的所有PHP组件，以及组件的
####具体版本号(包括主版本号、次版本号、修订版本号)。锁定了项目，让项目只能使用具体版本的PHP组件
####如果有composer.lock文件，composer会下载这个文件中列出的具体版本，而不管packagist中可用的最新版本hi多少。将
####composer.lock文件纳入版本控制，不同环境使用相同版本的PHP组件，能降低由组件版本差异导致的兼容风险
####使用composer.lock文件时候，composer.install命令不会安装比其中列出版本号新的版本。如果需要下载新版组件并更新到
####composer.lock文件，要使用composer update命令。这个命令会更新把组件更新到最新稳定版，还会更新composer.lock文件
###自动加载PHP组件
####在需要自动加载的文件顶部使用`require 'vendor/autoload.php'`
###composer私有仓库
####composer可以管理放在需要认证戴的仓库中的私有PHP组件。执行composer install或composer update命令时，如果组件
####的仓库需要认证凭据，composer会提醒你。composer还需询问你是否把仓库的认证凭据保存在本地的auth.json文件(和composer.json
文件放在同一级目录)中。
```
{
    "http-basic": {
        "example.org": {
            "username": "your-username",
            "password": "your-password"
        }
    }
}
```
####可以使用`composer config http-basic.example.org your-username your-password`告诉composer远程设备认证凭证
####http-basic告诉composer。为指定的域名添加认证信息。example.org是主机名。用于识别存储私有组件仓库的远程设备。默认情况
####下，这个命令会在当前项目中的auth.json文件里保存凭据，也可以在在config后面添加--global表示，这样composer会在本地设备
####中的所有项目里使用这个凭据，全局凭证保存在`~/.composer/auth.json`文件中。window则在`%APPDATA%/Composer`文件夹中
```
{
    "name": "modernphp/scanner",
    "description": "Scan URLs from a scv file and report inaccessible URLs",
    "keywords": ["url", "scnner", "csv"],
    "homepage": "http://example.com",
    "license": "MIT"
    "authors": [
        {
            "name": "Josh Lockhart"
            "homepae": "https://github.com/codeguy"
            "role": "Developer"
        }
    ],
    "support": {
        "email": "help@example.com"
    },
    "require": {
        "php": ">=5.4.0",
        "guzzlehttp/guzzle": "~5.0",
    },
    "require-dev": {
        "phpunit/phpunit": "~4.3"
    },
    "suggest": {
        "league/csv": "~6.0"
    },
    "autoload": {
        "psr-4": {
            "Oreilly//ModernPHP\\": "src"
        }
    }
}
```
###composer.json文件构成
####name 组件的厂商名称和包名。在packagist中搜索
####description这个值是简要说明组件作用的
####keywords 描述组件的关键字，搜索组件关键字
####homepage 组件网站url
####license 软件许可证
####authors 作者信息
####support 组件用户获取技术支持戴的方式
#### require 组件自身依赖的 组件
#### require-dev 开发这个组件的依赖，生产环境不会安装
#### suggest 这个属性是建议安装的组件，composer不会安装
#### autoload 告诉composer如何加载这个组件