## Linux 下开发机配置

### 安装更新软件安装显卡驱动

* sudo apt update && sudo apt upgrade

* sudo ubuntu-drivers autoinstall

* Nvidia 显卡驱动安装连接 https://linuxconfig.org/how-to-install-the-nvidia-drivers-on-ubuntu-18-04-bionic-beaver-linux

### 科学上网

#### 安装 shadowsocks


* 安装

  ```shell
  # 安装 pip 
  sudo apt install  python-pip
  # 安装 shadowsocks
  sudo pip install shadowsocks
  ```

* 配置 `shadowsocks`

  */etc/shadowsocks.json*

  ```json
  {
  	"server": "ss-server-ip",
  	"server_port": "ss-server-port",
  	"local_address": "127.0.0.1".
  	"local_port": 1080,
  	"password": "ss-server-password",
  	"timeout": 300,
  	"method": "aes-256-cfb"
  }
  ```

* 运行

  `nohup sslocal -c /etc/shadowsocks.json > /dev/null 2>%1 &`

* 解决 `shadowssock-2.8.2` 不兼容高版本 `openssl` 无法启动问题

  根据启动 `sslocal` 错误日志来找到 `crypto/openssl.py` 所在文位置，替换该文件中的
  `libcrypto.EVP_CIPHER_CTX_cleanup.argtypes` 函数为 `libcrypto.EVP_CIPHER_CTX_reset`

* 开机启动

  ```shell
  sudo su
  echo "nohup sslocal -c /etc/shadowsocks.json /dev/null 2>&1 &" >> /etc/rc.local
  ```

* 浏览器上网需要在 设置--网络--网络代理--手动--Socks主机 127.0.0.1 1080

* 终端上网代理

  ```shell
   当前终端有效
    export http_proxy="socks5://127.0.0.1:1080"
    export https_proxy="socks5://127.0.0.1:1080"
  ```

    https://blog.fazero.me/2015/09/15/%E8%AE%A9%E7%BB%88%E7%AB%AF%E8%B5%B0%E4%BB%A3%E7%90%86%E7%9A%84%E5%87%A0%E7%A7%8D%E6%96%B9%E6%B3%95/

### bash

#### zsh 

* 安装 `oh-my-zsh`

  ```shell
  sudo apt-get install zsh
  sudo wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | sh
  chsh -s /bin/zsh
  ```

* 自动补全插件

  ```shell
  cd /home/<user>/.oh-my-zsh/plugins
  git clone https://github.com/zsh-users/zsh-autosuggestions.git
  vim .zshrc plugins=(zsh-autosuggestions)
  ```

* 语法纠错插件

  ```shell
  cd /home/<user>/.oh-my-zsh/plugins
  git clone https://github.com/zsh-users/zsh-syntax-highlighting.git
  # 语法纠错插件必须放在所有插件之后
  vim .zshrc plugins=([...other plugins zsh-autosuggestions])
  ```

* 自动补全插件

  ```shell
  cd ~/.oh-my-zsh/plugins/
  mkdir incr
  cd incr
  wget http://mimosa-pudica.net/src/incr-0.2.zsh
  source incr*.zsh
  vim ~/.zshrc 在末尾添加
  source ~/.oh-my-zsh/plugins/incr/incr*.zsh
  ```

#### fish

* 安装

  ```shell
  sudo apt install fish
  oh my fish
  curl -L https://get.oh-my.fish | fish
  cat /etc/shells
  chsh -s /usr/bin/fish
  ```

  `fish` 语法部分不兼容 `bash`
### `vim` 插件安装

#### 安装管理运行时 `vim-pathogen`

* 安装

  ```shell
  mkdir -p ~/.vim/autoload ~/.vim/bundle && \
  curl -LSso -/.vim/autoload/pathogen.vim https://tpo.pe/pathogen.vim
  ```
* 运行时路径操作

  ```shell
  vim ~/.vimrc
  execute pathogen#infect()
  syntax on
  filetype plugin indent on
  ```
* 安装 vim 插件

  ```shell
  cd ~/.vim/bundle && \
  git clone https://github.com/tpope/vim-sensible.git
  ```


### 更换中国源

* ubuntu 源位置

  ```shell
  cp /etc/apt/sources.list /etc/apt/sources.list.backup`
  vim /etc/api/sources.list
  ```


### 固定 ip

* Ubuntu 16.04 及以前使用 `ifupdown` 配置 

  ```shell
  # 配置文件
  vim /et	c/network/interfaces
  # 内容
  iface ens160 inet static
  address 210.72.92.25
  gateway 210.72.92.1
  netmask 255.255.255.222.0
  dns-nameservers 8.8.8.8
  # 重启
  sudo service network restart
  ```

* Ubuntu 18.04 使用 `netplan`

  ```shell
  # 配置文件
  vim /etc/netplan/50-cloud-init.yml
  # 内容
  network:
     	ethernets:
     	    ens33:
     		# 使用 dhcp 分配 dhcp: true
     	    addresses:
     	      - 192.168.1.106/24 # IP 与 主机号
     	    gateway4: 192.168.1.1
     	    nameservers:
     	      addresses:
     	        - 8.8.8.8
  version: 2
  # 应用
  sudo netplan apply
  ```
  
* Centos 7

  ```shell
  # 编辑配置文件
  sudo vim /etc/sysconfig/network-scripts/ifcfg-ens33
  TYPE=Ethernet                # 网卡类型：为以太网
  PROXY_METHOD=none            # 代理方式：关闭状态
  BROWSER_ONLY=no                # 只是浏览器：否
  BOOTPROTO=dhcp                # 网卡的引导协议：DHCP[中文名称: 动态主机配置协议]
  DEFROUTE=yes                # 默认路由：是, 不明白的可以百度关键词 `默认路由` 
  IPV4_FAILURE_FATAL=no        # 是不开启IPV4致命错误检测：否
  IPV6INIT=yes                # IPV6是否自动初始化: 是[不会有任何影响, 现在还没用到IPV6]
  IPV6_AUTOCONF=yes            # IPV6是否自动配置：是[不会有任何影响, 现在还没用到IPV6]
  IPV6_DEFROUTE=yes            # IPV6是否可以为默认路由：是[不会有任何影响, 现在还没用到IPV6]
  IPV6_FAILURE_FATAL=no        # 是不开启IPV6致命错误检测：否
  IPV6_ADDR_GEN_MODE=stable-privacy            # IPV6地址生成模型：stable-privacy [这只一种生成IPV6的策略]
  NAME=ens33                    # 网卡物理设备名称
  UUID=f47bde51-fa78-4f79-b68f-d5dd90cfc698    # 通用唯一识别码, 每一个网卡都会有, 不能重复, 否两台linux只有一台网卡可用
  DEVICE=ens33                    # 网卡设备名称, 必须和 `NAME` 值一样
  ONBOOT=no                        # 是否开机启动， 要想网卡开机就启动或通过 `systemctl restart network`控制网卡,必须设置为 `yes` 
  # 修改
  BOOTPROTO=static
  ONBOOT=yes
  IPADDR=192.168.1.111
  NETMASK=255.255.255.0
  GATEWAY=192.168.1.1
  DNS1=192.168.0.1
  # 重启
  sudo systemctl restart network
  ```
  
* 获取网关

  可以使用 `route -n`、`traceroute`、`ip route show` 命令获取

### UbuntuGUI美化

* https://zhuanlan.zhihu.com/p/36200924

* https://zhuanlan.zhihu.com/p/36265103

* https://zhuanlan.zhihu.com/p/36470249

