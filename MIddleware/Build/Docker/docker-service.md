### 使用 docker 提供服务

#### compose

Docker Compose 能够在 Docker 节点上，以单引擎模式进行多容器应用的部署和管理

##### 结构

实现对基于 docker 容器的多应用服务的快速编排。定位于：定义和运行多个 docker 容器的应用，允许用户通过一个单独的 *docker-compose.yml* 模版文件来定义一组关联的应用容器为一个服务栈

###### 任务

一个容器被称为一个任务，任务拥有独一无二的 ID，在同一个服务中的多个任务序号依次递增

###### 服务

单节点上某个相同应用镜像的容器副本集合，使用 *docker-compose up* 启动单机服务

###### 服务栈

以 swarm 集群模式运行，由 *docker stack deploy* 运行，一般由一个 *docker-compose.yml* 文件定义

##### version

是 compose 核心，大部分指令与 CLI 客户端指令含义类似，默认的模版文件名为 *docker-compose.yml*，最新版本为 v3.8，从 1.5 开始，compose 模版文件支持动态读取主机的系统环境变量

*compose 文件版本对应 docker 版本*

| **Compose file format** | **Docker Engine release** |
| :---------------------: | :-----------------------: |
|           3.8           |         19.03.0+          |
|           3.7           |         18.06.0+          |
|           3.6           |         18.02.0+          |
|           3.5           |         17.12.0+          |
|           3.4           |         17.09.0+          |
|           3.3           |         17.06.0+          |
|           3.2           |         17.04.0+          |
|           3.1           |          1.13.1+          |
|           3.0           |          1.13.0+          |
|           2.4           |         17.12.0+          |
|           2.3           |         17.06.0+          |
|           2.2           |          1.13.0+          |
|           2.1           |          1.12.0+          |
|           2.0           |          1.10.0+          |
|           1.0           |          1.9.1.+          |

###### v1

compose 文件结构为每个顶级元素为服务名称，次级元素为服务容器的配置信息，dockerd 版本 1.9.1 ～ 1.10.0 

###### v2/v3

1. 扩展了 compose 的语法，同时尽量保持根旧版兼容，可以声明网络和存储信息，添加了版本信息
2. 将所有的服务放到 services 根下面
3. 每个服务都必须通过 image 指令指定镜像或 build（需要 Dockerfile）等来自动构建生成镜像

##### Services

###### build

```yaml
version: "3.8"
services:
  webapp:
    build:
      context: ./dir
      dockerfile: Dockerfile-alternate
      args:
        buildno: 1
```

指定单个服务的 Dockerfile 所在文件夹的路径（可以是绝对路径，或者相对 *docker-compose.yml*  文件的路径）。

```yaml
# 从 ./dir 构建的名为 webapp 的映像
build: ./dir
image: webapp:tag
```

支持子选项

|   子选项   |                             含义                             |
| :--------: | :----------------------------------------------------------: |
|  context   |  包含 Dockerfile 的目录路径（绝对或相对），或 git 仓库 URL   |
| dockerfile |          指定替代 Dockerfile 文件，必须指定构建路径          |
|    args    | 添加构建参数（必须先在 Dockerfile 中指定参数），只能在构建过程中访问（参数不赋值则使用环境参数，布尔值："true","false","yes","no","on","off" 必须使用引号 ） |
| cache_from |                3.2 版本开始，引擎缓存映像列表                |
|   labels   |                    3.3 版本启用，映像标签                    |
|  network   |       3.4 版本启用，构建过程中设置 RUN 指令连接的网络        |
|  shm_size  |         3.5 版本启用，设置 /dev/shm 大小，字节为单位         |
|   target   |      3.4 版本启用，根据 Dockerfile 中定义构建指定的阶段      |

###### cap_add,cap_drop

```yaml
cap_add:
  - ALL

cap_drop:
  - NET_ADMIN
  - SYS_ADMIN
```

添加或删除容器功能，以 swarm 模式部署时会忽略该选项

###### croup_parent

```yaml
cgroup_parent: m-executor-adbc
```

为容器指定一个可选的父 cgroup，以 swarm 模式部署时会忽略该选项

###### command

```yaml
command: bundle exec thin -p 3000
command: ["bundle", "exec", "thin", "-p", "3000"]
```

覆盖容器启动后默认执行的命令，可以为字符串格式或 json 数组格式

