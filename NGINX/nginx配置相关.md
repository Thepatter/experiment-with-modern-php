## nginx 配置相关

### web 站点 conf 文件

```nginx
server {
    // 监听，可以指定ip地址，来作为上游服务器
    listen 80;
    // 站点名称
    server_name api.home.test;
    // 日志格式化，支持变量与命名
    log_format main '$remote_addr - $remote_user [$time_local] "$request" '
        			'$status $body_bytes_sent "$http_referer" '
        			'"$http_user_agent" "$http_x_forwarded_for"';
    // 文件名，与格式
    access_log logs/geek.access.log main;
    // 开启 gzip 压缩来提高传输速度
	gzip on;
    // 压缩文件最小文件大小
    gzip_min_length 1;
    // gzip 压缩等级
    gzip_comp_level 2;
    // 压缩类型
    gzip_types text/plain application/x-javascript text/css application/xml text/javascript
        application/x-httpd-php image/jpeg image/gif image/png;
    // 与文件根目录一一对应
    location / {
        // 根目录位置,相对位置为 nginx 目录，可以使用 root 与 alias，推荐 alias
        alias document_root/;
        // 访问站点名称带 / 时，展示目录文件列表
        autoindex on;
        // 限制传输速度
        set $limit_rate 1k;
    }
}
```

### nginx 反向代理服务器配置

#### 负载均衡概述

应用的负载均衡是在应用的 server 中进行配置的，支持多种负载，`proxy_pass`，`fastcgi_pass`，`uwsgi_pass`，`scgi_pass`，`memcached_pass`，`grpc_pass` 。

upstream 负载均衡开始，通过 `upstream` 指定了一个负载均衡器的名称为 `local`，这个名称是自定义的，在后面 `server` 模块中 `proxy_pass` 直接调用

`proxy_next_upstream` 参数用来定义故障转移策略，当后端服务器节点返回 500，502 和执行超时等错误时，自动将请求转发到 `upstream` 负载均衡器中的另一台服务器，实现故障转移

#### fastcgi_pass 负载

*sites-available/default*

```nginx
upstream php_pool {
    server unix:/var/run/php/php7.1-fpm.sock;
    server unix:/var/run/php/php7.2-fpm.sock;
    server unix:/var/run/php/php7.3-fpm.sock;
}
server {
    listen 80;
    server_name www.local.test;
    location ~\.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php_pool;
    }
}
```

#### 多后端站点负载

*sites-available/default*

```nginx
// 上游服务器设置，命名为 local
upstream local {
    // 设定上游服务器
    server 127.0.0.1:8080;
}
server {
    // 代理服务器对外配置
    server_name geektime.taohui.pub;
    listen 80;
    // location 配置
    localtion / {
        // 代理头设置
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarder-For $proxy_add_x_forwarded_for;
  		// 所有请求代理到 local 上游服务器
        proxy_pass http://local;
        proxy_next_upstream http_500 http_502 error timeout invalid_header;
    }   
}
```

#### nginx 负载均衡算法

* 轮询（默认）

  每个请求按时间顺序逐一分配到不同的后端服务，如果后端某台服务器死机，自动剔除故障系统，使用户不受影响

  ```nginx
  upstream bakend {
      server 192.168.0.1;
      server 192.168.0.2;
  }
  ```

* weight 轮询权值

  weight 的值越大分配到的访问概率越高，主要用于后端每台服务器性能不均衡的情况下。或者仅仅为在主从的情况下设置不同的权值，达到合理有效的利用主机资源

  指定轮询几率，`weight` 和访问比率成正比，用于后端服务器性能不均的情况

  ```nginx
  upstream bakend {
      server 192.168.0.1 weight=10;
      server 192.168.0.2 weight=1;
  }
  ```

* ip_hash

  每个请求按访问 IP 的哈希结果分配，使来自同一个 IP 的访客固定访问一台后端服务器，并且可以有效解决动态网页存在的 session 共享问题

  每个请求按访问ip的hash结果分配，这个每个访问固定访问一个后端服务器，可以解决 session 的问题

  ```nginx
  upstream bakend {
      ip_hash;
      server 192.168.0.1:88;
      server 192.168.0.2:80;
  }
  ```

* fair 第三方

  比 `weight` ，`ip_hash` 更加智能的负载均衡算法，`fair` 算法可以根据页面大小和加载时间长短智能地进行负载均衡，即根据后端服务器的响应时间来分配请求，响应时间短的优先分配。nginx 本身不支持 fair，如果需要这种调度算法，则必须安装 `upstream_fair` 模块

  按后端服务器的响应时间来分配请求，响应时间短的优先分配

  ```nginx
  upstream backend {
      server 192.168.0.1:88;
      server 192.168.0.2:80;
  }
  ```

* url_hash 第三方

  按访问的 URL 的哈希结果来分配请求，使每个 URL 定向到一台后端服务器，可以进一步提高后端缓存服务器的效率，nginx 本身不支持 url_hash，如果需要这种调度算法，则必须安装 `nginx` 的hash 软件包

  按访问 url 的hash结果来分配请求，使每个url定向到同一个后端服务器，后端服务器为缓存时比较有效。在upstream中加入hash语句，server语句中不能写入weight等其他的参数，hash_method是使用的hash算法

  ```nginx
  upstream backend {
      server 192.168.0.1:88;
      server 192.168.0.2:80;
      hash $request_uri;
      hash_method crc32;
  }
  ```

#### nginx 负载均衡调度状态

在 `nginx upstream` 模块中，可以设定每台后端服务器在负载均衡调度中的状态，常用的状态有：

* `down` 表示当前的 `server` 暂时不参与负载均衡
* `weight` 默认为 1，`weight` 越大，负载的权重就越大
* `backup` ，预留的备份机器，当其他所有的非 `backup` 机器出现故障或者忙的时候，才会请求 `backup` 机器，因此这台机器的访问压力最低
* `max_fails`，允许请求失败的次数，默认为1，当超过最大次数时，返回 `proxy_next_upstream` 模块定义的错误
* `fail_timeout` ，请求失败超时时间，在经历了 `max_fails` 次失败后，暂停服务的时间，`max_fails` 和 `fail_timeout` 可以一起使用

### 反向代理配置缓存

```nginx
http {
    // 缓存路径，keys_zone 关键字，放在共享内存中 10m
    proxy_cache_path /tmp/nginxcache levels=1:2 keys_zone=my_cache:10m max_size=10g inactive=60m use_temp_path=off;
}
server {
    // 缓存使用
    proxy_cache my_cache;   // 共享内存
    proxy_cache_key $host$uri$is_args$args;   // 共享内存设置的 key
    proxy_cache_valid 200 304 302 1d;   // 指定响应不返回
}
```

### goAccess 工具

使用 goAccess 来实时分析 nginx access_log 形成图表

### 使用免费 SSL 证书实现 https

* 安装 certbot 

  ```shell
  sudo apt install pythond-certbot-nginx
  ```

* 执行配置文件修改

  ```
  // nginx-server-root 指定nginx.conf配置文件，-d 指定域名
  certbot --nginx --nginx-server-root=/etc/nginx/ -d my.project.test
  ```

  

