## docker 三剑客

### Docker -Machine

负责实现对 Docker 运行环境进行安装和管理，在管理多个 Docker 环境时，使用 Machine 很高效，Machine 的定位是：在本地或云环境中创建 docker 主机，基本功能包含：在指定节点或平台上安装 docker 引起，配置其为可用的 docker 环境，集中管理（包括启动，查看等）所安装的 docker 环境

#### 使用

Docker Machine 通过多种后端驱动来管理不同的资源，包括虚拟机、本地主机和云平台，`-d` 选项可以选择支持的驱动类型。默认情况下，所有的客户端配置数据会自动存放在 `~/.docker/machine/machines/` 路径下，该路径下内容仅为客户端侧的配置和数据，删除其下内容不会影响到已创建的 docker 环境

* 虚拟机

  将启动一个全新的虚拟机，并安装 docker 环境

  *virtualbox驱动*

  ```shell
  # 通过 virtualbox 驱动支持本地启动一个虚拟机环境，并配置 docker 主机
  docker-machine create --driver=virtualbox test
  # 查看访问所创建 docker 环境所需要的配置信息
  docker-machine env test
  # 停止
  docker-machine stop test
  ```

  *hyper-v驱动*

  必须使用管理员且必须要有一个虚拟交换机才能使用

  ```shell
  # 使用 hyper-v 驱动并引用创建的虚拟交换机
  docker-machine create -d hyperv --hyperv-virtual-switch <NameOfVirtualSwitch> <nameOfNode>
  ```

  

* 本地主机

  这种驱动适合主机操作系统和 SSH 服务已安装，需要对其安装 docker 引擎，首先确保本地主机可以通过 user 账号的 key 直接 ssh 到目标主机，使用 `generic` 类型驱动，注册一台 docker 主机

  ```shell
  # 命名为 test
  docker-machine create -d generic --generic-ip-address=10.0.100.102 --generic-ssh-user=user test
  ```

#### 客户端命令

`docker-machine <COMMAND> -h`

|       命令        |                             说明                             |
| :---------------: | :----------------------------------------------------------: |
|      active       |                查看当前激活状态的 docker 主机                |
|      config       |               查看到激活 docker 主机的连接信息               |
|      create       |                     创建一个 docker 主机                     |
|        env        |               显示连接到某个主机需要的环境变量               |
|      inspect      |            以 json 格式输出指定 docker 主机的信息            |
|        ip         |                  获取指定的 docker 主机地址                  |
|       kill        |                  直接杀手指定的 docker 主机                  |
|        ls         |                      列出所有管理的主机                      |
| regenerate-create |               为某个主机重新生成 TLS 认证信息                |
|      restart      |                     重启指定 docker 主机                     |
|        rm         |           删除某台 docker 主机，对应虚拟机会被删除           |
|        scp        |        在 docker 主机，本地主机间通过scp命令复制文件         |
|        ssh        |                 通过 ssh 连接主机，执行命令                  |
|       start       | 启动一个指定的 docker 主机，如果对象是虚拟机，该虚拟机将被启动 |
|      status       | 获取指定 docker 主机状态：running，paused，saved，stopped，stopping，starting，error |
|       stop        |                     停止一个 docker 主机                     |
|      upgrade      |              将指定主机的 docker 版本更新为最新              |
|        url        |                获取指定 docker 主机的监听 URL                |
|       help        |                             帮助                             |

* active

  `docker-machine active[arg....]`

  支持`·-timeout，-t"10"`选项，代表超时时间，默认为10s。查看当前激活状态的Docker主机。激活状态意味着当前的 DOCKER_HOST 环境变量指向该主机

* config

  `docker-machine config [options][arg...]`

  支持 `-swarm` 参数，打印 swarm 集群信息，而不是 docker 信息

* create

  `docker-machine create[OPTIONS][arg...]`

  创建一个 docker 主机环境，支持选项：

  `--driver, -d "virtualbox"`: 指定驱动类型

  `-engine-install-url "https://get.docker.com"`: 配置 docker 主机时的安装 URL

  `-engine-opt option`: 以键值对格式指定所创建 docker 引擎参数

  `-engine-insecure-registry option`：以键值对格式指定所创建Docker引擎允许访问的不支持认证的注册仓库服务；
  `-engine-registry-mirror option`：指定使用注册仓库镜像；
  `-engine-label option`：为所创建的Docker引擎添加标签；
  `-engine-storage-driver`：存储后端驱动类型；
  `-engine-env option`：指定环境变量；
  `-swarm`：配置Docker主机加入到Swarm集群中；
  `-swarm-image"swarm：latest"`：使用Swarm时候采用的镜像；
  `-swarm-master`：配置机器作为Swarm集群的master节点；
  `-swarm-discovery`：Swarm集群的服务发现机制参数；
  `-swarm-strategy“spread”`：Swarm默认调度策略；
  `-swarm-opt option`：任意传递给Swarm的参数；
  `-swarm-host"tcp：//0.0.0.0：3376"`：指定地址将监听Swarm master节点请求；
  `-swarm-addr`：从指定地址发送广播加入Swarm集群服务

* env

  `docker-machine env [OPTIONS][arg...]`

  显示连接到某个主机需要的环境变量，支持的选项包括：

  `-swarm`：显示 swarm 集群配置

  `-shell`：指定 shell 环境，默认自动探测

  `-unset`：取消对应环境变量

  `-no-proxy`：添加对象主机地址到 NO_PROXY 环境变量

* inspect

  `docker-machine inspect[options][arg...]`

  支持 `-format,-f`，选项使用指定 go 模板格式化输出

* ls

  `docker-machine ls[options][arg...]`

  支持 `--filter[--filter option --filter option]` 只输出某些 docker 主机，支持过滤器包括正则表达式，驱动类型，swarm 管理节点，状态

  `docker-machine ls --filter state=Stopped`

  `--quiet,-q`：减少无关信息

  `-timeout, -t "10"`: 命令执行超时时间，默认 10s

  `-format, -f`：使用所指定的 Go 模板格式化输出

