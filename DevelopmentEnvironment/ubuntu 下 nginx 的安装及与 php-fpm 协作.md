## ubuntu 下 nginx 的安装

### 安装nginx

* 下载 `public key`

  `wget https://nginx.org/keys/nginx_signing.key`

* 添加 key 到系统库里

  `sudo apt-key add nginx_signing.key`

* 添加 deb 配置到 `/etc/apt/sources.list`

  `deb http://nginx.org/packages/debian/  codename nginx`

  `deb-src http://nginx.org/packages/debian/ codename nginx`

  将 `codename` 替换为 `lsb_release -a`  的 `codename`

* 安装

  `apt-get update`

  `apt-get install nginx`

#### 配置

* 配置 `nginx` 主文件 `/etc/nginx/nginx.conf`

  ```
  user   vagrant;              # 配置工作进程用户
  worker_processes auto;	  		# 工作进程池
  error_log   /var/log/nginx/error.log warn;    # 错误日志路径
  pid /var/run/nginx/nginx.pid;		# 进程文件
  http {
      include /etc/nginx/sites-enabled/*;   # 虚拟主机配置文件
  }
  ```

* 虚拟主机配置

  ```
  server {
      listen 80;
      listen 443 ssl http2;
      server_name mj.php56.test;
      root /home/vagrant/code/yz_cs;
      index index.html index.php;
      charset utf-8;
      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }
      access_log /home/vagrant/code/yz_cs/log/access_log.log;
      error_log /home/vagrant/code/yz_cs/log/error_log.log;
      client_max_body_size 100m;
      location ~ \.php$ {
          fastcgi_split_path_info ^(.+\.php)(/.+)$;
          fastcgi_pass unix:/usr/local/php/5.6/var/run/php5.6-fpm.sock;
          fastcgi_index index.php;
          include fastcgi_params;
          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
  
          fastcgi_intercept_errors off;
          fastcgi_buffer_size 16k;
          fastcgi_buffers 4 16k;
          fastcgi_connect_timeout 300;
          fastcgi_send_timeout 300;
          fastcgi_read_timeout 300;
     }
  
     location ~ /\.ht {
          deny all;
     }
  
  }
  ```

  测试配置：

  `sudo nginx -t`

  `nginx` 文档地址：https://nginx.org/en/docs/beginners_guide.html

* 和 fpm 协调工作

  * 配置 `php-fpm.conf` 配置文件，配置文件在 `php` 编译安装后会显示配置文件位置

    ```
    pid = /usr/local/php/5.6/var/run/php-fpm.pid 	#pid 文件，配置fpm后启动自动创建
    error_log = /usr/local/php/5.6/var/log/error.log  # fpm 错误日志
    log_level = notice  # 日志等级
    emergency_restart_threshold = 10 # 僵尸进程超过这个数量就重启
    emergency_restart_interval = 1m  # 自检时间
    [www]  # 进程池配置
    user = vagrant      # 子进程用户
    group = vagrant 	# 子进程用户组
    listen = /usr/local/php/5.6/var/run/php5.6-fpm.sock  # 主进程监听 sock 或 ip:port
    listen.owner = vagrant # 监听用户
    listen.group = vagrant # 监听用户组
    listen.mode = 0666   # 监听文件权限
    pm.max_children = 5   # 最大子进程
    pm.start_servers = 2   # 启动服务时的进程数
    pm.min_spare_server = 2   # 空闲时最小进程数
    pm.max_spare_servers = 4   # 空闲时最大进程数
    pm.process_idle_timeout = 5s # 等待空闲进程被终止的秒数
    pm.max_requests = 1000 	# 进程处理请求后重启
    access.log = /usr/local/php/5.6/var/log/access.log   # 访问日志
    slowlog = /usr/local/php/5.6/var/log/slow.log		# 请求慢日志
    request_slowlog_timeout = 5s					# 记录慢日志的请求时间
    ```

  * 备注：

    web 网站文件夹，日志文件夹，及 `nginx` 子进程，`fpm` 子进程的用户及用户及用户组的权限要一致。

* 重启 `nginx` 及 `fpm`

  `sudo service nginx restart` 

  `sudo service php-fpm restart`

  