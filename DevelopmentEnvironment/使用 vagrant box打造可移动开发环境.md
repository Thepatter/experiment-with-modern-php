### 使用 `vagrant box`打造可移动开发环境

#### 安装配置 `vagrant`, `virtualbox`

#### 初始化配置环境开发环境（基于 ubuntu 18.04.LTS)

* 下载 vagrant box

  `vagrant init ubuntu/bionic64`

  `vagrant up`

* 替换`apt`软件源为阿里云

  `cp /etc/apt/sources.list  /etc/apt/sources.list.backup`

  `vim /etc/api/sources.list`

  ```conf
  deb http://mirrors.aliyun.com/ubuntu/ bionic main restricted universe multiverse
  deb http://mirrors.aliyun.com/ubuntu/ bionic-security main restricted universe multiverse
  deb http://mirrors.aliyun.com/ubuntu/ bionic-updates main restricted universe multiverse
  deb http://mirrors.aliyun.com/ubuntu/ bionic-proposed main restricted universe multiverse
  deb http://mirrors.aliyun.com/ubuntu/ bionic-backports main restricted universe multiverse
  deb-src http://mirrors.aliyun.com/ubuntu/ bionic main restricted universe multiverse
  deb-src http://mirrors.aliyun.com/ubuntu/ bionic-security main restricted universe multiverse
  deb-src http://mirrors.aliyun.com/ubuntu/ bionic-updates main restricted universe multiverse
  deb-src http://mirrors.aliyun.com/ubuntu/ bionic-proposed main restricted universe multiverse
  deb-src http://mirrors.aliyun.com/ubuntu/ bionic-backports main restricted universe multiverse
  ```

* 安装常用软件

  ```sudo apt install -y gcc g++ autoconf openssl curl git mysql-client mysql-common mysql-server rabbitmq oracle-java8-installer subversion subversion-tools nodejs-dev nodejs nginx nginx-common nginx-core libnginx-mod-rtmp
  sudo apt install -y gcc g++ autoconf openssl curl git mysql-client mysql-common mysql-server rabbitmq-server openjdk-8-jdk subversion subversion-tools nodejs-dev nodejs nginx nginx-common nginx-core libnginx-mod-rtmp libhiredis-dev libhiredis0.13 redis-server redis-tools libmemcached11 memcached mongodb mongodb-clients mongodb-dev mongodb-server mongo-tools influxdb
  ```

* 编译 `php`

* 导出盒子

  `vagrant package --base source_box_name --output target_box_name.box --vagrantfile=/vagrantfile/path  --include=/include/file`
  
* 导入盒子
  
  在一个新文件夹下放入盒子文件执行 `vagrant init box_name.box`, 配置 `vagrantfile` `vagrant up` 使用 `vagrant` 默认密码 `vagrant` 登录
  
 
   