### Docker  Compose

负责实现对基于 docker 容器的多应用服务的快速编排，定位是：定义和运行多个 docker 容器的应用，它允许用户通过一个单独的 `docker-compose.yml` 模板来定义一组相关联的应用容器为一个服务栈。compose 中的概念：

* 任务（task）

  一个容器被称为一个任务，任务拥有独一无二的 ID，在同一个服务中的多个任务序号依次递增

* 服务（service)

  某个相同应用镜像的容器副本集合，一个服务可以横向扩展为多个容器实例

* 服务栈（stack）

  由多个服务组成，相互配合完成特定业务，一般由一个 docker-compose.yml 文件定义

compose 的默认管理对象是服务栈，通过子命令对栈中的多个服务进行便捷的生命周期管理

#### 模板文件

是 compose 的核心，大部分指令与 `docker [container]create|run` 相关参数的含义类似，默认的模板文件名称为 docker-compose.yml，格式为 YAML 格式，最新版本为 v3

`v1` 的 compose 文件结构为每个顶级元素为服务名称，次级元素为服务容器的配置信息，`v2` 和 `v3` 扩展了 compose 的语法，同时尽量保持跟旧版本兼容：可以声明网络和存储信息，添加了版本信息，将所有的服务放到 services 根下面，每个服务都必须通过 image 指令指定镜像或 build 指令（需要 Dockerfile）等来自动构建生成镜像，如果使用 build 指令，在 Dockerfile 中设置的选项（如：CMD，EXPOSE，VOLUME，ENV等）将会自动被获取，无须在 docker-compose.yml 中再次设置

从 1.5 开始，compose 模板文件支持动态读取主机的系统环境变量

```yaml
db:
	# compose 文件将从运行它的环境中读取 ${MONGO_VERSION} 的值，不指定默认 3.2
	image: "mongo:${MONGO_VERSION-3.2"
```

*compose模板文件主要命令*

|       命令        |                             功能                             |
| :---------------: | :----------------------------------------------------------: |
|       build       |                  指定 dockerfile 所在文件夹                  |
| cap_add,cap_drop  |              指定容器的内核能力（capacity）分配              |
|      command      |                 覆盖容器启动后默认执行的命令                 |
|   cgroup_parent   |   指定父cgroup组，将继承该组资源限制，暂不支持 swarm 模式    |
|  container_name   |              指定容器名称，暂不支持 swarm 模式               |
|      devices      |             指定设备映射关系，不支持 swarm 模式              |
|    depends_on     |                  指定多个服务之间的依赖关系                  |
|        dns        |                      自定义 DNS 服务器                       |
|    dns_search     |                       配置 DNS 搜索域                        |
|    dockerfile     |             指定额外的编译镜像的 Dockefile 文件              |
|    entrypoint     |                   覆盖容器中默认的入口命令                   |
|     env_file      |                     从文件中获取环境变量                     |
|    environment    |                         设置环境变量                         |
|      expose       |        暴露端口，但不映射到宿主机，只被连接的服务访问        |
|      extends      |                   基于其他模板文件进行扩展                   |
|  external_links   |             链接到 docker-compose.yml 外部的容器             |
|    extra_hosts    |                 指定额外的 host 名称映射信息                 |
|    healthcheck    |                  指定检测应用健康状态的机制                  |
|       image       |                    指定为镜像名称或镜像ID                    |
|     isolation     |                     配置容器隔离的机制 v                     |
|      labels       |                 为容器添加 docker 元数据信息                 |
|       links       |                    链接到其他服务中的容器                    |
|      logging      |                       跟日志相关的配置                       |
|   network_mode    |                         设置网络模式                         |
|     networks      |                         所加入的网络                         |
|        pid        |                  跟主机系统共享进行命名空间                  |
|       ports       |                         暴露端口信息                         |
|      secrets      |                      配置应用的秘密数据                      |
|   security_opt    |   指定容器模板标签机制的默认属性（用户，角色，类型，级别）   |
| stop_grace_period | 指定应用停止时，容器的优雅停止期限，过期后通过sigkill强制退出，默认10s |
|    stop_signal    |                      指定停止容器的信号                      |
|      sysctls      |          配置容器内的内核参数，暂不支持 swarm 模式           |
|      ulimits      |                  指定容器的 ulimits 限制值                   |
|    userns_mode    |          指定用户命名空间模式，暂不支持 swarm 模式           |
|      volumes      |                     数据卷所挂载路径设置                     |
|      restart      |                         指定重启策略                         |
|      deploy       | 指定部署和运行时的容器相关配置，只在 swarm 模式下生效，只支持docker stack deploy 命令部署 |

* build

  指定 dockerfile 所在文件夹的路径（可以是绝对路径，或者相对 docker-compose.yml 文件的路径）。compose 将会利用它自动构建应用镜像，然后使用这个镜像

  ```yaml
  version: '3'
  services:
      app:
          build: /path/to/build/dir
  ```

  指定创建镜像上下文，dockefile 路径，标签，shm 大小，参数和缓存源

  ```yaml
  build:
  	context: /path/to/build/dir
  	dockerfile: Dockerfile-app
  	labels:
  		version: "2.0"
  		released: "true"
  	shm_size: "2gb"
  	args:
  		key: value
  		name: myApp
  	cache_from:
  		- myApp:1.0
  ```

* cap_add，cap_drop

  指定容器的内核能力（capacity）分配

  ```yaml
  # 让容器拥有所有能力
  cap_add:
  	- ALL
  # 去掉 NET_ADMIN 能力
  cap_drop:
  	- NET_ADMIN
  ```

* command

  覆盖容器启动后默认执行的命令，可以为字符串格式或 JSON 数组格式

  ```yaml
  command: echo "hello world"
  command: ["bash", "-c", "echo", "hello world"]
  ```