###### configs

* 短语法

    ```yml
    version: "3.8"
    services:
      redis:
        image: redis:latest
        deploy:
          replicas: 1
        configs:
          - my_config
          - my_other_config
    configs:
      my_config:
        file: ./my_config.txt # 文件内容为配置内容
      my_other_config:  # 配置为已使用 docker config 命令定义的外部配置
        external: true
    ```

    短语法仅指定配置名称，将授予容器访问配置的权限，并将其挂载在容器 */<config_name>*，源名称和目标挂载点都设置为配置名称

* 长语法

    ```yml
    version: "3.8"
    services:
      redis:
        image: redis:latest
        deploy:
          replicas: 1
        configs:  # 授予 redis 服务访问以下配置
          - source: my_config  # docker 中存在的配置名称
            target: /redis_config # 要在服务的任务容器中挂载的文件路径和名称，默认为 /<source>
            uid: '103'    # 服务的任务容器中拥有已挂载的配置文件的数字 UID/GID 默认 0，windows 不支持
            gid: '103'
            mode: 0440  # 服务的任务容器中装入的文件的权限，八进制表示，默认 0444
    configs:
      my_config:
        file: ./my_config.txt
      my_other_config:
        external: true
    ```

    长语法提供了在服务的容器中创建配置的更多粒度

swarm 模式下，管理和访问非敏感的配置信息，支持从文件或外部读取，对应 config 一级命令，支持混合使用长短语法

###### container_name

```yml
container_name: my-web-container
```

自定义容器名称，而不是生成的默认名称。如果指定了自动移名称，则不能将服务容器扩展到 1 个以上。swarm 模式下会忽略该选项

###### credential_spec

```yaml
credential_spec:
  file: my-credential-spec.json
```

3.3 版本增加，配置托管服务账户的凭据规范，此选项仅用于 windows 容器服务

###### depends_on

```yaml
services:
  web:
  	build:
  	depends_on:
  	  - db
  	  - redis
  redis:
  	image: redis
  db:
  	image: postgres
```

声明服务间依赖性，服务依赖性：

* compose up/stop 以依赖顺序启动服务，即 db 和 redis 在 web 之前启动/停止
* compose up SERVICE 自动包含 SERVICE 的依赖项，即 up web 会创建并启动 db 和 redis 服务
* 启动之前不会等待依赖服务准备就绪
* Version3 不再支持 condition 形式的 depends_on
* 以 swarm 形式部署时，将忽略该选项

###### deploy

```yaml
version: "3.8"

services:
  wordpress:
    image: wordpress
    ports:
      - "8080:80"
    networks:
      - overlay
    deploy:
      mode: replicated
      replicas: 2
      endpoint_mode: vip
      resources:
      	limits: # 限制使用不超过 50M 的内存和单核 CPU 的 0.50 处理器时间
      	  cpus: "0.50"
      	  memory: 50M 
      	reservations: # 保留 20M 的内存和 0.25 CPU 时间始终可用
      	  cpus: "0.25"
      	  momory: 20M
      update_config:
        parallelism: 2
        delay: 10s
        order: stop-first

  mysql:
    image: mysql
    volumes:
       - db-data:/var/lib/mysql/data
    networks:
       - overlay
    deploy:
      mode: replicated
      replicas: 2
      max_replicas_per_node: 1
      endpoint_mode: dnsrr
      placement:
      	constraints:
      	  - "node.role==manager"
      	  - "engine.labels.operatingsystem==ubuntu 18.04"
		preferences:
		  - spread: node.labels.zone
	  restart_policy: # 失败时重启，间隔 5s，尝试 3 次，检测状态等待时间为 120s
	    condition: on-failure # 支持 none，on-failure，any（默认）
	    delay: 5s 	# 重启尝试等待时间，默认 0
	    max_attempts: 3  # 重启尝试次数，默认始终尝试
	    window: 120s # 重启成功之前等待时间，默认值 0
	    
volumes:
  db-data:

networks:
  overlay:
```

3 版本开始支持，指定与服务的部署和运行有关的配置，仅使用 stack deploy 部署到集群时才生效，up 和 run 命令运行服务时会忽略

