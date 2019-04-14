## LNMP环境搭建

### Ubuntu 下从软件仓库安装 

* 安装 `add-apt-repository` 

  `sudo apt install python-software-properties`

* 安装 `Nginx`

  ```shell
  # 获取公钥
  wget http://nginx.org/keys/nginx_signing.key
  # 导入公钥
  sudo apt-key add nginx_signing.key
  # 修改软件源
  vim /etc/apt/sources.list
  # 增加如下内容
  deb http://nginx.org/packages/ubuntu/  bionic nginx
  deb-src http://nginx.org/packages/ubuntu/ bionic nginx
  # 安装
  sudo apt update
  sudo apt install nginx
  ```

* 安装 MySQL

  ```shell
  # 安装软件源默认版本
  sudo apt install mysql-server mysql-common mysql-client
  # 安装 8.0 版本
  # 导入软件仓库
  sudo add-apt-repository ppa:lars-tangvald/mysql-8.0
  # 安装
  sudo apt install mysql-server mysql-common mysql-client
  ```

  备注：如果使用 MySQL 8.0，而 `php-mysqli` 和 `pdo-mysql` 并没有实现8.0 的连接协议（auth with caching_sha2_password)。此时要使用连接数据库，需要将 MySQL 配置为以 `mysql_native_password` 模式运行

  *修改MySQL端auth模块*

  ```shell
  # 编辑配置文件
  vim /etc/mysql/my.cnf
  # 增加如下内容
  [mysqld]
  default_authentication_plugin=mysql_native_password
  ```

  或者使用 SQL 修改用户连接

  ```mysql
  ALTER USER 'mysqlUsername'@'localhost' IDENTIFIED WITH mysql_native_password BY 'mysqlUsernamePassword'
  ```

* 安装 PHP

  ```shell
  # 添加 ppa 源获取当前支持版本
  sudo add-apt-repository ppa:ondrej/php
  # 安装
  sudo apt update
  sudo apt install php7.3-fpm php7.3-dev php7.3-mbstring php7.3-curl php7.3-pdo php7.3-pdo-mysql php7.3-mysqli
  ```

### 编译搭建

#### Nginx

* 加入仓库来自动构建依赖（见 apt 安装 nginx）导入公钥加入仓库

* 构建依赖

  ```shell
  sudo apt build-dep nginx
  ```

* 编译

  ```shell
  # 从官网 http://nginx.org 源码
  wget http://nginx.org/download/nginx-1.15.11.tar.gz
  # 解压
  tar -xf nginx-1.15.11.tar.gz
  # 执行 configure 获取配置帮助 ./configure --help
  ./configure --prefix=/usr/share/nginx
  # 编译，编译完成后生成的中间文件存在 objs 目录
  make
  # 安装，安装完成后生成的二进制运行文件在 `sbin` 目录下。配置文件在 `conf` 目录下
  make install
  ```

#### PHP

* 从官网下载源码

* 执行 `./configure

  **configure配置**

  configure: error: Cannot find OpenSSL's libraries

  解决方案：安装 openssl 及 opessl-dev 再次配置如果还报错

  sudo find / -name libssl.so` 并建立软连接到 `/usr/lib` 目录

  sudo ln -s /usr/lib/x86_64-linux-gnu/libssl.so /usr/lib

  `Please reinstall the libcurl distribution - easy.h should be in <curl-dir>/include/curl/`
  编译报错找不到 `curl` 

  解决方案为：安装 `libcurl4-openssl-dev` 并建立链接 `sudo ln -s  /usr/include/x86_64-linux-gnu/curl  /usr/include/curl`

* 编译

  `make`

* 安装

  `make install`

* 配置服务自启动

  ```shell
  chmod +x /etc/init.d/php-fpm
  update-rc.d php-fpm defaults
  ```

* 添加环境变量

  ```shell
  sudo vim  /etc/profile
  export PATH=/usr/local/php7.3/bin:$PATH
  export PATH=/usr/local/php7.3/sbin:$PATH
  ```

* 安装 pecl 扩展，以 `swoole` 为例

  ```shell
  # 下载 `swoole` 源码 
  wget https://github.com/swoole/swoole-src/archive/v2.2.0.tar.gz
  # 解压
  tar jxf v2.2.0.tar.gz
  # configure 配置 swoole configure 常用参数，`phpize` ， `php` , `php-config` ，三个文件版本要一致
  ./configure --with-php-config=/usr/local/php/5.6/bin/php-config --enable-swoole-debug --enable-sockets --enable-async-redis --enable-openssl --enable-http2 --enable-mysqlnd --with-openssl-dir=/usr/local/openssl098`
  # 安装
    ```
    cd swoole-src-swoole-2.2.0-stable/
    phpize
    ./configure
    sudo make
    sudo make install
    ```
  # 在 php.ini 中添加扩展 so
    extension=swoole.so
  ```

  __解决编译时opensll库不兼容__

  ```shell
  # 查看当前 `openssl` 版本
  openssl verions -a
  # 编译安装 php 版本对应 openssl `php7.0` 及以下支持0.9.8  `php7.1` 及以上支持1.0.1 
  wget https://www.openssl.org/source/old/0.9.x/openssl-0.9.8zh.tar.gz
  tar -xf openssl-0.9.8zh.tar.gz
  ./config --prefix=/usr/local/openssl098 shared zlib
  ln -s /usr/local/openssl098/bin/openssl /usr/bin/openssl
  # 要兼容新/旧版则不执行该指令
  ln -s /usr/local/openssl098/include/openssl /usr/include/openssl
  # 解决编译 `php-openssl` 时候找不到  `.h` 文件错误
  echo "/usr/local/openssl098/lib" >> /etc/ld.so.conf
  查看 ldconfig -v
  如果已安装有 openssl 则需要将 
  cp /usr/local/openssl098/lib/libcrypto.so.0.9.8 /usr/lib
  # 否则编译 php 会报错，编译安装 php 配置 php-openssl 模块参数
  # 重新执行 php 的 .configure 并指定 openssl 库
  ./configure --with-openssl=/usr/local/openssl098
  ```

  __解决编译 php 时，`easy.h should be in <--dir>includ/dir` 错误__

  ```shell
  # 在 /usr/include 创建符号连接
  cd /usr/include
  sudo ln -s x86_64-linux-gnu/curl
  ```

  









