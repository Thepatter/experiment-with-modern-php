## Ubuntu 开发环境配置

### 编译构建环境

* `ubuntu18.04`

### 编译安装 `nginx`

* 获取 `nginx` 公钥并加入仓库：

  ```shell
  wget http://nginx.org/keys/nginx_signing.key
  sudo apt-key add nginx_signing.key
  sudo apt update
  ```

* 自动构建依赖

  ```shell
  sudo apt build-dep nginx
  ```

* 下载源码

  官网地址:  http://nginx.org

  下载：`wget http://nginx.org/download/nginx-1.14.2.tar.gz`

  目录结构：

  `auto`  目录：编译相关

  `conf`  目录：配置示例

  `configure`   脚本：生成中间文件脚本

  `contribe`   目录：提供 `vim` 语法高亮支持插件等 `cp -r contrib/vim/* ~/.vim/`

  `html` 目录：提供欢迎及500html文件

  `man` 目录：帮助文档

  `src` 目录：源码目录

* `configure`

  执行脚本后，生成的中间文件存放在 `objs` 目录，`objs` 目录中的 `ngx_modules.c` 决定

* 编译

  `make`

  编译完成后生成的二进制文件及中间文件在 `objs` 目录里

* 安装

  安装完成后生成的二进制运行时文件在 `sbin` 目录下，配置文件在 `conf` 目录下

### 编译安装 PHP

**配置报错备注**

* configure: error: Cannot find OpenSSL's libraries

  1.解决方案：安装 openssl 及 opessl-dev 再次配置如果还报错

  2.`sudo find / -name libssl.so` 并建立软连接到 `/usr/lib` 目录

  `sudo ln -s /usr/lib/x86_64-linux-gnu/libssl.so /usr/lib`
* `Please reinstall the libcurl distribution - easy.h should be in <curl-dir>/include/curl/`
   编译报错找不到 `curl` 
   解决方案为：安装 `libcurl4-openssl-dev` 并建立链接 `sudo ln -s  /usr/include/x86_64-linux-gnu/curl  /usr/include/curl`

#### 编译和安装

* 编译 `sudo make`

* 编译测试 `sudo make test`

  目前测试后会有两个 bug 

  `Bug #64267 (CURLOPT_INFILE doesn't allow reset) [ext/curl/tests/bug64267.phpt]`

  如果编译出错:

  ```
  Generating phar.php
  /bin/bash: ext/phar/phar.php: Permission denied
  Makefile:329: recipe for target 'ext/phar/phar.php' failed
  make: *** [ext/phar/phar.php] Error 1
  ```

  解决：

  ```
  sudo vim /etc/ld.so.conf
  /usr/local/lib
  ```

  不过 `sudo make` 则未出现

* 安装 `sudo make install`

#### 安装后配置（以下操作需要 sudo)

设置快速访问别名：

`vim ~/.bash_aliases`
```
alias php='/usr/local/php/bin/php'
alias phpize='/usr/local/php/bin/phpize'
alias php-fpm='/usr/local/php/sbin/php-fpm'
```

刷新设置:

`source ~/.bash_aliases`

```
cp php.ini-development /usr/local/php7.2/lib/php.ini

cp /usr/local/php7.2/etc/php-fpm.conf.default /usr/local/php7.2/etc/php-fpm.conf
 
cp /usr/local/php7.2/etc/php-fpm.d/www.conf.default /usr/local/php7.2/etc/php-fpm.d/www.conf
 
cp ./sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm
 
chmod +x /etc/init.d/php-fpm
 
update-rc.d php-fpm defaults
```

修改 www.conf

`vim /usr/local/php/etc/php-fpm.d/www.conf`

```
user = www-data
group = www-data
 
listen = /run/php/php7.0-fpm.sock
 
listen.owner = www-data
listen.group = www-data
 
pm.max_requests = 5000
```

创建 php 运行时目录：

`mkdir /run/php`

修改`php.ini` 配置

启动 php-fpm

`sudo service php-fpm start`

添加环境变量

`sudo vim /etc/profile`

`export PATH=/usr/local/php7.2/bin:$PATH`

`export PATH=/usr/local/php7.2/sbin:$PATH`

备注：编译安装 php 的 cli 执行器和 `php.ini` 在 `/usr/local/php7.2/lib/` 文件夹下

composer 只能项目安装，并使用 `php composer.phar` 命令来执行

命令行辅助命令

```
php -h 获取帮助
 --rf <name>      Show information about function <name>.
 --rc <name>      Show information about class <name>.
 --re <name>      Show information about extension <name>.
 --rz <name>      Show information about Zend extension <name>.
 --ri <name>      Show configuration for extension <name>.
 --ini            Show configuration file names
```

#### pecl 扩展编译安装（以 swoole 为例子）

* 下载 `swoole` 源码 `wget https://github.com/swoole/swoole-src/archive/v2.2.0.tar.gz`

* 解压 `tar jxf v2.2.0.tar.gz` 

