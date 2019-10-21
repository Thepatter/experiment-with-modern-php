## docker 容器编排

### Docker Machine

负责实现对 Docker 运行环境进行安装和管理，在管理多个 Docker 环境时，使用 Machine 很高效，Machine 的定位是：在本地或云环境中创建 docker 主机，基本功能包含：在指定节点或平台上安装 docker 引起，配置其为可用的 docker 环境，集中管理（包括启动，查看等）所安装的 docker 环境

#### 使用

Docker Machine 通过多种后端驱动来管理不同的资源，包括虚拟机、本地主机和云平台，`-d` 选项可以选择支持的驱动类型。默认情况下，所有的客户端配置数据会自动存放在 `~/.docker/machine/machines/` 路径下，该路径下内容仅为客户端侧的配置和数据，删除其下内容不会影响到已创建的 docker 环境

* 虚拟机

  将启动一个全新的虚拟机，并安装 docker 环境

  ```shell
  # 通过 virtualbox 驱动支持本地启动一个虚拟机环境，并配置 docker 主机
  docker-machine create --driver=virtualbox test
  # 查看访问所创建 docker 环境所需要的配置信息
  docker-machine env test
  # 停止
  docker-machine stop test
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

  

  