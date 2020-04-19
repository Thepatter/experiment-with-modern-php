### 使用 vagrant 打造可移动开发环境

#### 安装配置

##### 快速开始

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

  

  

   
