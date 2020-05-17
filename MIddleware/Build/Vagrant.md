### Vagrant

#### box

提供虚拟机模板

#### Vagrantfile

是一个 ruby 脚本，只支持 1、2 两个版本，支持向后兼容

有以下配置命令空间：

* config.vm

  将修改 vagrant 管理的计算机配置

* config.ssh

* config.winrm

* config.winssh

* config.vagrant

###### vm

*config.vm.*

部分选项支持与否由提供者觉得

|             选项              |      类型      |                             含义                             |
| :---------------------------: | :------------: | :----------------------------------------------------------: |
|           base_mac            |     string     |           分配默认 NAT 接口的 MAC 地址（provider）           |
|         base_address          |     string     |           分配默认 NAT 接口的 IP 地址（provider）            |
|         boot_timeout          |    integer     |                   引导超时时间，默认 300s                    |
|              box              |     string     |                         配置机器 box                         |
|       box_check_update        |    boolean     |                为 true 时，每次启动将检查更新                |
|     box_download_checksum     |     string     | 下载 box_url 的校验和，如果未指定将不校验，如果指定不匹配则错误，还必须指定 box_download_checksum_type |
|  box_download_checksum_type   |     string     |      执行 hash 类型：md5、sha1、sha256、sha384、sha512       |
|   box_download_client_cert    |     string     |                  指定下载 box 时的证书路径                   |
|     box_download_ca_cert      |     string     | 直接下载 box 时使用的 CA 证书包路径，默认使用 Mozilla CA 证书 |
|     box_download_ca_path      |     string     |   下载 box 时的 CA　证书目录路径，默认使用 Mozilla CA 证书   |
|     box_download_options      |      map       |            指定下载 box 下载选项：{key: "value"}             |
|     box_download_insecure     |    boolean     | 为 true，则不验证来自服务器的 SSL 证书，默认如果 URL 是 HTTPS，则将验证 SSL 证书 |
| box_download_location_trusted |    boolean     | 为 true 时，所有 HTTP 重定向将被信任，默认不信任重定向 HTTP  |
|            box_url            |     string     |                 指定 box URL，支持 file 协议                 |
|          box_version          |     string     |                        使用 box 版本                         |
|         communicator          |     string     | 连接 box 客户端类型，默认 ssh，windows 客户端可指定为 winrm  |
|     graceful_halt_timeout     |    integer     |               关闭时等待正常停止时间，默认 60s               |
|             guest             | string、symbol | 将在此计算机上运行的 guest 操作系统，默认为 :linux，vagrant 见自动检测正确的发行版 |
|           hostname            |     string     |                  配置主机名，将在引导时设置                  |
|    ignore_box_vagrantfile     |    boolean     | true，则 vagrant 将不会加载在 vagrantfile box 中设置，默认 false |
|            network            |                |                       配置机器 network                       |
|        post_up_message        |     string     |                     启动完成后显示的信息                     |
|           provider            |                |                      声明 provider 配置                      |
|           provision           |                |                          预配置机器                          |
|         synced_folder         |                |                        配置同步文件夹                        |
|       usable_port_range       |                |             指定端口冲突时的范围，默认 2200~2250             |

###### vagrantfile

vagrantfile 是一个 ruby 脚本，用于配置如何构建虚拟机

```ruby
# -*- mode: ruby -*-

# vi: set ft=ruby :
Vagrant.configure("2") do |config|
config.vm.box = "ubuntu/xenial64"
config.vm.box_check_update = false
config.vm.define :php do |php|
  php.vm.hostname = "php"
  php.vm.network "private_network", ip: "192.168.10.100"
  php.vm.provider "virtualbox" do |v|
    # 设置主机与虚拟机的共享目录
    php.vm.synced_folder "C:/Users/home/code", "/home/vagrant/code"
    v.name = "php"
    v.memory = "4096"
    v.cpus = "6"
  end
end
config.vm.define :db do |db|
  db.vm.hostname = "db"
  db.vm.network :private_network, ip: "192.168.10.101"
  db.vm.provider "virtualbox" do |v|
      v.name = "db"
      v.memory = "4096"
      v.cpus = "6"
  end
end
config.vm.define :elasticnode1 do |nginx|
  nginx.vm.hostname = "elasticnode1"
  nginx.vm.network "private_network", ip: "192.168.10.103"
  nginx.vm.provider "virtualbox" do |v|
      v.name = "elasticnode1"
      v.memory = "4096"
      v.cpus = "6"
  end
end
config.vm.define :elasticnode2 do |nginx|
  nginx.vm.hostname = "elasticnode2"
  nginx.vm.network "private_network", ip: "192.168.10.104"
  nginx.vm.provider "elasticnode2" do |v|
      v.name = "elasticnode1"
      v.memory = "4096"
      v.cpus = "6"
  end
end
config.vm.define :elasticnode3 do |nginx|
  nginx.vm.hostname = "elasticnode3"
  nginx.vm.network "private_network", ip: "192.168.10.105"
  nginx.vm.provider "virtualbox" do |v|
      v.name = "elasticnode3"
      v.memory = "4096"
      v.cpus = "6"
  end
end
end
```

###### 启动

```shell
vagrant init ubuntu/bionic64
# 或直接在包含 vagrantfile 文件夹下运行，Windows 下 provider 为 hyperV 是需要管理员权限运行
vagrant up && vagrant ssh
```

###### 导出盒子

  ```shell
vagrant package --base <source_box_name> --output <target_box_name.box> --vagrantfile=/vagrantfile/path --include=/include/file
  ```

###### 导入盒子

  ```shell
# 在一个新文件夹下放入盒子文件执行 
vagrant init box_name.box
# 配置 vagrantfile 
vagrant up
# 使用账户 vagrant 默认密码 vagrant 登录
  ```

  

  

   