* configs

  在 docker swarm 模式下，可以通过 configs 来管理和访问非敏感的配置信息，支持从文件读取或外部读取

  ```yaml
  version: "3.3"
  services:
  	app:
  		image: myApp:1.0
  		deploy:
  			replicas: 1
  		configs:
  			- file_config
  			- external_config
  ```

* cgroup_parent

  指定父 cgroup 组，将继承该组的资源限制，暂不支持 swarm 模式中使用

  ```yaml
  cgroup_parent: cgroups_1
  ```

* container_name

  指定容器名称，默认将会使用`项目名称_服务名称_序号` 这样的格式，不支持在 swarm 模式中使用

  ```yaml
  container_name: docker-web-container
  ```

  指定容器名称后，该服务将无法进行扩展，docker 不允许多个容器实例重名

* devices

  指定设备映射关系，不支持 swarm 模式

  ```yaml
  devices:
  	- "/dev/ttyUSB1:/dev/ttypUSB0"
  ```

* depends_on

  指定多个服务之间的依赖关系。启动时，会先启动被依赖的服务

  ```yaml
  # 指定依赖于 db 服务
  depends_on: db
  ```

* dns

  自动有 DNS 服务器，可以是一个值或列表

  ```yaml
  dns: 8.8.8.8
  dns:
  	- 8.8.8.8
  	- 9.9.9.9
  ```

* dns_search

  配置 DNS 搜索域，可以是一个值，也可以是一个列表

  ```yaml
  dns_search: example.com
  dns_search:
  	- domain1.example.com
  	- domain2.example.com
  ```

* dockerfile

  指定额外的编译镜像的 dockefile 文件，可以通过该指令来指定

  ```yaml
  dockerfile: Dockerfile-alternate
  ```

  该指令不能跟 image 同时使用

* entrypoint

  覆盖容器中默认的入口命令，也会取消掉镜像中指定的入口命令和默认启动命令

  ```yaml
  entrypoint: python app.py
  ```

* env_file

  从文件中获取环境变量，可以为单独的文件路径或列表。如果通过 `docker-compose -f FILE` 方式来指定 compose 模板文件，则 `env_file` 中变量的路径会基于模板文件路径。如果有变量名称与 `environment` 指令冲突，则按照惯例，以后者为准

  ```yaml
  env_file: .env
  env_file:
  	- ./commom.env
  	- ./apps/web.env
  	- ./opt/secrets.env
  ```

  环境变量文件中每一行必须符合格式，支持 # 开头的注释行

* environment

  设置环境变量，可以使用数组或字典两种格式，只给定名称的变量会自动获取运行Compose主机上对应变量的值，可以用来防止泄露不必要的数据。例如：

  ```yaml
  environment:
  	- RACK_ENV=development
  	- SESSION_SECRET
  # 或
  environment:
  	RACK_ENV: development
  	SESSION_SECRET:
  ```

  如果变量名称或值中用到 true|false，yes|no 表达布尔含义的词汇，最好放到引号里，避免 YAML 自动解析某些内容为对应的布尔语义

* expose

  暴露端口，但不映射到宿主机，只被连接的服务访问，仅可以指定内部端口为参数

  ```yaml
  expose:
  	- "3000"
  	- "8000"
  ```

* extends

  基于其他模板文件进行扩展，假定已有 webapp 服务，定义一个基础模板文件 common.yml

  *common.yml*

  ```yaml
  webapp:
  	build: ./webapp
  	environment:
  		- DEBUG=false
  		- SEND_ENAILS=false
  ```

  *development.yml* 

  ```yaml
  # 使用 common.yml 中的 webapp 服务进行扩展
  web:
  	extends:
  		file: common.yml
  		service: webapp
      ports:
      	- "8000:8000"
      links:
      	- db
      environment:
      	- DEBUG=true
  db:
  	image: postgres
  ```

  避免出现循环依赖，extends 不会继承 links 和 volumes_from 中定义的容器或数据卷资源。一般情况下，推荐在基础模板中只定义一些可以共享的镜像和环境变量，在扩展模板中具体指定应用变量，链接，数据卷等信息

* external_links

  链接到 docker-compose.yml 外部的容器，参数跟 links 类似

  ```yaml
  external_links
  	- redis_1
  	- project_db_1: mysql
  	- project_db_1: postgresql
  ```

* extra_hosts

  类似 dockers 中的 `--add-host` 参数，指定额外的 host 名称映射信息

  ```yaml
  extra_hosts:
  	- "googledns:8.8.8.8"
  	- "dockerhub:52.1.157.61"
  ```

  会在启动后的服务容器中 `/etc/hosts` 文件中添加

  ```
  8.8.8.8 googledns
  52.1.157.61 dockerhub
  ```

* healthcheck

  指定检测应用健康状态的机制，包括检测方法（test)、间隔（interval）、超时（timeout）、重试次数（retries)、启动等待时间（start_period）等

  ```yaml
  healthcheck:
  	test: ["CMD", "curl", "-f", "http://localhost:8080"]
  	interval: 30s
  	timeout: 15s
  	retries: 3
  	start_period: 30s
  ```

* image

  指定为镜像名称或镜像ID，如果镜像本地未缓存，会拉取该镜像

  ```yaml
  image: ubuntu
  ```

* isolation

  配置容器隔离的机制，包括 default、process 和 hyperv

* labels

  为容器添加 docker 元数据（metadata）信息

  ```yaml
  labels:
  	com.startupteam.description: "webapp for a startup team"
  	com.startupteam.department:	"devops department"
  	com.startupteam.release: "rc3 for v1.0"
  ```

* links

  links 命令属于旧的用法，可能在后续版本中被移除。链接到其他服务中的容器。使用服务名称（同时作为别名）或服务名称：服务器别名格式

  ```yaml
  links:
  	- db
  	- db:database
  	- redis
  ```

  使用的别名将会自动在服务器容器中的 `/etc/hosts` 里创建，被链接容器中相应的环境变量也将被创建

