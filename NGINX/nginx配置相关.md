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
    }   
}
```

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

  

