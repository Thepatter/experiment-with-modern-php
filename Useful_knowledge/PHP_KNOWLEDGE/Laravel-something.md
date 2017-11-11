

##Homestead 相关

###laravel 官方Homestead环境安装
1.下载和导入 Homestead Box 虚拟机盒子
2.安装 Git, 为下载 Homestead 管理脚本做准备
3.使用 Git，下载 Homestead 管理脚本

####1 下载和导入 Homestead Box

下载 url http://download.fsdhub.com/lt-homestead-3-0-0-2017082400.zip

下载后的文件为 `lt-homestead-3-0-0-2017082400.zip` ，解压后进入文件夹，运行 `vagrant box add metadata.json` 导入box（需解压到非中文路径）。

#### 2.安装 Git

Mac 下，通过安装 Xcode 命令来安装 Git `xcode-select --install`

Windows  下，安装 git bash

####3. 下载 Homestead 管理脚本

国内定制化 Homestead 脚本（Composr 加速，配置了 Composer 中国全量镜像，集成 heroku，集成Yarn，为 Yarn 加了淘宝镜像加速，使用 CNPM 对 NPM 加速，移除了每一次 provision 时 composer self-update）

`git clone https://git.coding.net/summerblue/homestead.git Homestead`

检出需要的 Homestead 版本 `git checkout v5.4.0`  并初始化 `bash init.sh` 后，生成 Homestead.yaml 文件为 Homestead 虚拟机配置文件（虚拟机设置，SSH 密钥登陆设置，共享文件夹，站点，数据库，自定义变量，**每次修改该文件后需要运行 `vagant provision && vagrant reload`）、after.sh（每一次 Homestead 盒子重置后 `vagrant provision` 会调用的 shell 脚本文件）、aliases（每一次 Homestead 盒子重置后`vagrant provison`，会被替换至虚拟机的`~/.bash_aliases` 文件中，aliases 放一些快捷命令)

### 启动和运行 vagrant

在 Homestead 脚本文件夹里 `vagrant up && vagrant ssh` 推出 `exit` 关闭 Homestead `vagrant halt`

### Homestead box 版本更新

删除旧版本相关文件夹，从新导入盒子(删除文件夹前需删除相关 homestead 版本`vagrant box remove laravel/homestead --box-version "0.6.0"`，在旧版本文件夹里运行 `vagrant destroy` 删除 homestead

或者去 virtualBox 里删除虚拟机。homestead 只能存在一个，后面导入的 homestead 会覆盖前面的 homestead 配置。