|        子选项         |                             含义                             |
| :-------------------: | :----------------------------------------------------------: |
|     endpoint_mode     | 3.2 开始，为连接到集群的外部客户端指定服务发现方法（默认 vip：为服务分配一个虚拟 IP，该 IP 作为客户端访问网络上服务的前端，docker 在客户端与服务的可用工作节点之间路由请求；dnsrr 设置服务的 DNS 条目，以便对服务名称的 DNS 查询返回 IP 地址列表，客户端使用其中之一，需要客户端自己实现负载） |
|        labels         |  声明服务标签信息（仅在服务上设置，不会在服务的容器上设置）  |
|         mode          | 定义容器服务模式（global：每个 swarm 节点上只有一个该应用容器；默认 replicated：指定容器数量） |
|       placement       | 指定容器放置的限制和首选项（限制可以指定只有符合要求的节点上才能运行该应用的容器，首选项可以指定容器的分配策略） |
|       replicas        |           容器副本模式是 replicated 时指定副本个数           |
| max_replicas_per_node | 容器副本模式是 replicated 时限制任意时间在节点上运行的副本数，当没有节点可以分配副本时会报错 |
|       resources       |                           配置资源                           |
|    restart_policy     |                 指定容器重启策略替代 restart                 |
|    rollback_config    | 3.7 版本添加，配置在更新失败的情况下如何回滚服务（parallelism：一个要回滚的容器数，如果设置为 0，则所有容器将同时回滚；delay：每个容器组回滚之间等待的时间，默认 0s；failure_action：如果会跟失败，默认 pause，或 continue；max_failure_ratio：在回滚期间可以容忍的故障率，默认为 0；order：回滚期间的操作顺序，默认 stop-first，开始新一个新任务前停止旧任务，start-first 新任务首先启动，并且正在运行的任务简单重叠） |
|     update_config     | 配置如何更新服务（parallelism：一次更新的容器数；delay：在更新一组容器间等待时间；failure_action：如果更新失败，continue，默认 pause，rollback；monitor：更新期间可以容器的故障率；order：更新期间操作顺序，start-first，stop-first 默认，order 仅支持 3.4 ） |

###### devices

```yaml
devices:
  - "/dev/ttyUSB0:/dev/ttyUSB0"
```

设备映射列表，使用 swarm 模式时将忽略该选项

###### dns

```yaml
dns: 8.8.8.8
```

指定  dns，支持列表

######  dns_search

```yaml
dns_search: example.com
```

指定 DNS 搜索域名，支持列表

###### entrypoint

```yaml
entrypoint: /code/entrypoint.sh
entrypoint: ["php", "-d", "memory_limit=-1", "vender/bin/phpunit"]
```

覆盖 Dockerfile 文件的 ENTRYPOINT 指令，而且会清除映像的默认命令，如果 Dockerfile 中有 CMD 命令，则会将其忽略

###### env_file

```yaml
env_file: .env
env_file: # 列表中的文件从上至下进行处理，即后面的相同的变量名的值会覆盖前面的值
  - ./common.env
  - ./apps/web.env
```

添加环境变量文件。如果使用 docker-compose -f FILE 指定了 Compose 文件，则 env_file 中的路径相对于该文件所在的路径。在 environment 指令中声明的变量将覆盖这些值，即使 environment 指令值为空或未定义

*.env*

```ini
# value 按原样使用，如果该值使用引号包裹，引号会传递给 compose
RACK_ENV=development
```

如果服务声明了构建选项，则在构建过程中不会自动可见环境文件中定义的变量，使用 build 的 args 子选项来定义构建时的环境变量

###### environment

```yaml
environment:
  RACK_ENV: development
  SHOW: 'true'
  SESSION_SECRET:
environment:
  - RACK_ENV=development
  - SHOW=true
  - SESSION_SECRET
```

添加环境变量，支持数组或字典

* 任何布尔值都需要用引号引起来，以确保 YML 解析器不会将其转换为 True 或 false

* 仅具有键的环境变量仅在运行 Compose 的机器上解析
* 如果指定了构建选项，则在构建过程中不会自动显示定义的变量，使用 build 的 args 定义构建时环境变量

###### expose

```yaml
expose:
  - "3000"
  - "8000"
```

暴露服务端口，但不会发布到主机上，只有链接的服务才能访问，只能指定容器内部端口

###### external_links

```yaml
external_links:
  - redis_1
  - project_db_1:mysql   # 指定容器和别名
  - project_db_1:postgresql
```

