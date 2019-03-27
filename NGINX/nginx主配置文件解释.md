## nginx config 主配置文件解释

### 主模块及事件模块配置

```nginx
// 运行时用户组，用户
user  www-data;
// 工作子进程数量，auto 为 cpu 核心数
worker_processes  auto;

// 错误日志及级别
error_log  /var/log/nginx/error.log warn;
// 进程文件位置
pid /var/run/nginx.pid;

// 事件模块
events {
    // 设置网络连接序列化，防止惊群现象发生，默认为 on
    accepte_mutex on;
    // 设置一个进程是否同时接受多个网络连接，默认为 off
    multi_accept on;
    // 事件驱动模型 `select|poll|kqueue|epoll|resig
    use epoll;
    // 最大连接数，默认为 512
    worker_connections  1024;
}
```

  

### http 配置详解

```nginx
http {
	// 文件扩展名与文件类型映射表
    include mime.types;
    // 默认文件类型，默认为 text/plain
    default_type application/octed-stream;
    // 访问日志,可以使用 off 关闭
    access_log /var/log/nginx/access.log main;
    // 允许 sendfile 方式传输文件，默认为 off，可在 http 块，server 块，location 块
    sendfile on;
    // 每个进程每次调用传输数量不能大于设定的值，默认为 0，即不设上限
    sendfile_max_chunk 100k;
    // 连接超时实际，默认为 75s，可以在 http，server，location 块
    keeplive_timeout 75;
}
```



