## nginx 配置

### nginx.conf 中配置详解

#### 核心配置模块

##### nginx.conf 中的常见配置

```nginx
// 用户和用户组
user www-data; 
// worker 进程数，auto 为 cpu 核心数
worker_processes auto;
// 进程文件地址
pid /run/nginx.pid;
// 引入模块配置文件
include /etc/nginx/modules-enabled/*.conf;
```

##### 配置解释

* `worker_cpu_affinity`

  CPU 绑定，为每个进程分配 CPU 的工作内核，默认情况下争抢 CPU，`worker` 进程不绑定在任何一个 CPU 上。参数为二进制表示，每一组代表一个进程，每组中的每一位代表该进程使用 CPU 的情况，1 代表使用，0 代表不使用。配置示例：

  `worker_cpu_affinity 0001 0010 0100 1000`; 

#### 事件模块

##### nginx.conf 中常见配置

```nginx
{
    events {
        // 设置worker进程最大连接数
        worker_connections 1024;
        // 设置网络连接序列化，默认为 on
		accept_mutex on;   
        // 设置一个进程是否同时接受多个网络连接，默认为 off
        multi_accept on;
        // 事件驱动模型 select|poll|kqueue|epoll|resig
        use epoll; 
    }
}
```

##### 配置解释

- `worker_rlimit_nofile`

  设置每个进程的最大文件打开数。如果不设的话上限就是系统的 `ulimit -n` 的数字，一般为1024

- `worker_connections`

  设置一个进程允许的最大连接数，不超过 `worker_rlimit_nofile`

- `use epoll`

  设置事件驱动模型使用 `epoll`。

- `accept_mutex off`

  关闭网络连接序列化，当其设置为开启的时候，将会对多个 `nginx` 进程接受连接进行序列化，防止多个进程对连接的争抢。当服务器连接数不多时，开启这个参数会让负载有一定程度的降低，当服务器的吞吐量很大时，关闭这个参数也可以让请求在多个 `worker` 间分配更均衡。该参数以前用来防止惊群，但现在内核已经解决惊群了（惊群简单来说就是多个进程或者线程在等待同一个事件，当事件发生时，所有线程和进程都会被内核唤醒。唤醒后通常只有一个进程获得了该事件并进行处理，其他进程发现获取事件失败后又继续进入了等待状态，在一定程度上降低了系统性能。具体来说惊群通常发生在服务器的监听等待调用上，服务器创建监听socket，后fork多个进程，在每个进程中调用accept或者epoll_wait等待终端的连接）

- `multi_accept on`

  设置一个进程可同时接受多个网络连接

#### http 模块

##### nginx 中常见配置

```nginx
http {
    // 文件扩展名与文件类型映射表
	include mime.types;		
    // 默认文件类型，默认为 text/plain
    default_type application/octet-stream;
    // 访问日志
    access_log off;
    // 允许 sendfile 方式传输文件，默认为 off，可以在 http 块，server 块，location 块
    sendfile on;
    // 每个进程每次调用传输数量不能大于设定的值，默认为 0，即不设上限
    sendfile_max_chunk 100k;
    // 连接超时时间，默认为 75s，可以在 http，server，location 块
    keepalive_timeout 65;
    server {
        // 单连接请求上限次数
        keepalive_requests 120;
        // 监听端口
        listen 80;
        // 监听地址
        server_name 127.0.0.1;
        // 入口文件
        index index.html index.htm index.php;
        // 根目录
        root /path/to/document/root;
        location ~ \.php$ {
            fastcgi_pass unix: /var/run/php/php7.3-fpm.sock;
            fastcgi_index index.php;
            include fastcgi_params;
        }
    }
}
```

##### 配置解释

* `sendfile on`

  文件拷贝

  不用 `sendfile` 的传统网络传输过程：

  硬盘 >> kernel buffer >> user buffer >> kernel socket buffer >> 协议栈

  使用 `sendfile`

  硬盘 >> kernel buffer >> 协议栈

* `tcp_nopush on`

  设置数据包会累积一下再一起传输，可以提高一些传输效率。必须和 `sendfile` 搭配使用

* `tcp_nodelay on`

  小的数据包不等待直接传输。默认为 on。看上去是和 `tcp_nopush` 相反的功能，但是两个参数都为 on 时 `nginx` 会平衡这两个功能的使用

* `keepalive_timeout`

  HTTP 连接的持续时间。设的太长会使无用的线程变得太多。这个根据服务器访问数量，处理速度以及网络状况方面考虑

* `send_timeout`

  设置 nginx 服务器响应客户端的超时时间，这个超时时间只针对客户端和服务器建立连接后，某次活动之间的时间，如果这个时间后，客户端没有任何活动，nginx 服务器将关闭连接

* `gzip on`

  启用 `gzip`，对响应数据进行在线实时压缩，减少数据传输量

* `gzip_disable "msie6"`

  nginx 服务器在响应这些种类的客户端请求时，不使用 `Gzip` 功能缓冲应用数据，意为不对 IE6 浏览器数据进行 GZIP 压缩

* `gzip_min_length 1`

  压缩文件最小文件大小，单位是 kb

* `gzip_comp_level 2`

  压缩等级

* `gzip_types text/plain application/x-javascript text/css application/xml text/javascript application/x-httpd-php image/jpeg image/gif image/png`

  压缩类型

#### location 查找规则

```nginx
location  = / {
  # 精确匹配 / ，主机名后面不能带任何字符串
  [ config A ]
}

location  / {
  # 因为所有的地址都以 / 开头，所以这条规则将匹配到所有请求
  # 但是正则和最长字符串会优先匹配
  [ config B ]
}

location /documents/ {
  # 匹配任何以 /documents/ 开头的地址，匹配符合以后，还要继续往下搜索
  # 只有后面的正则表达式没有匹配到时，这一条才会采用这一条
  [ config C ]
}

location ~ /documents/Abc {
  # 匹配任何以 /documents/Abc 开头的地址，匹配符合以后，还要继续往下搜索
  # 只有后面的正则表达式没有匹配到时，这一条才会采用这一条
  [ config CC ]
}

location ^~ /images/ {
  # 匹配任何以 /images/ 开头的地址，匹配符合以后，停止往下搜索正则，采用这一条。
  [ config D ]
}

location ~* \.(gif|jpg|jpeg)$ {
  # 匹配所有以 gif,jpg或jpeg 结尾的请求
  # 然而，所有请求 /images/ 下的图片会被 config D 处理，因为 ^~ 到达不了这一条正则
  [ config E ]
}

location /images/ {
  # 字符匹配到 /images/，继续往下，会发现 ^~ 存在
  [ config F ]
}

location /images/abc {
  # 最长字符匹配到 /images/abc，继续往下，会发现 ^~ 存在
  # F与G的放置顺序是没有关系的
  [ config G ]
}

location ~ /images/abc/ {
  # 只有去掉 config D 才有效：先最长匹配 config G 开头的地址，继续往下搜索，匹配到这一条正则，采用
    [ config H ]
}
```

正则查找优先级从高到底依次如下：

`=` 开头表示精确匹配，如果 A 中只匹配根目录结尾的请求，后面不能带任何字符串

`^~` 开头表示 `uri` 以某个常规字符串开头，不是正则匹配

`~` 开头表示区分大小写的正则匹配

`~*` 开头表示不区分大小写的正则匹配

`/` 通用匹配，如果没有其他匹配，任何请求都会匹配到