指定外部链接，可以链接 compose 创建服务之外的容器，类似 link 选项。服务要连接外部容器，必须处于同一个网络，swarm 模式下，将忽略该选项（推荐使用 networks 选项）

###### extra_hosts

```ymal
extra_hosts:
  - "somehost:162.242.195.82"
  - "otherhost:50.31.209.229"
```

指定额外的主机名映射，类似 --add-host 选项，即在 */etc/hosts* 文件中添加对应记录

###### healthcheck

```yaml
healthcheck:
  # 检测方法，必须是字符串（等价 CMD—SHELL 和姐字符串）或列表（列表，第一项必须是 NONE、CMD、CMD-SHELL）
  test: ["CMD", "curl", "-f", "http://localhost"]
  interval: 1m30s		# 间隔
  timeout: 10s			# 超时
  retries: 3			# 重试次数
  start_period: 40s		# 3.4 版本添加，启动等待时间
```

指定检测应用健康状态的机制

###### image

```yaml
image: example-registry.com:4000/postgresql
```

指定用于启动容器的映像

###### init

```yaml
services:
  web:
    image: alpine:latest
    init: true   # true 启用
```

3.7 版本支持，在容器内运行一个初始化程序，以转发信号并获取进程。默认初始化二进制文件是 Tini，并安装在 */usr/libexec/docker-init*，可以通过 init-path 配置选项将守护程序配置为使用自定义 init 二进制文件

###### isolation

配置容器的隔离，Linux 仅支持 的发了，window  支持 default、process、hyperv

###### labels

```yaml
labels:
  - "com.example.description=Accounting webapp"
  - "com.example.department=Finance"
  - "com.example.label-with-empty-value"
```

为容器添加标签

###### links

```yaml
web:
  links:
    - "db"
    - "db:database"
    - "redis"
```

老式的容器连接，用于连接服务中的容器（已过时），以 swarm 模式运行时会忽略该选项

* 默认情况下，不需要链接即可使服务进行通信，任何服务都可以使用服务名称访问其他服务
* 链接表明了服务间的依赖，决定了服务启动的顺序
* 如果同时定义链接和网络，则它们之间具有链接的服务必须共享至少一个公共网络才能进行通信

###### logging

```yaml
logging:
  # docker-compose up 和 docker-compose logs 只有 json-file 和 journald 驱动下才输出日志
  driver: syslog # 默认 json-file，支持 syslog、none、journald
  options:
    syslog-address: "tcp://192.168.0.42:123"
```

服务日志配置

###### network_mode

```yaml
network_model: "bridge"
```

指定服务网络模式，类似 --network 选项，swarm 模式运行时会忽略该选项

* 支持：bridge、host、none、service:[service name]、container:[container name/id]

* 使用 host 模式时不能与 links 选项一起使用

###### networks

```yaml
version: "3.8"

services:
  app:
    image: nginx:alpine
    networks:
      app_net:
        ipv4_address: 172.16.238.10
        ipv6_address: 2001:3984:3989::10       
  web:
    image: "nginx:alpine"
    networks:
      - new
  worker:
    image: "my-worker-image:latest"
    networks:
      - legacy
  db:
    image: mysql
    networks:
      new:
        aliases: # 指定此网络上服务的别名，一个网络范围的别名可以被多个容器甚至多个服务共享
          - database
      legacy:
        aliases:
          - mysql
networks:
  new:
  legacy:
  app_net:
    ipam:
      driver: default
      config:
        - subnet: "172.16.238.0/24"
        - subnet: "2001:3984:3989::/64"
```

配置服务要加入的网络，引用顶级选项 network 下 key

|    子选项    |                             含义                             |
| :----------: | :----------------------------------------------------------: |
|    alias     | 指定网络范围内服务别名（同一网络上的其他容器可以使用服务名称或此别名来连接到服务的容器之一） |
| ipv4_address | 加入网络后，指定静态 IPv4 地址（顶层网络相应配置必须具有 ipam 块，其子网配置覆盖每个静态地址） |
| ipv6_address | 静态 IPv6（必须设置 enable_ipv6 且 2.x 的版本的 Compose 当前在集群模式下不起作用） |

###### pid

```yaml
pid: "host"
```

