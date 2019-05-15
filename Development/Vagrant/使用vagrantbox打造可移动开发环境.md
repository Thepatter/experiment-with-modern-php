### 使用 `vagrant box`打造可移动开发环境

#### 安装配置 `vagrant`, `virtualbox`

#### 初始化配置环境开发环境（基于 ubuntu 18.04.LTS)

* 下载 vagrant box

  `vagrant init ubuntu/bionic64`

  `vagrant up`

* 搭建LNMP环境

* 导出盒子

  ```shell
  vagrant package --base <source_box_name> --output <target_box_name.box> --vagrantfile=/vagrantfile/path --include=/include/file
  ```

* 导入盒子

  ```shell
  # 在一个新文件夹下放入盒子文件执行 
  vagrant init box_name.box
  # 配置 vagrantfile 
  vagrant up
  # 使用账户 vagrant 默认密码 vagrant 登录
  ```

  

  

   
