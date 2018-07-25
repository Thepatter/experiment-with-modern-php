###Ubuntu 开发环境配置

####操作环境：

* ubuntu16.04
* php7.2.6 http://cn2.php.net/distributions/php-7.2.6.tar.bz2

#### 安装编译软件库

```
  sudo apt-get install -y \
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
  libfreetype6-dev \
  openssl \
  curl \
  libpng12-dev \
  libjpeg-dev \
  libcurl4-gnutls-dev \
  libxml2 \
  libssl-dev \
```

#### 编译配置参数详解：

```
--prefix=/usr/local/php                      //指定 php 安装目录 
--with-apxs2=/usr/local/apache/bin/apxs      //整合apache，
                        //apxs功能是使用mod_so中的LoadModule指令，
                       //加载指定模块到 apache，要求 apache 要打开SO模块
--with-config-file-path=/usr/local/php/etc    //指定php.ini位置
--with-MySQL=/usr/local/mysql                 //mysql安装目录，对mysql的支持
--with-mysqli=/usr/local/mysql/bin/mysql_config
                      //mysqli扩展技术不仅可以调用MySQL的存储过程、处理MySQL事务，
                      //而且还可以使访问数据库工作变得更加稳定。
--enable-safe-mode    //打开安全模式 
--enable-ftp          //打开ftp的支持 
--enable-zip          //打开对zip的支持 

--with-bz2            //打开对bz2文件的支持 
--with-jpeg-dir       //打开对jpeg图片的支持 
--with-png-dir        //打开对png图片的支持 
--with-freetype-dir   //打开对freetype字体库的支持 
--without-iconv       //关闭iconv函数，各种字符集间的转换 
--with-libXML-dir     //打开libxml2库的支持 
--with-XMLrpc         //打开xml-rpc的c语言 
--with-zlib-dir       //打开zlib库的支持 
--with-gd             //打开gd库的支持 
--enable-gd-native-ttf //支持TrueType字符串函数库 
--with-curl            //打开curl浏览工具的支持 
--with-curlwrappers    //运用curl工具打开url流 
--with-ttf             //打开freetype1.*的支持，可以不加了 
--with-xsl             //打开XSLT 文件支持，扩展了libXML2库 ，需要libxslt软件 
--with-gettext         //打开gnu 的gettext 支持，编码库用到 
--with-pear            //打开pear命令的支持，PHP扩展用的 
--enable-calendar      //打开日历扩展功能 
--enable-mbstring      //多字节，字符串的支持 
--enable-bcmath        //打开图片大小调整,用到zabbix监控的时候用到了这个模块
--enable-sockets       //打开 sockets 支持
--enable-exif          //图片的元数据支持 
--enable-magic-quotes  //魔术引用的支持 
--disable-rpath        //关闭额外的运行库文件 
--disable-debug        //关闭调试模式 
--with-mime-magic=/usr/share/file/magic.mime  //魔术头文件位置
```

* CGI 安装参数

  ```
  --enable-fpm                 //打上PHP-fpm 补丁后才有这个参数，CGI方式安装的启动程序
  --enable-fastCGI             //支持fastcgi方式启动PHP
  --enable-force-CGI-redirect  //重定向方式启动PHP
  --with-ncurses         //支持ncurses 屏幕绘制以及基于文本终端的图形互动功能的动态库
  --enable-pcntl         // 多进程支持
  --with-mcrypt          //mcrypt算法的扩展
  --with-mhash           //mhash算法的扩展
  
  //以上函数库需要安装
  
  --with-gmp                     //应该是支持一种规范
  --enable-inline-optimization   //优化线程
  --with-openssl                 //openssl的支持，加密传输时用到的
  --enable-dbase                 //建立DBA 作为共享模块
  --with-pcre-dir=/usr/local/bin/pcre-config       //perl的正则库案安装位置
  --disable-dmalloc
  --with-gdbm            //dba的gdbm支持
  --enable-sigchild
  --enable-sysvsem
  --enable-sysvshm
  --enable-zend-multibyte  //支持zend的多字节
  --enable-mbregex
  --enable-wddx
  --enable-shmop
  --enable-soap
  ```

  * 5.6 常用参数

    ```
    ./configure --prefix=/usr/local/php/5.6 \
    --with-config-file-path=/etc/php/5.6 \
    --enable-fpm \
    --enable-pcntl \
    --enable-bcmath \
    --enable-mysqlnd \
    --enable-opcache \
    --enable-sockets \
    --enable-sysvmsg \
    --enable-sysvsem \
    --enable-sysvshm \
    --enable-shmop \
    --enable-zip \
    --enable-soap \
    --enable-xml \
    --enable-mbstring \
    --disable-rpath \
    --disable-debug \
    --disable-fileinfo \
    --with-mysql=mysqlnd \
    --with-mysqli=mysqlnd \
    --with-pdo-mysql=mysqlnd \
    --with-pcre-regex \
    --with-iconv \
    --with-zlib \
    --with-mcrypt \
    --with-gd \
    --with-openssl \
    --with-mhash \
    --with-xmlrpc \
    --with-curl \
    --with-imap-ssl
    ```

    

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

* 修改 php.ini 扩展增加扩展 so

  `extension=swoole.so`

* 备注

  编译时候：`phpize` ， `php` , `php-config` ，三个文件版本要一致。并软连接到 `/usr/bin/` 目录

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

  