设置主机的 PID 模式。将打开容器和主机操作系统之间的 PID 地址空间共享。以此标志启动的容器可以访问和操作裸机名称空间中的其他容器

###### ports

* 短语法

    ```yaml
    ports:
      - "3000"
      - "3000-3005"
      - "8000:8000"
      - "9090-9091:8080-8081"
      - "49100:22"
      - "127.0.0.1:8001:8001"
      - "127.0.0.1:5000-5010:5000-5010"
      - "6060:6060/udp"
      - "12400-12500:1240"
    ```

* 长语法

    ```yaml
    ports:
      - target: 80 # 容器内端口
        published: 8080 # 公开端口
        protocol: tcp # 协议 tcp/udp
        mode: host # host 在每个节点上发布主机端口，ingress 以 swarm 模式来负载均衡
    ```

    3.2 版本支持长语法

暴露服务端口

###### restart

```yaml
restart: "no"
```

指定服务重启策略，支持 no（默认），以 swarm 模式运行时会忽略该选项

###### secrets

* 短语法

    ```yaml
    version: "3.8"
    services:
      redis:
        image: redis:latest
        deploy:
          replicas: 1
        secrets:
          - my_secret
          - my_other_secret
    secrets:
      my_secret:
        file: ./my_secret.txt
      # 必须已使用 docker secret create 命令或其他服务部署进行了定义，如果外部机密不存在，堆栈部署将失败
      my_other_secret: 
        external: true
    ```

    仅指定密码名称，将授予容器访问密码权限，并将其安装在容器内的 */run/secrets/<secret_name>*（源名称和容器内名称都设置为机密名称）

* 长语法

    ```yaml
    version: "3.8"
    services:
      redis:
        image: redis:latest
        deploy:
          replicas: 1
        secrets:
          - source: my_secret  # 秘密名称
            target: redis_secret # 在容器 /run/secrets 路径下挂载的名称，未指定为 source 名称
            uid: '103' # 指定容器中密码文件的用户和组，未指定默认为 0
            gid: '103'
            mode: 0440 # 8 进制指定文件权限
    secrets:
      my_secret:
        file: ./my_secret.txt
      my_other_secret:
        external: true
    ```

按服务授予 secrets 配置，按服务授予对密码数据的访问权限

* 该密码必须已经存在或在顶级的 secrets 配置中定义，否则 swarm 部署将失败

###### security_opt

```yaml
security_opt:
  - label:user:USER
  - label:role:ROLE
```

覆盖每个容器的默认标签，swarm 模式时将忽略该选项

###### stop_grace_period

```yaml
stop_grace_period: 1m30s
```

指定在发送 SIGKILL 之前，如果容器无法处理 SIGTERM（或使用 stop_signal 指定的任何停止信号）时，尝试停止该容器要等待的时间，为持续时间

默认情况下，发送 SIGKILL 之前等待 10s 后

###### stop_signal

```yaml
stop_signal: SIGUSR1
```

设置停止容器的替代信号，默认为 SIGTREM

###### sysctls

```yaml
sysctls:
  net.core.somaxconn: 1024
  net.ipv4.tcp_syncookies: 0
sysctls:
  - net.core.somaxconn=1024
  - net.ipv4.tcp_syncookies=0
```

设置容器的内核参数，只能使用内核中已命名空间的 sysctl，不支持修改容器内的主机系统 sysctls。以 swarm 模式运行时，docker 引擎版本最低为 19.03

###### tmpfs

```yaml
tmpfs: 
 -/run
 - type: tmpfs
     target: /app
     tmpfs:
       size: 1000 # 大小字节，默认无限制
```

3.6 版本开始支持，在容器内挂载一个临时文件系统。3 - 3.5 版本的 swarm 模式运行时将忽略该选项

###### ulimits

```yaml
ulimits: # 最大进程数 65535 
  nproc: 65535
  nofile: # 文件句柄数软限制 20000 硬限制 40000
    soft: 20000
    hard: 40000
```

指定容器容器的 ulimits 限制值

###### users_mode

```yaml
userns_mode: "host"
```

如果 dockerd 配置了用户命名空间，则禁用此服务的用户命名空间，在 swarm 模式运行时会忽略该选项

###### volumes