* logging

  日志相关配置，包含一系列子配置

  ```yaml
  logging:
  	driver: "syslog"
  	options:
  		syslog-address: "tcp://192.168.0.42:123"
  # 或
  logging:
  	driver: "json-file"
  	options:
  		max-size: "1000k"
  		max-file: "20"
  ```

* network_mode

  设置网络模式。使用 docker client 的 `--net` 参数一样的值

  ```yaml
  network_mode: "none"
  network_mode: "bridge"
  network_mode: "host"
  network_mode: "service:[service name]"
  network_mode: "container:[name or id]"
  ```

* networks

  所加入的网络。需要在顶级的 networks 字段中定义具体的网络信息

  ```yaml
  # 指定 web 服务的网络为 web_net, 并添加服务在网络中别名为 web_app
  services:
  	web:
  		networks:
  			web_net:
  				aliases: web_app
  			ipv4_address: 172.16.0.10
  	networks:
      	web_net:
      		driver: bridge
      		enable_ipv6: true
      		ipam:
      			driver: default
      			config:
      				subnet: 172.16.0.0/24
  ```

* pid

  跟主机系统共享进行命名空间，打开该选项的容器之间，以及容器和宿主机系统之间可以通过进程ID来相互访问和操作

  ```yaml
  pid: "host"
  ```

* ports

  暴露端口信息

  使用宿主：容器（host:container) 格式，或仅指定容器端口

  ```yaml
  ports:
  	- "3000"
  	- "8000:8000"
  	- "127.0.0.1:8001:8011"
  # 或
  ports:
  	- target: 80
  	  published: 8080
  	  protocol: tcp
  	  mode: ingress
  ```

* secrets

  配置应用的秘密数据，可以指定来源密码，挂载后名称，权限

  ```yaml
  version: "3.1"
  services:
  	web:
  		image: webapp:stable
  		deploy:
  			replicas: 2
  		secrets:
  			- source: web_secret
  			  target: web_secret
  			  uid: '103'
  			  git: '103'
  			  mode: 0444
  		secrets:
  			web_secret:
  				file: ./web_secret.txt
  ```

* security_opt

  指定容器模板标签（label）机制的默认属性（用户，角色，类型，级别）

  ```yaml
  security_opt:
  	- label:user:USER
  	- label:role:ROLE
  ```

* sysctls

  配置容器内的内核参数，swarm 模式不支持

  ```yaml
  # 指定连接数为 4096 和开启 TCP 的 syncookies
  sysctls:
  	net.core.somaxconn: 4096
  	net.ipv4.tcp_syncookies: 1
  ```

* ulimits

  指定容器的 ulimits 限制值

  ```yaml
  # 最大进程数65535文件句柄数20000 
  ulimits:
  	nproc: 65535
  	nofile:
  		soft: 20000
  		hard: 40000
  ```

* userns_mode

  指定用户命名空间模式，swarm 模式不支持

  ```yaml
  # 使用主机上的用户命名空间
  userns_mode: "host"
  ```

* volumes

  数据卷所挂载路径设置。可以设置宿主机路径（host:container）可以指定访问模式（host:container:ro)，支持 driver、driver_opts、external、labels、name 等子配置，该指令支持相对路径

  ```yaml
  volumes:
  	- /var/lib/mysql
  	- cache/:/tmp/cache
  	- ~/configs:/etc/configs/:ro
  # 详细模式
  volumes:
  	- type: volume
  	  source: mydata
  	  target: /data
  	  volume:
  	  	  mocopy: true
  volumes:
  	mydata:
  ```

* restart

  指定重启策略，可以为 no（不重启）、always（总是）、on-failure（失败时）、unless-stopped（除非停止）。swarm 模式下要使用 restart_policy，在生产环境中推荐配置为 always 或 unless-stopped

  ```yaml
  restart: unless-stopped
  ```

* deploy

  指定部署和运行时的容器相关配置，该命令只在 swarm 模式下生效，且只支持 docker stack deploy 命令部署

  ```yaml
  version: '3'
  services:
  	redis:
  		image: web:stable
  		deploy:
  			replicas: 3
  			update_config:
  				parallelism: 2
  				delay: 10s
               restart_policy:
               	 condition: on-failure
  ```

  deploy 命令中包括 endpoint_mode、labels、mode、placement、replicas、resources、restart_policy、update_config 等配置项

  endpoint_mode

  指定服务端点模式，包括 `vip`  swarm 分配一个前端的虚拟地址，客户端通过该地址访问服务，而无须关系后端的应用容器个数；`dnsrr`，swarm 分配一个域名给服务，用户访问域名时按照轮流属性返回容器地址。

  ```yaml
  deploy:
  	endpoint_mode: vip
  ```

  labels

  指定服务的标签，标签信息不会影响到服务内的容器

  ```yaml
  deploy:
  	labels:
  		description: "this is a web app"
  ```

  mode

  定义容器副本模式，global：每个 swarm 节点上只有一个该应用容器；replicated 整个集群中存在指定份数的应用容器副本，默认值

  ```yaml
  deploy:
  	mode: replicated
      replicas: 3
  ```

  placement

  定义容器放置的限制（constraints）和配置（preferences）。限制可以指定只有符合要求的节点上才能运行该应用容器；配置可以指定容器的分配策略。

  ```yaml
  # 指定集群中 web 应用容器只存在于高安全的节点上，并且在带有 zone 标签的节点上均匀分配
  version: '3'
  services:
  	db:
  		image: web:stable
  		deploy:
  			placement:
  				constraints:
  					- node.labels.security = high
  				preferences:
  					- spread: node.labels.zone
  ```

  replicas

  容器副本模式为默认的 replicated 时，指定副本的个数

  resources

  指定使用资源的限制，包括 CPU、内存资源

  ```yaml
  # 指定应用使用 CPU 份额 10% ~ 25%，内存为 200MB ~ 500MB
  version: '3'
  services:
  	redis:
  		image: web:stable
  		deploy:
  			resources:
  				limits:
  					cpus: '0.25'
  					memory: 500M
  				reservations:
  					cpus: '0.10'
  					memory: 200M
  ```

  restart_policy

  指定容器重启的策略

  ```yaml
  # 指定失败时重启，等待 2s，最多尝试 3次，检测状态的等待时间为 10s
  version: '3'
  services:
  	redis:
  		image: web:stable
  		deploy:
  			restart_policy:
  				condition: on-failure
  				delay: 2s
  				max_attempts: 3
  				window: 10s
  ```

  update_config

  当需要对容器内容进行更新，可使用该配置指定升级的行为。包括每次升级多少个容器（parallelism），升级的延迟（delay），升级失败后的行动（failure_action），检测升级后状态的等待时间（monitor）、升级后容忍的最大失败比例（max_failure_ratio）、升级顺序（order）等

  ```yaml
  # 每次更新两个容器、更新等待 10s，先停止旧容器再升级
  version: "3.4"
  services:
  	redis:
  		image: web:stable
  		deploy:
  			replicas: 2
  			update_config:
  				parallelism: 2
  				delay: 10s
  				order: stop-first
  ```

