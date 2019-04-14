Linux 下开发机配置

### 安装更新软件安装显卡驱动

* sudo apt update && sudo apt upgrade

* sudo ubuntu-drivers autoinstall

* Nvidia 显卡驱动安装连接 https://linuxconfig.org/how-to-install-the-nvidia-drivers-on-ubuntu-18-04-bionic-beaver-linux

### 科学上网

* sudo apt install python-pip

* sudo pip install shadowsocks

* 配置上网

- 浏览器上网需要在 设置--网络--网络代理--手动--Socks主机 127.0.0.1 1080
- 终端上网
  当前终端有效
  export http_proxy="socks5://127.0.0.1:1080"
  export https_proxy="socks5://127.0.0.1:1080"
  https://blog.fazero.me/2015/09/15/%E8%AE%A9%E7%BB%88%E7%AB%AF%E8%B5%B0%E4%BB%A3%E7%90%86%E7%9A%84%E5%87%A0%E7%A7%8D%E6%96%B9%E6%B3%95/

- 安装软件

    - chrome

      wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
      sudo dpkg -i *.deb

    - phpstorm

      下载 phpstorm.tar.gz 并解压缩

      进入解压目录允许 

      ./phpstorm.sh

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

### 更换中国源

* ubuntu 源位置

  ```shell
  cp /etc/apt/sources.list /etc/apt/sources.list.backup`
  vim /etc/api/sources.list
  ```

* 清华源

  ```text
  # 默认注释了源码镜像以提高 apt update 速度，如有需要可自行取消注释
  deb https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic main restricted universe multiverse
  # deb-src https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic main restricted universe multiverse
  deb https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-updates main restricted universe multiverse
  # deb-src https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-updates main restricted universe multiverse
  deb https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-backports main restricted universe multiverse
  # deb-src https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-backports main restricted universe multiverse
  deb https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-security main restricted universe multiverse
  # deb-src https://mirrors.tuna.tsinghua.edu.cn/ubuntu/ bionic-security main restricted universe multiverse
  ```

### UbuntuGUI美化

* https://zhuanlan.zhihu.com/p/36200924

* https://zhuanlan.zhihu.com/p/36265103

* https://zhuanlan.zhihu.com/p/36470249