* 短语法

    ```yaml
    volumes:
      # Just specify a path and let the Engine create a volume
      - /var/lib/mysql
    
      # Specify an absolute path mapping
      - /opt/data:/var/lib/mysql
    
      # Path on the host, relative to the Compose file
      - ./cache:/tmp/cache
    
      # User-relative path
      - ~/configs:/etc/configs/:ro
    
      # Named volume
      - datavolume:/var/lib/mysql
    ```

* 长语法

    ```yaml
    version: "3.8"
    services:
      web:
        image: nginx:alpine
        ports:
          - "80:80"
        volumes:
          - type: volume
            source: mydata
            target: /data
            volume:
              nocopy: true  # 创建券时禁用从容器复制数据的标志
          - type: bind
            source: ./static
            target: /opt/app/static
    
    networks:
      webnet:
    
    volumes:
      mydata:
    ```

    3.2 版本支持

    *长语法支持的子选项*

    |    选项     |                             含义                             |
    | :---------: | :----------------------------------------------------------: |
    |    type     |                            volume                            |
    |   source    | 挂载源，主机上用于绑定挂载的路径或顶级 volumes 中定义的卷名称 tmpfs 卷不支持该选项 |
    |   target    |                    安装了券的容器中的路径                    |
    |  read_only  |                         设置为只读券                         |
    |    bind     |                       配置其他绑定选项                       |
    |   volume    |                        配置其他券选项                        |
    |    tmpfs    |                     配置其他 tmpfs 选项                      |
    |    size     |               tmpfs 挂载的大小（以字节为单位）               |
    | consistency | 装载的一致性要求（consistent 一致，cached 主机为权威，delegated 容器为权威） |

挂载主机路径或命名卷，可以直接在单个服务中定义 volumes，而无需预先在顶级 volumes 中定义。如果要在多个服务间重用卷，则必须在顶级的 volumes 

* 使用长语法时，源文件夹必须提前创建
* 使用短语法时，会自动创建不存在的文件夹

##### volumes

顶级券配置，服务可以引用配置的 key（key 值可以为空，此时使用默认驱动程序）

###### dirver

```yaml
driver: local
```

指定券驱动程序，如果驱动不可用，启动服务时将出错

###### driver_opts

```yaml
volumes:
  example:
    driver_opts:
      type: "nfs"
      o: "addr=10.40.0.199,nolock,soft,rw"
      device: ":/docker/example"
```

配置驱动的选项

###### external

```yaml
ersion: "3.8"

services:
  db:
    image: postgres
    volumes:
      - data:/var/lib/postgresql/data

volumes:
  data:
    external: true
```

指定券是否为外部创造，为 true 时使用 up 启动服务不会创建券，如果外部不存在该券，则启动失败。在 swarm 模式下则会创建不存在的外部券（在本地节点上创建券）

###### labels

```yaml
labels:
  - "com.example.description=Database volume"
  - "com.example.department=IT/Ops"
  - "com.example.label-with-empty-value"
```

指定券的元信息

###### name

```yaml
version: "3.8"
volumes:
  data:
    name: my-app-data
```

3.4 版本支持，为此券设置一个自定义名称，名称字段可用于引用包含特殊字符的券，该名称按原样使用，

##### networks

顶级网络配置

###### dirver

```yaml
driver: overlay
```

驱动程序，支持 host、none、bridge、overlay。单节点默认 bridge，swarm 模式默认为 overlay

```yaml
services:
  web:
    ...
    networks:
      nonet: {}

networks: # 使用主机的网络栈 host 或 none，定义一个别名来让服务调用
  nonet:
    external: true 
    name: none
```

###### driver_opts

```yaml
driver_opts:
  foo: "bar"
  baz: 1
```

传递给驱动的选项

###### attachable

```yaml
networks:
  mynet1:
    driver: overlay
    attachable: true
```

3.2 版本添加，仅支持 overlay 类型网络，如果为 true，则除了服务之外，独立容器还可以连接到该网络。如果独立容器连接到覆盖网络，则它可以与附加到该网络的其他容器和服务通信

###### enable_ipv6

启用 IPv6 网络，仅支持 version2 版本 compose，swarm 模式不支持该指令

###### ipam

```yaml
ipam:
  driver: default # 指定驱动程序
  config:
    - subnet: 172.28.0.0/16  # 指定子网段
```

指定自定义 IPAM 配置。gateway 等其他配置，仅支持 v2 版本 compose