* 其他指令

  ```yaml
  # 指定容器工作目录
  working_dir: /code
  # 指定容器中搜索域名、主机名、mac 地址
  domainname: your_website.com
  hostname: test
  mac_address: 08-00-27-00-0C-0A
  # 允许运行特权命令
  privileged: true
  ```

#### compose 命令

对于 compose，大部分命令的对象既可以是项目本身，也可以指定为项目中的服务或者容器。如果没有特别的说明，命令对象将是项目，这意味着项目中所有的服务都会受到命令影响

`docker-compose [-f=<arg>...][options][COMMAND][ARGS]`

`-f, --file FILE`：指定使用的 Compose 模板文件，默认为 `docker-compose.yml`，可以多次指定

`-p, --project-name NAME`：指定项目名称，默认将使用所在目录名称作为项目名

`--verbose`：输出调试信息

`-v，--version`：打印版本并退出

`-H, -host HOST`：指定所操作的 Docker 服务地址

`-tls`：启用 TLS，如果指定 `-tlsverify` 则默认开启

`-tlscacert CA_PATH`：信任的 TLS CA 的证书

`-tlscert CLIENT_CERT_PATH`：客户端使用的 TLS 证书

`-tlskey TLS_KEY_PATH`：TLS 的私钥文件路径

`-tlsverify`：使用 TLS 校验连接对方

`-skip-hostname-check`：不使用 TLS 证书校验对方主机名

`-project-directory PATH`：指定工作目录，默认为 Compose 文件所在路径

 *compose 命令*

|  命令   |                             说明                             |
| :-----: | :----------------------------------------------------------: |
|  build  |               构建（重新构建）项目中的服务容器               |
| bundle  | 创建一个可分发的配置包，包括整个服务栈的所有数据，他人可以利用该文件启动服务栈 |
| config  |              校验和查看 compose 文件得配置信息               |
|  down   | 停止服务栈，并删除相关资源，包括容器，挂载卷，网络，创建镜像等，默认情况下只清除所创建得容器和网络资源 |
| events  |                    实时监控容器得事件信息                    |
|  exec   |               在一个运行中的容器内执行给定命令               |
|  help   |                      获得一个命令得帮助                      |
| images  |                     列出服务所创建得镜像                     |
|  kill   |           通过发送 SIGKILL 信息号强制停止服务容器            |
|  logs   |                       查看服务容器输出                       |
|  pause  |                       暂停一个服务容器                       |
|  port   |               打印某个容器端口所映射得公共端口               |
|   ps    |                   列出项目中目前得所有容器                   |
|  pull   |                      拉取服务依赖得镜像                      |
|  push   |                推送服务器创建得镜像到镜像仓库                |
| restart |                       重启项目中得服务                       |
|   rm    |                删除所有（停止状态得）服务容器                |
|   run   |                   在指定服务上执行一个命令                   |
|  scale  |                  设置指定服务运行得容器个数                  |
|  start  |                    启动已经存在得服务容器                    |
|  stop   |             停止已经处于运行状态得容器，但不删除             |
|   top   |                显示服务栈中正在运行得进程信息                |
| unpause |                   恢复处于暂停状态中得服务                   |
|   up    | 尝试自动完成一系列操作：包括构建镜像，创建服务，启动服务，关联服务相关容器 |
| version |                         打印版本信息                         |

* build

  `docker-compose build[options][SERVICE...]`

  构建（重新构建）项目中的服务容器，服务容器一旦构建后，将会带上一个标记名，对于 web 项目中的一个 db 容器，可能是 web_db，可以随时在项目目录下运行 docker-compose build 来重新构建服务，支持选项包含：

  `--force-rm`：强制删除构建过程中的临时容器

  `--no-cache`：构建镜像过程中不适用 cache

  `--pull`：始终尝试通过 pull 来获取更新版本的镜像

  `-m, -memory MEM`：指定创建服务所使用的内存限制

  `-build-arg key=val`：指定服务创建时的参数

* bundle

  `docker-compose bundle[options]`

  创建一个可分发的（Distributed Application Bundle, DAB）配置包，包括整个服务栈的所有数据，他人可以利用该文件启动服务栈

  支持选项包括：

  `-push-images`：自动推送镜像到仓库

  `-o, -output PATH`：配置包的导出路径

* config

  `docker-compose config[options]`

  校验和查看 compose 文件的配置信息，支持选项包括：

  `-resolve-image-digests`：为镜像添加对应的摘要信息

  `-q, -quiet`：只检验格式正确与否，不输出内容

  `-services`：打印出 Compose 中所有的服务信息

  `-volumes`：打印出 compose 中所有的挂载卷信息

