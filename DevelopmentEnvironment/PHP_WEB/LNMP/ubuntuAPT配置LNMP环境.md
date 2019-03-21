## APT 配置 LNMP 运行环境

### 操作系统

* ubuntu 18.04 `bionic`，采用 `apt` 安装的好处的依赖处理简单，配置自动化，直接开箱即用
* `sudo apt install python-software-properties`

### `apt` 安装 `nginx`

```shell
wget http://nginx.org/keys/nginx_signing.key
sudo apt-key add nginx_signing.key
// 修改 /etc/apt/sources.list
deb http://nginx.org/packages/ubuntu/  bionic nginx
deb-src http://nginx.org/packages/ubuntu/ bionic nginx
sudo apt update
sudo apt install nginx
```

### `apt`  安装 `mysql`

* 安装 5.7 版本 `mysql`

```shell
sudo apt install mysql-server mysql-common mysql-client
```

* 安装 8.0 版本 `mysql`

```shell
sudo add-apt-repository ppa:lars-tangvald/mysql-8.0
sudo apt update
sudo apt install mysql-server mysql-common mysql-client
```

备注：如果使用 MySQL 8.0，而 `php-mysqli` 和 `pdo-mysql` 并没有实现8.0 的连接协议（auth with caching_sha2_password)。此时要使用连接数据库，需要将 MySQL 配置为以 `mysql_native_password` 模式运行

```cnf
[mysqld]
default_authentication_plugin=mysql_native_password
```

或者使用 SQL 修改用户连接

```mysql
ALTER USER 'mysqlUsername'@'localhost' IDENTIFIED WITH mysql_native_password BY 'mysqlUsernamePassword'
```

###  `apt` 安装 `php`

```shell
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php7.3-fpm php-pdo php-pdo-mysql php-mysqli php-mbstring 
```

### 服务启动

```shell
sudo service nginx restart
```