###### internal

```yaml
internal: true
```

默认情况下，覆盖网络还将连接到桥接外部网络以提供外部连接，为 true 时创建外部隔离的覆盖网络（仅提供网络范围内的节点服务通信）

###### labels

```yaml
labels:
  com.example.description: "Financial transaction network"
  com.example.department: "Finance"
  com.example.label-with-empty-value: ""
```

指定网络元信息

###### external

```yaml
networks:
  outside:
    external: true # 指定外部网络
  outsidenet:
    external: # 指定外部网络名称,其他服务可以引用该名称
      name: actual-name-of-network
```

指定外部网络，使用 up 启动时，如果网络不存在，则会启动失败

###### name

```yaml
version: "3.8"
networks:
  network1:
    name: my-app-net
```

为网络指定自定义名称。名称按原样使用

##### configs

顶级 configs 选项，对应一级 config 命令

```yaml
configs:
  my_first_config:
    file: ./config_data # 指定文件内容创建配置
  my_second_config:
    external: true  # 指定外部配置
  redis_config:
    external:
      name: redis  # 指定别名 
```

##### secrets

顶级 secrets 选项，对应一级 secret 选项

```yaml
secrets:
  my_first_secret:
    file: ./secret_data
  my_second_secret:  # 3.5 版本
    external: true  # 外部配置
    name: redis_secret # 别名 
  mysql_secret:  # 3.4 版本
    external:
      name: mysql_secret
```

##### 变量

支持使用环境变量，compose 使用 运行时的 shell 环境中的变量值

```yaml
db:
  image: "postgres:${POSTGRES_VERSION}"  # 如果未设置环境变量，compose 替换为空字符串
```

可以使用 compose 自动查找的 *.env* 文件来为环境变量设置默认值，在 shell 中设置的值将覆盖 *.env* 文件中设置的值（*.env* 文件仅支持 compose up 命令，swarm 模式不支持 *.env* 文件）

##### 扩展字段

3.4 版本添加，可以使用扩展字段，在 compose 文件的顶级目录，以 x- 口头的。

3.7 开始，扩展字段可以在 services、volumes、networks、configs、secrets 顶级目录下使用。

* 使用 yaml 锚点

    ```yaml
    version: '3.4'
    x-logging:  		# 使用 yaml 锚点将其插入到资源定义中
      &default-logging    # 声明锚点
      options:
        max-size: '12m'
        max-file: '5'
      driver: json-file
    
    services:
      web:
        image: myapp/web:latest
        logging: *default-logging  # 引用锚点
      db:
        image: mysql:latest
        logging: *default-logging
    ```

* 使用 yaml 合并类型

    ```yaml
    version: '3.4'
    x-volumes:
      &default-volume
      driver: foobar-storage
    
    services:
      web:
        image: myapp/web:latest
        volumes: ["vol1", "vol2", "vol3"]
    volumes:
      vol1: *default-volume
      vol2:
        << : *default-volume
        name: volume02
      vol3:
        << : *default-volume
        driver: default
        name: volume-local
    ```

##### docker-compose 命令

###### docker-compose

```shell
docker-compose [-f arg...] [options] [COMMAND] [ARGS...]
docker-compose -h | --help
```

*options*

|           options           |                           comment                            |
| :-------------------------: | :----------------------------------------------------------: |
|      `-f, --file FILE`      |                             声明                             |
|  `-p, --project-name Name`  |         指定项目名称，默认将使用所在目录名作为项目名         |
|         `--verbose`         |                         输出调试信息                         |
|       `-v, --version`       |                        打印版本并退出                        |
|      `-H, --host HOST`      |                   指定 docker deamon 地址                    |
|           `--tls`           |         启用 TLS，如果指定 `--tlsverify` 则默认开启          |
|    `--tlscacert CA_PATH`    |                     信任的 TLS CA 的证书                     |
| `--tlscert CLENT_CERT_PATH` |                    客户端使用的 TLS 证书                     |
|   `--tlskey TLS_KEY_PATH`   |                      TLS 的私钥文件路径                      |
|        `--tlsverify`        |                    使用 TLS 校验连接对方                     |
|   `--skip-hostname-check`   |                不使用 TLS 证书校验对方主机名                 |
| `--project-directory PATH`  |          指定工作目录，默认为 Compose 文件所在路径           |
|      `--compatibility`      | 如果设置，Compose 将尝试转换密钥，在v3文件中将其替换为非Swarm等效文件 |
|      `--env-file PATH`      |                 什么 env 文件，默认当前路径                  |