* down

  `docker-compose down[options]`

  停止服务栈，并删除相关资源，包括容器、挂载卷、网络、创建镜像等，默认情况下只清除所创建容器和网络资源，支持选项包括：

  `-rmi type`：指定删除镜像的类型，包括 all（所有镜像），local（仅本地）

  `-v, -volumes`：删除挂载数据卷

  `-remove-orphans`：清除孤儿容器，即未在 Compose 服务中定义的容器

  `-t, -timeout TIMEOUT`：指定超时时间，默认为 10s

* event

  `docker-compose events[options][SERVICE...]`

  实时监控容器事件信息，支持选项：

  `-json`：以 json 对象流格式输出事件信息

* exec

  `docker-compose exec[options][-e KEY=VAL]SERVICE COMMAND[ARGS...]`

  在一个运行中的容器内执行给定命令，支持选项包括：

  `-d`：在后台运行命令

  `-privileged`：以特权角色运行命令

  `-u，user USER`：以给定用户身份运行命令

  `-T`：不分配 TTY 伪终端，默认情况下会打开

  `-index=index`：当服务有多个容器实例时指定容器索引，默认为第一个

  `-e，-env KEY=VAL`：设置环境变量

* images

  `docker-compose images[options][SERVICE...]`

  列出服务所创建的镜像，支持选项为：

  `-q`：仅显式镜像的 ID

* kill

  `docker-compose kill[options][SERVICE...]`

  通过发送 SIGKILL 信号来强制停止服务容器，支持选项

  `-s` ：指定发送的信号

  `docker-compose kill -s SIGINT`

* logs

  `docker-compose logs[options][SERVICE...]`

  查看服务器的输出，默认情况下，docker-compose 将对不同的服务输出使用不同的颜色来区分

  `--no-color` ：来关闭颜色输出

  `-f,-follow`：持续跟踪输出日志消息

  `-t，-timestamps`：显式时间戳信息

  `-tail="all"`：仅显示指定行数的最新日志消息

* pause

  `docker-compose pause[SERVICE...]`

  暂停一个服务容器

* port

  `docker-compose port[options]SERVICE PRIVATE_PORT`

  打印某个容器端口所映射的公共端口

  `--protocol=proto`：指定端口协议，tcp 默认

  `--index=index`：如果同一服务存在多个容器，指定命令对象容器的序号（默认为1）

* ps

  `docker-compose ps[options][SERVICE]`

  列出项目中目前所有的容器

  `-q`：只打印容器的 ID 信息

* pull

  `docker-compose pull[options][SERVICE...]`

  拉取服务依赖的镜像

  `--ignore-pull-failures`：忽略拉取镜像过程中错误

* push

  `docker-compose push[options][SERVICE...]`

  推送服务创建的镜像到镜像仓库

  `--ignore-push-failures`：忽略推送镜像过程中的错误

* restart

  `docker-compose restart[options][SERVICE...]`

  重启项目中的服务

  `-t,--timout TIMEOUT`：指定重启前停止容器的超时（默认10s)

* rm

  `docker-compose rm[options][SERVICE...]`

  删除所有（停止状态的）服务容器

  `-f,--force`：强制直接删除，包括非停止状态容器

  `-v`：删除容器所挂载的数据卷

* run

  `docker-compose run[options][-p PORT...][-e KEY=VAL...]SERVICE[COMMAND][ARGS...]`

  在指定服务上执行一个命令

  ```shell
  # 启动一个Ubuntu服务容器，ping docker.com
  docker-compose run ubuntu ping docker.com
  ```

  默认情况下，如果存在关联，则所有关联的服务将会自动被启动，除非这些服务已经在运行中，相关卷，连接都会按照配置自动创建，给定命令将会覆盖原有的自动运行命令；会自动创建端口，以避免冲突

  `--no-deps` 不自动启动关联的容器

  ```shell
  docker-compose run --no-deps web python manage.py shell
  ```

  `-d`：后台运行容器

  `--name NAME`：为容器指定一个名字

  `--entrypoint CMD`：覆盖默认的容器启动指令

  `-e KEY=VAL`：设置环境变量值，可多次使用选项来设置多个环境变量

  `-u, --user=""` ：指定运行容器的用户名或 uid

  `--rm`：运行命令行自动删除容器，d 模式下将忽略

  `-p, --publish=[]`：映射容器端口到本地主机

  `--service-ports`：配置服务端口并映射到本地主机

  `-T`：不分配伪 tty，依赖 tty 的指令将无法运行

* scale

  `docker-compose scale[options][SERVICE=NUM...]`

  设置指定服务运行的容器个数

  `-t, --timeout TIMEOUT`：停止容器时候的超时（默认为 10s），一般的，当指定数目多于该服务当前实际运行容器，将创建并启动容器，反之，将停止容器

  ```powershell
  # 启动3个容器运行web，2个容器运行db
  docker-compose scale web=3 db=2
  ```

* start

  `docker-compose start[SERVICE...]`

  启动已存在的服务容器

* stop

  `docker-compose stop[options][SERVICE...]`

  停止已经处于运行状态的容器，但不删除它

  `-t, --timeout TIMEOUT`：停止容器时候的超时（默认 10s）

* top

  `docker-compose top[SERVICE...]`

  显示服务栈中正在运行的进程信息

* unpause

  `docker-compose unpause[SERVICE...]`

  恢复处于暂停状态中的服务

