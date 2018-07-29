Linux 下开发机配置

安装更新软件安装显卡驱动

sudo apt update && sudo apt upgrade

sudo ubuntu-drivers autoinstall

Nvidia 显卡安装连接 https://linuxconfig.org/how-to-install-the-nvidia-drivers-on-ubuntu-18-04-bionic-beaver-linux

科学上网

sudo apt install python-pip

sudo pip install shadowsocks

配置上网

- 浏览器上网需要在 设置--网络--网络代理--手动--Socks主机 127.0.0.1 1080
- 终端上网
  当前终端有效
  export http_proxy="socks5://127.0.0.1:1080"
  export https_proxy="socks5://127.0.0.1:1080"
  https://blog.fazero.me/2015/09/15/%E8%AE%A9%E7%BB%88%E7%AB%AF%E8%B5%B0%E4%BB%A3%E7%90%86%E7%9A%84%E5%87%A0%E7%A7%8D%E6%96%B9%E6%B3%95/

安装软件

- chrome

    wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
    sudo dpkg -i *.deb

- phpstorm

    # 下载 phpstorm.tar.gz 并解压缩
    # 进入解压目录允许 
    ./phpstorm.sh

- zsh oh_my_zsh

    sudo apt-get install zsh
    sudo wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | sh
    chsh -s /bin/zsh

- fish

    sudo apt install fish
    # oh my fish
    curl -L https://get.oh-my.fish | fish
    cat /etc/shells
    chsh -s /usr/bin/fish

美化

https://zhuanlan.zhihu.com/p/36200924

https://zhuanlan.zhihu.com/p/36265103

https://zhuanlan.zhihu.com/p/36470249

#### 更换中国源
`cp /etc/apt/sources.list /etc/apt/sources.list.backup`
`vim /etc/api/sources.list`
阿里云源
```
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