###### build

```shell
docker-compose build [options] [--build-arg key=val...] [SERVICE...]
```

构建镜像

*options*

|         选项          |                             含义                             |
| :-------------------: | :----------------------------------------------------------: |
| `--build-arg key=val` |                        设置构建时变量                        |
|     `--compress`      |                   使用 gzip 压缩构建上下文                   |
|     `--force-rm`      |               始终删除构建过程中产生的中间容器               |
|  `-m, --memory MEM`   |                      设置构建容器的内存                      |
|     `--no-cache`      |                     构建镜像时不使用缓存                     |
|       `--no-rm`       |                   构建成功后不删除中间容器                   |
|     `--parallel`      |                         并行构建镜像                         |
|  `--progress string`  | 设置进度输出类型（auto、plain、tty）实验特性，启用 `COMPOSE_DOCKER_CLI_BUILD=1` |
|       `--pull`        |                     始终拉取镜像最新版本                     |
|     `-q,--quiet`      |                     不在标准输出打印结果                     |

###### config

```shell
docker-compose config [options]
```

*options*

|           选项            |            描述             |
| :-----------------------: | :-------------------------: |
| `--resolve-image-digests` |        声明图片摘要         |
|    `--no-interpolate`     |       不插入环境变量        |
|       `-q, --quiet`       |         验证并退出          |
|       `--services`        | 打印 service 信息，一行一个 |
|        `--volumes`        |    打印 volume 一行一个     |
|       `--hash="*"`        |      打印服务 hash 值       |

###### down

```shell
docker-compose down [options]
```

停止所有容器，并删除容器、网络、卷、和启动服务中创建的镜像。默认只移除容器、网络（不会删除外部定义的网络和卷）

|          选项           |                      含义                       |
| :---------------------: | :---------------------------------------------: |
|      `--rmi type`       | 删除图像 all 删除所有服务图像，local 仅删除本地 |
|     `-v, --volumes`     |          删除在 volumes 中声明的命名卷          |
|   `--remove-orphans`    |      删除 compose file 中未定义服务的容器       |
| `-t, --timeout TIMEOUT` |             声明关闭时间，默认 10s              |

###### events

```shell
docker-compose events [options] [SERVICE...]
```

实时订阅服务状态

*options*

|   选项   |        含义        |
| :------: | :----------------: |
| `--json` | 状态流为 json 格式 |

###### exec

```shell
docker-compose exec [options] [-e KYE=VAL...] SERVICE COMMAND [ARGS...]
```

在运行的容器中执行一条命令

|        选项         |                   含义                    |
| :-----------------: | :---------------------------------------: |
|   `-d, --detach`    |         在后台以分离模式运行命令          |
|   `--privileged`    |             赋予进程更多特权              |
|  `-u, --user USER`  |               执行命令用户                |
|        `-T`         |     禁用 tty 分配，默认情况下分配 tty     |
|   `--index=index`   | 容器索引，如果服务实例有多个容器，默认 1  |
| `-e, --env KEY=VAL` | 设置环境变量，可以设置多个，1.25 版本支持 |
| `-w, --workdir DIR` |            指定命令的工作目录             |

###### help

```shell
docker-compose help [COMMAND]
```

###### images

```shell
docker-compose images [options] [SERVICE...]
```

列出创建容器使用的镜像

###### kill

```shell
docker-compose kill [options] [SERVICE...]
```

强制停止服务容器

|    选项     |              含义              |
| :---------: | :----------------------------: |
| `-s SIGNAL` | 发送到容器的信号，默认 SIGKILL |

###### logs

```
docker-compose logs [options] [SERVICE...]
```

查看容器输出

|        选项        | 含义 |
| :----------------: | :--: |
|    `--no-color`    |      |
|   `-f, --follow`   |      |
| `-t, --timestamps` |      |
|   `--tail="all"`   |      |



###### pause

###### port

###### ps

###### pull

###### push

###### restart

###### rm

###### run

###### scale

###### start

###### stop

###### top

###### unpause

###### up

###### version

#### Machine

#### SWARM

##### 配置服务发现