* up

  `docker-compose up[options][SERVICE...]`

  尝试自动完成包括构建镜像，重新创建服务，启动服务，并关联服务，链接的服务都将会被自动启动，除非已经处于运行状态，大部分可以直接通过该命令来启动一个项目，默认启动的容器都在前台，控制台会打印所有容器的输出信息。默认情况下，如果服务容器已经存在，将会尝试停止容器，然后重新创建（保持使用 volumes-from）挂载的卷，以保证新启动的服务匹配 docker-compose.yml 文件的最新内容。`docker-compose up --no-deps -d <service_NAME>` 来重新创建服务并后台停止旧服务，启动新服务，并不会影响到其所依赖的服务。支持选项：

  `-d`： 在后台运行服务容器
  
  `--no-color`：不使用颜色来区分不同的服务的控制台输出
  
  `--no-deps`：不启动服务所链接的容器
  
  `--force-recreate`：强制重新创建容器，不能与 `--no-recreate` 同时使用
  
  `--no-recreate`：如果容器已经存在，则不重新创建，不能与 `--force-recreate` 同时使用
  
  `--no-build`：不自动构建缺失的服务镜像
  
  `--abort-on-container-exit`：当有容器停止时中止整个服务，与 `-d` 选项冲突
  
  `-t, --timeout TIMEOUT`：停止容器超时（10s），与 `-d` 冲突
  
  `--remove-orphans`：删除服务中未定义的孤儿容器
  
  `--exit-code-from SERVICE`：退出时返回指定服务器容器的退出符
  
  `--scale SERVICE=NUM`：扩展指定服务实例到指定数目

#### Compose 环境变量

环境变量用来配置 Compose 的行为，以 DOCKER_ 开头的变量和用来配置 Dockers 命令行客户端的使用一样

|            变量            |                             说明                             |
| :------------------------: | :----------------------------------------------------------: |
|    COMPOSE_PROJECT_NAME    | 设置 Compose 的项目名称，默认是当前工作目录（docker-compose.yml文件目录）的名字，compose 会为每个启动的容器前添加的项目名称 |
|        COMPOSE_FILE        | 设置docker-compose.yml路径，如果不指定，默认会先查找当前目录 |
|    COMPOSE_API_VERSION     |               指定 API 版本以兼容dockers服务端               |
|        DOCKER_HOST         | 设置docker服务端监听地址，默认 `unix://var/run/docker.sock`  |
|     DOCKER_TLS_VERIFY      |    如果该变量不为空，则与docker服务端交互都通过 TLS 协议     |
|      DOCKER_CERT_PATH      | 配置 TLS 通信所需要的验证文件（ca.pem,cert.pem,key.pem）的路径，默认`~/.docker` |
|    COMPOSE_HTTP_TIMEOUT    |         compose 向 docker 服务器发送请求超时默认60s          |
|    COMPOSE_TLS_VERSION     |                指定与dockers服务交互的TLS版本                |
|   COMPOSE_PATH_SEPARATOR   |           指定 COMPOSE_FILE 环境变量中的路径间隔符           |
|   COMPOSE_IGNORE_ORPHANS   |                       是否忽略孤儿容器                       |
|   COMPOSE_PARALLEL_LIMIT   |              设置 Compose 可以执行进程的并发数               |
| COMPOSE_INTERACTIVE_NO_CLI |        尝试不适用 Docker 命令来执行 run 和 exec 指令         |

### SWARM

#### 基本概念

* swarm 集群 cluster

  为一组被统一管理起来的 docker 主机。集群是 swarm 所管理对象，这些主机通过 docker 引擎的 swarm 模式相互沟通，其中部分主机可能作为管理节点（manager）响应外部请求，其他主机作为工作节点（worker）来实际运行 docker 请求。同一主机即可以是管理节点，同时作为工作节点

* 节点 node

  是 swarm 集群的最小资源单位，每个节点实际上都是一台 docker 主机分为：

  管理节点：负责响应外部对集群的操作请求，并维持集群中资源，分发任务给工作节点，多个管理节点之间通过 raft 协议构成共识，一般推荐每个集群设置 5 或 7 个管理节点；

  工作节点：负责执行管理节点安排的具体任务，默认情况下，管理节点自身同时也是工作节点。每个工作节点上运行 agent 来汇报任务完成情况

* 服务 service

  是 docker 支持复杂容器协作场景工具，一个服务可以由若干个任务组成，每个任务为某个具体的应用。服务还包括对应的存储、网络、端口映射、副本个数、访问配置、升级配置等附加参数。集群中服务分为：

  复制服务（replicated services）模式：默认，每个任务在集群中会有若干副本，这些副本会被管理节点按照调度策略分发到集群中的工作节点上。此模式下可以使用 `-replicas` 参数设置副本数量

  全局服务（global services）模式：调度器将在每个可用节点都执行一个相同的任务。该模式适合运行节点的检查，如监控应用等

* 任务

  是 swarm 集群中的最小调度单位，即一个指定的应用容器，任务从生命周期上可能处于创建（NEW）、等到（PENDING）、分配（ASSIGNED）、接受（ACCEPTED）、准备（PREPARING）、开始（STARTING）、运行（RUNNING）、完成（COMPLETE）、失败（FAILED）、关闭（SHUTDOWN）、拒绝（REJECTED）、孤立（ORPHANED）等不同状态。swarm 集群中的管理节点会按照调度要求将任务分配到工作节点，一旦当某个任务被分配到一个工作节点，将无法被转移到另外的工作节点，swarm 中的任务不支持迁移，无法将任务转移到其他工作节点

* 服务的外表访问

  集群中的服务要被集群外部访问，必须要能允许任务的响应端口映射出来，支持入口负载均衡（ingress load balancing）的映射测试。该模式下，每个服务都会被分配一个公开端口（PublishedPort），该端口在集群中任意节点上都可以访问到，并被保留给该服务，当有请求发送到任意节点的公开端口时，该节点若并没有实际执行服务相关的容器，则会通过路由机制将请求转发给实际执行了服务容器的工作节点

#### 使用 swarm

##### 创建集群

```shell
docker swarm init --advertise-addr <manager id>
```

默认的管理服务端口为 2377，需要能被工作节点访问到，为了支持集群的成员发现和外部服务映射，需要在所有节点上开启 7947 TCP/UDP 端口和 4789 UDP 端口，创建成功后会返回该集群唯一标识 token

* `--advertise-addr[:port]`

  知道服务监听的地址和端口

* `--autolock`

  自动锁定管理服务器的启停操作，对服务进行启动或停止都需要通过口令来解锁

* `--availability string`

  节点的可用性，包括 active、pause、drain 三种，默认为 active