* `swoole` configure 常用参数
  
  `./configure --with-php-config=/usr/local/php/5.6/bin/php-config --enable-swoole-debug --enable-sockets --enable-async-redis --enable-openssl --enable-http2 --enable-mysqlnd --with-openssl-dir=/usr/local/openssl098`
* 安装 `swoole`

  ```
  cd swoole-src-swoole-2.2.0-stable/
  phpize
  ./configure
  sudo make
  sudo make install
  ```

* 修改 php.ini 扩展增加扩展 so

  `extension=swoole.so`

* 备注

  编译时候：`phpize` ， `php` , `php-config` ，三个文件版本要一致。

#### composer 安装

* 下载

  `wget https://getcomposer.org/download/1.6.5/composer.phar`

* 验证

  ```php
  php -r "if (hash_file('SHA256', 'composer.phar') === '67bebe9df9866a795078bb2cf21798d8b0214f2e0b2fd81f2e907a8ef0be3434') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer.phar'); } echo PHP_EOL;"
  ```

* 全局安装

  `sudo mv composer.phar /usr/local/bin/composer`

  `sudo chown vagrant:vagrant /usr/local/bin/composer`

  `sudo chmod u+x /usr/local/bin/composer`

* 配置中国镜像

  全局配置

  ```
  composer config -g repo.packagist composer https://packagist.phpcomposer.com
  ```

  项目配置

  ```
  "repositories": {
      "packagist": {
          "type": "composer",
          "url": "https://packagist.phpcomposer.com"
      }
  }
  ```
  

#### 解决编译安装 php 后，fpm.service 无法启动问题
  原因：没有删除第一次编译的文件夹，重新编译生成的文件，文件夹还是上次编译参数生成的，没有重新配置启动服务等
  解决：需要重新编译软件的时候千万要删除原来编译的文件夹，不然原来编译生成的`config, php-fpm`等文件还是上次编译参数生成的文件和编译参数

  无法启动服务类似 `fpm.service is maskd` 之类错误

  首先查看对应的 `fpm-service` 是否链接到 `/dev/null` `file /lib/systemd/system/php-fpm.service`

  及 `/etc/systemd/system` 目录文件夹下是否有该 `php-fpm.service -> /dev/null` 有的话先删除在操作

  如果返回 `/lib/systemd/system/php-fpm.service: symbolic link to /dev/null` 则删除它 `rm /lib/systemd/system/php-fpm.service`

  重置服务 `systemctl daemon-reload`

  解决 php 编译成功后无法启动 php-fpm 服务。

  进入编译目录的 `sapi` 目录下 `fpm` 目录拷贝 `init.d.php-fpm` 到 `/etc/init.d/php5.6-fpm` 并给与执行和设置自启动

  `cd php/sapi/fpm/` `cp init.d.php-fpm /etc/init.d/php5.6-fpm` `chmod +x /etc/init.d/php5.6-fpm` `update-rc.d php5.6-fpm defaults`

#### 解决编译`PHP5.6` 时 `openssl` 库版本不兼容 `7.0 及以上 1.0 版本，5.6 不超过 1.0`

* 查看当前 `openssl` 版本

  `openssl verions -a`

* 编译安装 `php` 版本对应 `openssl`
  `php7.0` 及以下支持0.9.8  `php7.1` 及以上支持1.0.1 

  `wget https://www.openssl.org/source/old/0.9.x/openssl-0.9.8zh.tar.gz`

  `tar -xf openssl-0.9.8zh.tar.gz`

  `./config --prefix=/usr/local/openssl098 shared zlib`

  `ln -s /usr/local/openssl098/bin/openssl /usr/bin/openssl` // 要兼容新/旧版则不执行该指令

  `ln -s /usr/local/openssl098/include/openssl /usr/include/openssl` // 解决编译 `php-openssl` 时候找不到  `.h` 文件错误

  `echo "/usr/local/openssl098/lib" >> /etc/ld.so.conf`

  查看 `ldconfig -v`

  如果已安装有 `openssl` 则需要将 `cp /usr/local/openssl098/lib/libcrypto.so.0.9.8 /usr/lib`, 否则编译 `php` 回报错

* 编译安装`php` 配置 `php-openssl` 模块参数

  `--with-openssl=/usr/local/openssl098`

#### 解决编译 `php` 时候 `easy.h should be in <--dir>/includ/dir` 错误

  在 `/usr/include` 创建符号连接

 	 `cd /usr/include`

 	 `sudo ln -s x86_64-linux-gnu/curl`

### apt 仓库安装

* 添加 `apt` 仓库源

  ```shell
  sudo add-apt-repository ppa:ondrej/php			// php 仓库源
  sudo add-apt-repository ppa:ondrej/php5-compat			// php5.5 仓库
  sudo apt update && sudo apt upgrade
  sudo apt install php-fpm php-common php-dev php-mbstring php-gd php-mysqli php-pdo-mysqli php-curl
  ```

* 如果没有 `add-apt-repository` 二进制文件

  `sudo apt install python-software-properties`