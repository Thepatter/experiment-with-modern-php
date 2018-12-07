## APT 配置 LNMP 运行环境

### 操作系统

* ubuntu 18.04 `bionic`，采用 `apt` 安装的好处的依赖处理简单，配置自动化，直接开箱即用
* `sudo apt install python-software-properties`

### `apt` 安装 `nginx`

```shell
wget http://nginx.org/keys/nginx_signing.key
sudo apt-key add nginx_signing.key
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

###  `apt` 安装 `php`

```shell
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php7.2-fpm php-pdo-mysql php-mysqli
```

### 服务启动

```shell
sudo service nginx restart
```