* `--cert-expiry duration`

  根证书的过期时长，默认为 90 天

* `--data-path-addr`

  指定数据流量使用的网络接口或地址

* `dispatcher-heartbeat duration`

  分配组建的心跳时长，默认为 5s

* `--external-ca external-ca`

  指定使用外的证书签名服务地址

* `--force-new-cluster`

  强制创建新集群

* `--max-snapshots uint`

  raft 协议快照保留的个数

* `--snapshot-interval uint`

  raft 协议进行快照的间隔（单位为事务个数），默认为 10000 个事务

* `--task-history-limit int`

  任务历史的保留个数，默认为 5s

##### 使用

```shell
# 查看集群信息 swarm key
docker info 
# 查看集群中节点
docker node ls
# 加入集群
docker swarm join --token 
```

可以使用 docker service 命令操作集群，或使用 `-H ` 选项向指定的 docker 服务端发送 docker 命令来操作集群

```shell
# 查看服务
docker service ls
docker service inspect --pretty <service_name>
# 伸缩服务
docker service scale <service_name>=1
# 删除
docker service rm <service_name>
# 离开集群,最后一个节点离开必须加 -f 选项
docker swarm leave
```

更新集群

docker swarm update [options] 命令来更新集群

* `--autolock`

  启动或关闭自动锁定

* `-cert-expiry duration`

  根证书的过期时长，默认为 90 天

* `--dispatcher-heartbeat duration`

  分配组件的心跳时长，默认为 5s

* `--external-ca external-ca`

  指定使用外部的证书签名服务地址

* `--max-snapshots uint`

  raft 协议快照保留数

* `--snapshot-interval uint`

  raft 协议进行快照的间隔（单位为事务个数）默认 10000 个事务

* `--task-history-limit int`

  任务历史的保留个数，默认为 5

#### 使用服务命令

swarm 提供了对应服务的良好支持，docker 通过 service 命令来管理应用服务，包括以下命令

|   命令   |           说明           |
| :------: | :----------------------: |
|  create  |         创建应用         |
| inspect  |         查看应用         |
|   logs   |    获取服务或任务日志    |
|    ls    |      列出服务的信息      |
|    ps    | 列出服务中包括的任务信息 |
|    rm    |         删除服务         |
| rollback |         回滚服务         |
|  scale   |  对服务进行横向扩展调整  |
|  update  |         更新服务         |

* create

  `docker service create[OPTIONS]IMAGE[COMMAND][ARG]`

  `--config config`

  指定暴露给服务的配置

  `--constraint list`

  应用实例在集群中被放置时的位置限制

  `-d, --detach`

  不等待创建后对应用进行探测即返回

  `--dns list`

  自定义 dns

  `--endpoint-mode string`

  指定外部访问模式，vip（虚地址字段负载）dnsrr（DNS轮训）

  `-e, --env list`

  环境变量列表

  `--health-cmd string`

  进行健康检查的指令

  `-l, --label list`

  指定服务的标签

  `--mode string`

  服务模式，包括 replicated 默认或 global

  `--replicas uint`

  指定实例复制份数

  `--secret secret`

  向服务暴露的秘密数据

  `-u,--user string`

  指定用户信息，UID:GID 

  `-w,--workdir string`

  指定容器中的工作目录位置

* inspect

  `docker service inspect[OPTIONS]SERVIVE[SERVICE...]`

  `-f, --format string` 

  使用 go 模版指定格式化输出

  `--pretty`

  适合阅读格式输出

* logs

  `docker service logs[OPTIONS]SERVICE|TASK`

  `--details`：所有细节

  `-f,--follow`：持续跟随输出

  `--no-resolve`：输出中不将对象的ID映射为名称

  `--no-task-ids`：输出中不包括任务的ID信息

  `--no-trunc`：不截断输出信息

  `--raw`：输出原始格式信息

  `--since string`：输出自指定时间开始的日志

  `--tail string`：只输出给定行数的最新日志信息

  `-t, --timestamps`：打印日志时间戳

* ls

  `docker service ls[options]`

  `-f, --filter filter`：只输出过滤信息

  `--format string`：按照 go 模版输出

  `-q, --quit`：只输出 id

* ps

  `docker service ps[options] service[service...]`

  `-f --filter` 过滤

  `--format string` 格式化

  `-no-resolve` 不将 id 映射为名称

  `--no-trunc` 不截断

  `-q, --quiet` 输出 id

* rollback

  `docker service rollback[options]service`

  `-d, --detach` 执行后返回，不等待服务状态校验完整

  `-q,--quiet` 不显示执行信息

* update

  `docker service update[options]service`

  `--args command` 服务命令参数

  `--config-add config` 增加或更新一个服务的配置信息

  `--config-rm list` 删除一个配置文件

  `--constraint-add list` 增加或更新放置的限制条件

  `--constraint-rm list` 删除一个限制条件

  `-d, --detach` 不等待校验返回

  `--dns-add list` 增加或更新 dns 信息

  `--dns-rm list`：删除 dns 信息

  `--endpoint-mode string` 指定外部访问模式，vip，dnsrr

  `--entrypont command`指定默认入口命令

  `--env-add list` 添加或更新一组环境变量

  `--env-rm list` 删除环境变量

  `--health-cmd string` 进行检查检查

  `--label-add list` 添加或更新一组标签信息

  `--label-rm list` 删除一组标签信息

  `--no-healthcheck` 不进行健康检查

  `--publish-add port` 添加或更新外部端口信息

  `--publish-rm port` 删除端口信息

  `-q, --quiet` 简略信息

  `--read-only` 指定容器文件系统为只读

  `--replicas uint`：指定服务实例的复制份数

  `--rollback` 回滚到上次配置

  `--secret-add secret` 添加或更新秘密数据

  `--secret-rm list` 删除秘密数据

  `--update-parallelism unit` 更新执行并发数

  `-u, --user string` 指定用户信息 UID:GID

  `-w, --workdir string` 指定容器中工作目录信息