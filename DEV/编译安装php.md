###编译安装php

####操作环境：

* ubuntu16.04
* php7.2.6 http://cn2.php.net/distributions/php-7.2.6.tar.bz2

#### 安装编译软件库

* `sudo apt-get install make`
* `sudo apt-get install autoconf`
* `sudo apt-get install openssl`
* `sudo apt-get install curl`
* `sudo apt-get install socket`
* `sudo apt-get install libmcrypt-dev`
* `sudo apt-get install libpng-dev `
* `sudo apt-get install libjpeg-dev `
* `sudo apt-get install libcurl4-gnutls-dev `
* `sudo apt-get install gcc`
* `sudo apt-get install cc`
* `sudo apt-get install libxml2`
* `sudo apt-get install libxml2-dev`
* `sudo apt-get install -y libssl-dev`

#### 编译配置参数

```
sudo ./configure  --prefix=/usr/local/php7.2 \
--with-curl \
--with-gd \
--with-iconv \
--with-zlib \
--with-mysqli=mysqlnd \
--with-pdo-mysql=mysqlnd \
--with-pear \
--with-openssl \
--enable-fpm \
--enable-bcmath \
--enable-libxml \
--enable-inline-optimization \
--enable-mbregex \
--enable-mbstring \
--enable-opcache \
--enable-pcntl \
--enable-shmop \
--enable-soap \
--enable-sockets \
--enable-sysvsem \
--enable-xml \
--enable-zip \
--enable-ctype \
--enable-maintainer-zts
```

**配置报错备注**

* configure: error: Cannot find OpenSSL's libraries

  1.解决方案：安装 openssl 及 opessl-dev 再次配置如果还报错

  2.`sudo find / -name libssl.so` 并建立软连接到 `/usr/lib` 目录

  `sudo ln -s /usr/lib/x86_64-linux-gnu/libssl.so /usr/lib`

#### 编译和安装

* 编译 `sudo make`

* 编译测试 `sudo make test`

  目前测试后会有两个 bug 

  `Bug #64267 (CURLOPT_INFILE doesn't allow reset) [ext/curl/tests/bug64267.phpt]`

* 安装 `sudo make install`

#### 安装后配置（以下操作需要 sudo)

`vim ~/.bash_aliases`

写入信息：

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

`make /run/php`

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

* 下载 swoole `wget https://github.com/swoole/swoole-src/archive/v2.2.0.tar.gz`

* 解压 `tar jxf v2.2.0.tar.gz` 

* 安装依赖库

  ```
  sudo apt-get install \
  build-essential \
  gcc \
  g++ \
  autoconf \
  libiconv-hook-dev \
  libmcrypt-dev \
  libxml2-dev \
  libmysqlclient-dev \
  libcurl4-openssl-dev \
  libjpeg8-dev \
  libpng12-dev \
  libfreetype6-dev \
  ```

* 安装 swoole

  ```
  cd swoole-src-swoole-2.2.0-stable/
  phpize
  ./configure
  sudo make
  sudo make install
  ```

  