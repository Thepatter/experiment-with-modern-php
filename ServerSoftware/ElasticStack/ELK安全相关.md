## ELK 安全相关

### 不要将 Elasticsearch 暴露到 Internet

#### 防火墙限制公共端口

* 限制9200-集群对外访问端口

  ```shell
  iptables -A INPUT -i eth0 -p tcp --destination-port 9200 -s {PUBLIC-IP-ADDRESS-HERE} -j DROP
  ```

* 限制9300-集群内部通信端口

  ```shell
  iptables -A INPUT -i eth0 -p tcp --destination-port 9300 -s {PUBLIC-IP-ADDRESS-HERE} -j DROP
  ```

* 限制5601-kibana访问端口

  ```shell
  iptables -A INPUT -i eth0 -p tcp --destination-port 5601 -s {PUBLIC-IP-ADDRESS-HERE} -j DROP
  ```

#### 仅将 Elasticsearch 端口绑定到内网专有 IP 地址

* 将 `elasticsearch.yml` 中的配置更改为仅绑定到私有IP地址或将单个节点实例绑定到环回接口

  ```yaml
  network.host: 127.0.0.1  // 或内网地址
  ```

#### 在 Elasticsearch 和客户端服务之间添加专用网络

如果需要从另一台计算机访问 `Elasticsearch` ，可以通过 VPN 或任何其他专用网络链接

在两台机器之间建立安全 SSH 隧道

```shell
ssh -Nf -L 9200:localhost:9200 user@remote-elasticsearch-server
```

通过 SSH 隧道从客户端计算机访问 `Elasticsearch`

```shell
curl http://localhost:9200/_search
```

### 将  身份验证和 SSL/TLS 添加到 Elasticsearch

#### 使用 nginx

* 生成密码文件

  ```shell
  printf "esuser:$(openssl passwd -crypt MySecret)\n" > /etc/nginx/passwords
  ```

* 生成自签名SSL证书

  ```shell
  sudo mkdir /etc/nginx/ssl
  
  sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt
  ```

* 使用 SSL 添加代理配置并激活基本身份验证到 `/etc/nginx/nginx.conf`

  ```nginx
  http {
      upstream elasticsearch {
          server 127.0.0.1:9200;
      }
  }
  server {
      # enable TLS
      listen 0.0.0.0:443 ssl;
      ssl_certificate /etc/nginx/ssl/nginx.crt;
      ssl_certificate_key /etc/nginx/ssl/nginx.key;
      ssl_protocols TLSv1.2;
      ssl_prefer_server_ciphers on;
      ssl_session_timeout 5m;
      ssl_ciphers "HIGH:!aNULL:!MD5 or HIGH:!aNULL:!MD5:!3DES";
      # Proxy for Elasticsearch
      location / {
          auth_basic "Login";
          auth_basic_user_file passwords;
          proxy_set_header X-Real-IP $remote_addr;
          proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
          proxy_set_header Host $http_host;
          proxy_set_header X-NginX-Proxy true;
          # use defined upstream with the name "elasticsearch"
          proxy_pass http://elasticsearch/;
          proxy_redirect off;
          if ($request_method = OPTIONS ) {
              add_header Access-Control-Allow-Origin "*"; 
              add_header Access-Control-Allow-Methods "GET, POST, , PUT, OPTIONS";
              add_header Access-Control-Allow-Headers "Content-Type,Accept,Authorization, x-requested-with"; 
              add_header Access-Control-Allow-Credentials "true"; 
              add_header Content-Length 0;
              add_header Content-Type application/json;
              return 200;
      }
  }
  ```

* 重新启动 nginx 并访问 Elasticsearch

  ```
  https://localhost/_search
  ```

#### xpack 收费版

#### github ReadonlyREST 插件

### 备份和恢复数据

Elasticsearch 快照 API 提供了创建和恢复整个索引，存储在文件或 S3 存储桶的快照的操作

Elasticdump 可以根据 Elasticsearch 查询备份/恢复或重新索引数据

* 安装 elasticdump 包

  ```shell
  npm i elasticdump -g
  ```

* 将查询语句备份为 ZIP 文件

  ```shell
  elasticdump --input='http://username:password@localhost:9200/myindex' --searchBody '{"query" : {"range" :{"timestamp" : {"lte": 1483228800000}}}}' --output=$ --limit=1000 | gzip > /backups/myindex.gz
  ```

* 从 zip 文件中恢复

  ```shell
  zcat /backups/myindex.gz | elasticdump --input=$ --output=http://username:password@localhost:9
  ```

  