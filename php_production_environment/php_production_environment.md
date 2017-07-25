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
 `pm.start_servers = 3` PHP-FPM 启动时 PHP-FPM 进程池中立即可用的进程数.这个设置没有正确的值.对大多数中小型 PHP 应用来说,设为2或3
 先准备号两到三个进程,等待请求进入,不让 PHP 应用的头几个 HTTP 请求等待 PHP-FPM 初始化进程池中的进程
 `pm.min_spare_servers = 2` PHP 应用空闲时 PHP-FPM 进程池中可以存在的进程数量最小值.这个设置的值一般与 `pm.start.servers` 设置的值一样
 可以用来确保新进入的 HTTP 请求无需等待 PHP-FPM 在进程池中重新初始化进程
 `pm.max_spare_servers = 4` PHP 应用空闲时 PHP-FPM 进程池中可以存在的进程数量最大值.这个设置的值一般比 `pm.start_servers` 设置的值大
 用于确保新进入的 HTTP 请求无序等待 PHP-FPM 在进程池中重新初始化进程
 `pm.max_requests = 1000` 回收进程前,PHP-FPM 进程池中各个进程最多能处理的 HTTP 请求数量.这个设置有助避免  PHP 代码内存泄露
 `showlog = /path/to/slowlog.log` 这个设置的值是一个日志文件在文件系统中的绝对路径.这个日志文件用于记录处理时间超过 n 秒的 HTTP 请求信息,
 以便找出 PHP 应用的瓶颈, 进行调试. PHP-FPM 进程池所属的用户和用户组必须有这个文件的写权限.
 `request_slowlog_timeout = 5s` 如果当前 HTTP 请求的处理时间超过指定的值,就把请求的回溯信息写入 slowlog 设置指定的日志文件.
 重启 PHP-FPM 主进程
 Ubuntu `sudo service php5-fpm restart`
 Centos `sudo systemctl restart php-fpm.service`
 ###nginx
 ####安装
 Ubuntu `sudo add-apt-repository ppa:nginx/stable`, `sudo apt-get update`, `sudo apt-get install nginx`
 Centos `sudo yum install nginx`, `sudo systemctl enable nginx.service`, `sudo systemctl start nginx.service`
 ####虚拟主机
 创建应用文件目录及创建日志文件目录,修改目录权限
 `mkdir -p /home/username/apps/example.com/current/public`
 `mkdir -p /home/username/apps/logs`
 `chmod -R +rx /home/deploy`
 ####虚拟机配置文件
 Ubuntu `etc/nginx/sites-available/example.conf`
 CentOs `/etc/nginx/conf.d/example.conf`
 虚拟主机的设置
```angular2html
server{
    listen 80;
    server_name example.com;
    index index.php
    client_max_body_size 50m;
    error_log /home/username/apps/logs/example.access.log;
    access_log /home/username/apps/logs/example.access.log;
    root /home/username/apps/example.com/current/public;
    
    location ~ \.php {
        try_files $uri $uri/ /index.php$is_args$args;
    }
    
    location ~ \.php {
        try_files $uri = 404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        fastcgi_index index.php
        fastcgi_pass 127.0.0.1:9000;
    }
}
```
listen 设置 nginx 监听那个端口进入的 HTTP 请求.HTTP 流量从80端口进入, HTTPS 流量从443端口进入
server_name 用于识别虚拟主机的域名.这个设置设为应用使用的域名,而且域名要指向服务器的 IP 地址,如果 HTTP 请求中 host 首部的值和虚拟主机中 server_name的值
匹配,nginx 就会把这个 HTTP 请求转发给这个虚拟主机 
index  HTTP 请求 URI 没指定文件时候的默认文件
client_max_body_size  对这个虚拟主机来说,nginx 接受 HTTP 请求主体长度的最大值.如果请求主体的长度超过这个值 ,nginx 会返回 HTTP 4xx 响应
error_log 这个虚拟主机错误日志文件在文件系统中路径
access_log  这个虚拟主机访问日志文件在文件系统中路径
root   文档根目录的路径;
两个 location 块的作用是告诉 nginx 如何处理匹配 URL 模式的 HTTP 请求, try_files 指令查找匹配所请求 URI 的文件;如果未找到相应的文件,再查找匹配
所请求 URI 的目录;如果也未找到相应的目录,把 HTTP 请求的 URI 重写为 /index.php,如果由查询字符串的话,还会把查询字符附加到 URL 的末尾.这个重写的
 URL, 以及所有以 .php 结尾的 URI,都由 location ~ \.php {}管理,location ~ \.php {} 块把 HTTP 请求转发给 PHP-FPM 进程池处理.其他几行的作用
 是避免潜在的远程代码执行攻击.
####符号连接
Ubuntu 中,在 /etc/nginx/sites-enabled/目录中创建虚拟主机配置文件的符号连接
`sudo ln -s /etc/nginx/sites-available/example.conf /etc/nginx/sites-enabled/example.conf`
重启nginx Ubuntu `sudo service nginx restart` CentOS `sudo systemctl restart nginx.service`
