###PHP 生产环境的搭建
####升级软件
ubuntu
`apt-get update`
`apt-get upgrade`
Centos
`yum update`
####非根用户
ubuntu 中创建非根用户
`adduser username`
把 username 加入 root 用户组 `usermod -a -G sudo username` 让 username 拥有 sudo 权限
centos `adduser username` 设置 username 的密码 `passwd username` 加入 wheel 用户组 `usermod -a -G wheel deploy`, 让 username
拥有 sudo 权限
#### SSH 密钥对认证
在本地设置 `ssh-keygen` 该命令会在本地创建两个文件 `~/.ssh/id_rsa.pub` 公钥和 `~/.ssh/id_rsa` 私钥,复制公钥到服务器
`scp ~/.ssh/id_rsa.pub username@ip:/path`
服务器设置 `mkdir ~/.ssh` 创建 .ssh 文件夹, 创建 authorized_keys 文件 `touch ~/.ssh/authorized_keys`, 修改权限只让 `username` 用
户访问 `~/.ssh` 目录和`~/.ssh/authorized_keys` 文件 `chown -R username:username ~/.ssh` `chmod 700 ~/.ssh` 
`chmod 600 ~/.ssh/authorized_keys`
####禁用密码,禁止根用户登录
登录服务器后,修改 SSH 服务器配置文件 `/etc/ssh/sshd_config` 文件, 将 PasswordAuthentication 设置为 no; 将 PermitRootLogin 设置为no
重启 SSH 服务器 `sudo service ssh restart` centos `sudo systemctl restart sshd.service`
###PHP-FPM PHP FastCGI Process Manager
PHP-FPM 用于管理 PHP 进程池,用户接收和处理来自 Web 服务器的请求, PHP-FPM 软件会创建一个主进程,通常以操作系统中根用户的身份运行,控制何时以及如何把
HTTP 请求转发给一个或多个子进程处理,  PHP-FPM 主进程还控制着什么时候创建 (处理 Web 应用更多流量) 和销毁 (子进程运行时间太久或不再需要了) PHP 
子进程, PHP-FPM 进程池中的每个进程存在的时间都比单个 HTTP 请求长.
####安装
ubuntu `sudo apt-get install python-software-properties`, `sudo add-apt-repository ppa:ondrej/php5-5.6`, `sudo apt-get update`
`sudo apt-get install php5-fpm php5-cli php5-curl php5-gd php5-json php5-mcrypt php5-mysqlnd`
####全局配置
ubuntu 中, PHP-FPM 的主配置文件是 `/etc/php5/fpm/php-fpm.conf` centos 中, PHP-FPM 的主配置文件是 `etc/php-fpm.conf`
以下设置的作用是,如果在指定的一段时间内有指定个子进程失效了,让 PHP-FPM 主进程重启.这是 PHP-FPM 进程的基本安全保障,能解决简单问题.
`emergency_restart_threshold = 10` 在指定的一段时间内,如果失效的  PHP-FPM 子进程数超过这个值,PHP-FPM 主进程就优雅重启
`emergency_restart_interval = 1m` 设定 emergency_restart_threshold 设置采用的时间跨度
####配置进程池
PHP-FPM 配置文件其余内容是一个名为 Pool Definitions 的区域.这个区域里的配置用户设置每个 PHP-FPM 进程池. PHP-FPM 进程池中是一系列相关的 
PHP 子进程.通常一个 PHP 应用有自己的一个 PHP-FPM 进程池
在 ubuntu 中, Pool Definitions 区域只有这一行内容
`include=/etc/php5/fpm/pool.d/*.conf`
在 centos 中,则在 PHP-FPM 主配置文件的顶部使用下面这行代码引入进程池定义文件
`include=/etc/php-fpm.d/*.conf`
这行代码的作用是让 PHP-FPM 加载 /etc/php5/fpm/pool.d/目录 (ubuntu) 或 /etc/php-fpm.d/目录 (centos) 中的各个进程池定义文件,进入这个目录
会看到一个名为 www.conf 的文件,这是名为 www 的默认 PHP-FPM 进程池的配置文件.每个 PHP-FPM 进程池的配置文件开头都是 \[ 符号,后跟进程池的名称,
然后是 ] 符号.各个 PHP-FPM 进程池都以指定的操作系统用户和用户组的身份运行.配置默认的 www PHP-FPM 进程池的配置.
`user = username` 拥有这个 PHP-FPM 进程池中子进程的系统用户,把这个设置的值设为运行 PHP 应用的非根用户的用户名.
`group = usergroup` 拥有这个 PHP-FPM 进程池中子进程的系统用户组.把这个设置的值设为运行 PHP 应用的非根用户所属的用户组名
`listen = 127.0.0.1:9000` PHP-FPM 进程池监听的 IP 地址和端口号,让 PHP-FPM 只接受 nginx 从这里传入的请求, 127.0.0.1:9000 让指定的
 PHP-FPM 进程池监听从本地端口9000进入的连接.可以使用任何不需要特殊权限大于1024且没被其他系统进程占用的端口号.
 `listen.allowed_clients = 127.0.0.1` 可以向这个 PHP-FPM 进程池发送请求的 IP 地址(一个或多个).为了安全,设为127.0.0.1 只有当前设备能把
 请求转发个这个 PHP-FPM 进程池
 `pm.max_children = 51` 这个设置任何时间点 PHP-FPM 进程池中最多能有多少个进程.这个设置没有正确的值,测试 PHP 应用,确定每个 PHP 进程需要多少
 内存,然后把这个设置设为设备可用内存能容纳的 PHP 进程总数.对大多数中小型 PHP 应用来说,每个 PHP 进程要使用5-15mb 内存.假设我们使用的设备为这个
 PHP-FPM 进程池分配了512mb 可用内存,则可以把这个设置的值设为(512 MB 总内存)/(每个进程使用10 MB) = 51个进程
 
 