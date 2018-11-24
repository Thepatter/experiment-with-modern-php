## 使用 WSL　进行　web　开发

### window 下各种开发环境优缺点

#### vagrant + homestead

优点：是`laravel` 官方推荐,纯 `linux` 环境，配置扩展简单， 缺点：不稳定，经常登录后找不到同步的文件夹，响应缓慢

#### 纯虚拟机

优点：完全在虚拟机下进行开发，缺点：是经常要切换到 `windows` 去不方便

#### 集成环境

优点：简单，快速 缺点：不兼容 `linux`,容易产生各种错误

### windows 子系统 Linux 进行 web 开发

**window on Linux 进行 web 开发能解决上述痛点，但无法支持服务自启动，需要手动启动，不过性能，速度，兼容非常好**

#### 安装

* 在 `windows` *启用或关闭windows功能* 里勾选 *适用于 Linux 的 windows 子系统* 
* 在 `windows` 商店里安装 `ubuntu`

#### 配置环境

* 切换国内源

* 更新及安装相关软件

  ```shell
  sudo apt update && sudo apt upgrade 
  sudo apt install nginx mysql-server mysql-common mysql-client mongodb-server redis-server php-fpm php-mysqli php-pdo-mysql php-curl php-mbstring
  php-gd
  ```

* 配置 `nginx` 及 `fpm` `mysql`

* 启动服务

### 备注

* `wsl` 虚拟机访问物理机磁盘为与 `/mnt/` 目录下

* 配置网页响应速度慢，修改 `nginx.conf` 的 `http` 配置

  *nginx.conf*

  ```nginx
  http {
      fastcgi_buffering off;
  }
  ```
### mysql 相关

* 设置 `ppa` 源 `sudo add-apt-repository ppa:lars-tangvald/mysql-8.0` 使用 `apt` 安装 `mysql8`

* 启动 MySQL 找不到主目录

  报错

  ```
  root@xxx:/etc/mysql# service mysql restart
   * Stopping MySQL Community Server 5.7.11
  ...
   * MySQL Community Server 5.7.11 is stopped
   * Re-starting MySQL Community Server 5.7.11
  No directory, logging in with HOME=/
  ..
   * MySQL Community Server 5.7.11 is started
  ```

  修复

  ```shell
  sudo service mysql stop
  sudo usermod -d /var/lib/mysql/ mysql
  sudo service mysql start
  ```
  
* `caching_sha2_password` 兼容问题

  1. 修改 `my.cnf` 使用原来的密码模块
  ```config
  [mysqld]
  default_authentication_plugin=mysql_native_password
  ```
  2. 更新用户使用的密码模块
  `ALTER USER 'username'@'ip_address' IDENTIFIED WITH mysql_native_password BY 'password';`
  

### 服务脚本

```shell
#!/bin/bash
sudo service mysql start
sudo service php7.2-fpm start
sudo service nginx start
sudo service mongodb start
sudo service redis-server start
sudo service elasticsearch start
sudo service kibana start
sudo umount /mnt/c
sudo umount /mnt/d
```

### 备注
最好分开搭建 `php nginx` 在一个 `Linux` 子系统上，`MySQL` 及其他存储在一个子系统，其他服务在一个子系统上，分布式，互不干扰




