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