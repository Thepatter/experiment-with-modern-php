## docker  关键特性

### docker 网络

docker 服务启动时会首先在主机上自动创建一个 docker0 虚拟网桥（Linux网桥）同时分配一个本地未占用的私有网段中的一个地址给 docker0 接口（典型：网段172.17.0.0/16掩码255.255.0.0）此后启动的容器内的网口也会自动分配一个该网段的地址。

当创建一个 docker 容器的时候，同时会创建一对 `veth pair` 互联接口，当向任一接口发送包时，另外一个接口自动收到相同的包。忽略接口的一端位于容器内，即 `eth0`；另一端在本地并被挂载 `docker0` 网桥（`veth` 开头）。通过这种方式，主机可以与容器通信，容器之间也可以互相通信。如此，docker 就创建了在主机和所有容器之间一个虚拟共享网络

*docker 网络连接原理*

<img src="./Images/docker网络连接原理.png" style="zoom:50%;" />

从 1.7.0 开始 Docker 正式把网络与存储着两部分的功能实现都以插件化形式剥离出来，剥离出来的独立容器网络项目即为 `libnetwork` 项目

#### 容器网络模型

libnetwork 中容器网络模型（Container Networking Model，CNM）十分简洁和抽象，可以让其上层使用网络功能的容器最大程度地忽略底层具体实现，CNM 的典型生命周期：首先，驱动注册自己到网络控制器，网络控制器使用驱动类型，来创建网络；然后再创建的网络上创建接口；最后把容器连接到接口上即可。销毁过程则正好相反，先把容器从接入口上卸载，然后删除接入口和网络即可

*CNM的典型生命周期*

<img src="./Images/CNM典型生命周期.png" style="zoom:50%;" />

目前 CNM 支持的驱动类型有四种：Null、Bridge、Overlay、Remote

* Null：不提供网络服务，容器启动后无网络连接
* Bridge：传统默认用 Linux 网桥和 Iptables 实现的单机网络
* Overlay：用 vxlan 隧道实现的跨主机容器网络
* Remote：扩展类型，预留给外部实现的方案

在 `libnetwork` 支持下，Docker 网络相关操作都作为 network 子命令出现，包括以下命令：

* create 创建一个网络

  Docker 内置了 bridge（默认使用）和 overlay 两种驱动，分别支持单主机和多主机场景。Docker 服务在启动后，会默认创建一个 bridge 类型的网桥 bridge。不同网络之间默认相互隔离。

  `docker network create[OPTIONS]NETWORK`

  `-attachable[=false]`：支持手动容器挂载

  `-aux-address=map[]`：辅助的 IP 地址

  `-config-from=""`：从某个网络复制配置数据

  `-config-only[=false]`：启用仅可配置模式

  `-d,-driver="bridge"`：网络驱动类型，如 bridge 或 overlay

  `-gateway=[]`：网关地址

  `-ingress[=false]`：创建一个 Swarm 可路由的网状网络用于负载均衡，可将对某个服务的请求自动转发给一个合适的副本

  `-internal[=false]`：内部模式，禁止外部对所创建网络的访问

  `-ip-range=[]`：指定分配 IP 地址范围

  `-ipam-driver="default"`：IP 地址管理的插件类型

  `-ipam-opt=map[]`：IP地址管理插件的选项

  `-ipv6[=false]`：支持 IPv6 地址

  `-label value`：为网络添加元标签信息

  `-o,-opt=map[]`：网络驱动所支持的选项

  `-scope=""`：指定网络范围

  `-subnet=[]`：网络地址段，CIDR 格式，如：172.17.0.0/16

* connect 将容器接入网络

  connect 命令将一个容器连接到一个已存在的网络上。连接到网络上的容器可以跟同一网络中其他容器互通，同一容器可以同时接入多个网络。可以在执行 docker run 命令时候通过 -net 参数指定容器启动后自动接入的网络

  `docker network connect[OPTIONS]NETWORK CONTAINER`

  `alias=[]`：为容器添加一个别名，此别名仅在所添加网络上可见

  `-ip=""`：指定IP地址，需要注意不能跟已接入的容器地址冲突

  `-ip6=""`：指定IPv6地址

  `-link value`：添加连接到另一个容器

  `-link-local-ip=[]`：为容器添加一个链接地址

* disconnect 把容器从网络上断开

  `docker network disconnect[OPTIONS]NETWORK CONTAINER`

  `-f,-force`：强制把容器从网络上移除

* inspect 查看网络的详细信息

  inspect 命令用户查看一个网络的具体信息（JSON格式），包括接入的容器，网络配置信息等

  `docker network inspect[OPTIONS]NETWORK[NETWORK...]`

  `-f,-format=""`：给定一个 Golang 模板字符串，对输出结果进行格式化

  `-v,-verbose[=false]`：输出调试信息

* ls 列出所有的网络

  `docker network ls[OPTIONS]`

  `-f, -filter=""`：指定输出过滤器，如 `driver=bridge`

  `-format=""`：给定一个 golang 模板字符串，对输出结果进行格式化

  `-no-trunc[=false]`：不截断地输出内容

  `-q,-quiet[=false]`：安静模式，只打印网络 ID

* prune 清理无用的网络资源

  `docker network prune[OPTIONS][flags]`

  `-filter=""`：指定选项过滤器

  `-f,-force`：强制清理资源

* rm 删除一个网络

  当网络上没有容器连接上时，才会成功删除

  `docker network rm NETWORK[NETWORK...]`

```shell
# 创建网络 backend
docker network create backend
# 创建 container1/2 并加入 backend
docker run -it --name container1 --net backend busybox
docker run -it --name container2 --net backend busybox
# 在容器内测试容器连通性
ping container2
# 将 container2 加入 frontend 网络 network connect 会创建新的网卡来完成连接
docker network connect fronted container2
```

#### 服务与容器网络相关参数

##### 启动服务时配置重启生效

* 指定容器挂载的网桥

  `-b BRIDGE or --bridge=BRIDGE`

* 指定 docker0 的掩码

  `--bip=CIDR`

* docker 服务端接收命令通道

  `-H SOCKET --host=socket`

* 是否支持容器之间进行通信

  `--icc=true|false`

* 启用 net.ipv4.ip_forward，打开转发功能

  `--ip-forward=true|false`

* 禁止 docker 添加 iptables 规则

  `--iptables=true|false`

* 容器网络中的 MTU

  `--mtu=BYTES`

##### 启动服务时容器启动时

* 使用指定的 DNS 服务器

  `--dns=IP_ADDRESS`

* 指定 DNS 选项

  `--dns-opt=""`

* 指定 DNS 搜索域

  `--dns-search=DOMAIN`

##### 容器启动时生效

* 配置容器主机名

  `-h HOSTNAME or --hostname=HOSTNAME`

* 指定容器内接口的 IP 地址

  `-ip=""`

* 添加到另一个容器的连接

  `--link=CONTAINER_NAME:ALIAS`

* 配置容器的桥接模式

  `--net=bridge|none|container:NAME_or_ID|host|user_defined_network`

  支持五种模式：

  `--net=bridge`：默认配置，为容器创建独立的网络命名空间，分配网卡、IP 地址等网络配置，并通过 veth 接口对将容器挂载到一个虚拟网桥（默认 docker0）上

  `--net=none`：为容器创建独立的网络命名空间，但不进行网络配置，容器内没有创建网卡，IP 地址等

  `--net=container:NAME_or_ID`：新创建的容器共享指定的已存在容器的网络命名空间，两个容器内的网络配置共享，其他资源隔离

  `--net=host`：不为容器创建独立的网络命名空间，容器内看到的网络配置（网卡，路由表，Iptables规则）与主机上保持一致

  `--net=user_defined_network`：用户自行用 network 相关命令创建一个网络，同一个网络内的容器彼此可见，可以采用更多类型的网络插件

* 容器在网络中的别名

  `--network-alias`

* 映射容器端口到宿主主机

  `-p SPEC or --publish=SPEC`

* 映射容器所有端口到宿主主机

  `-P or --publish-all=true|false`

#### 端口映射

##### 从外部访问容器应用

在启动容器时，如果不指定对应参数，在容器外部时无法通过网络来访问容器内的网络应用和服务的， `-P` ，docker 会随机映射一个 49000 ~ 49900 的端口到内部容器开发的网络端口，`-f` 指定要映射的端口，且在一个指定端口上只可以绑定一个容器，支持的格式有 `IP:HostPort:ContainerPort|IP:ContainerPort|HostPort:ContainerPort`，多次使用 `-p` 标记可以绑定多个端口

### 容器间通信

通过容器向外界进行端口映射的方式可以实现通信，但不够安全，其需要 NAT，效率不高。docker 的连接系统可以在两个容器之间建立一个安全的通道。

#### 传统 link 通信

1.9 之后，网络操作独立成为一个命令组，link 系统也与原来不同，若容器使用默认的 bridge 模式网络，则会默认使用传统的 link 系统；而使用用户自定义的网络，则会使用新的 link 系统

##### 使用 link 通信

link 是在容器创建过程中通过 `--link <name or id>:alias` 参数创建的

```shell
# 创建数据库服务容器
docker run -d --name db training/postgres
# 创建 web 并连接到 db 上
docker run -d -P --name web --link db:webdb traiging/webapp python app.py
```

`db` 容器为源容器或子容器，`web` 容器为接收容器或父容器，一个接收容器可以设置多个源容器，一个源容器也可以有多个接收容器。docker 在容器创建的 link 阶段会进行如下工作

* 设置接收容器的环境变量

  没有一个源容器，接收容器就会设置一个名为 `<alias>_NAME` 环境变量，`alias` 为源容器的别名

* 更新接收容器的 `/etc/hosts` 文件

  Docker 容器的 IP 地址是不固定的，容器重启后 IP 地址可能就不同了，在有 link 关系的两个容器中，虽然接收方容器中包含源容器 IP 的环境变量，但如果源容器重启，接收方容器中的环境变量不会自动更新。因此 link 操作除了将 link 信息保存在接收容器中，还在 `/etc/hosts` 中添加了源容器的 IP 和别名。当一个容器重启后，自身的 hosts 文件和以自己为源容器的接收容器的 hosts 文件都会更新，保证了 link 系统的正常工作

* 建立 iptables 规则通信

#### 新 link 通信

相对于传统的 link 系统提供的名字和别名的解析、容器间网络隔离以及环境变量的注入，1.9 之后为用户提供了自定义网络提供了 DNS 自动名字解析，同一个网络中容器间的隔离，可以动态加入或者退出多个网络、支持 `--link` 为源容器设定别名等服务。在新的网络模型中，link 系统只是源容器起了一个别名，并且这个别名只对接收容器有效。新 link 系统在创建一个 link 时并不要求源容器已经创建或启动。

```shell
# 创建自定义网络 isolated_nw
docker network create isolated_nw
# 运行容器 container1 并加入 isolated_nw 并链接另一个容器 container2
docker run --net=isolated_nw -it --name=container1 --link container2:c2 busybox
# 创建 container2
docker run --net=isolated_nw -itd --name=container2 busybox
```

### 数据卷

是一个可供容器使用的特殊目录，它将主机操作系统目录直接映射进容器，类似于 linux 中的 mount 行为，有用的特性：

* 数据卷可以在容器之间共享和重用，容器间传递数据将变得高效与方便
* 对数据卷内数据的修改会立马生效，无论是容器内操作还是本地操作
* 对数据卷的更新不会影响镜像，解耦开应用和数据
* 卷会一直存在，直到没有容器使用，可以安全地卸载它

#### 创建数据卷

使用 volume 子命令来管理数据卷

```shell
# 创建数据卷 test，指定 local 驱动
docker volume create -d local test
# 查看信息
docker volume inspect test
# 列出所有卷
docker volume ls
# 清理无用卷
docker volume prune
# 删除无用卷
docker volume rm
```

#### 绑定数据卷

可以在创建容器时将主机本地的任意路径挂载到容器内作为数据卷，即绑定数据卷，在用 `docker [container] run` 命令时，可以使用 `-mount` 选项来使用数据卷。`-mount` 选项支持三种类型的数据卷，包括：

* `-volume`

  普通数据卷，映射到主机 `/var/lib/docker/volumes` 路径下

* `-bind`

  绑定数据卷，映射到主机指定路径下

* `-tmpfs`

  临时数据卷，只存在于内存中

```shell
docker run -d -P --name web --mount type=bind,source=/webapp,destination=/opt/webapp training/webapp python app.py
# 上述等价于旧的 -v 标记
docker run -d -P --name web -v /webapp:/opt/webapp training/webapp python app.py
```

本地目录的路径必须是绝对路径，容器内路径可以为相对路径，如果目录不存在，docker 会自动创建，docker 挂载数据卷的默认权限是读写（rw），可通过 `ro` 指定为只读（容器内对锁挂载数据卷内的数据就无法修改了）

```shell
docker run -d -P --name web -v /webapp:/opt/webapp:ro training/webapp python app.py
```

如果直接挂载一个文件到容器，使用文件编辑工具，可能回造成文件 `inode` 的改变，1.1.0 开始，这会导致错误信息，推荐的方式是直接挂载目录到容器中

#### 数据卷容器

需要在多个容器之间共享一些持续更新的数据，最简单的方式是使用数据卷容器，它专门提供数据卷给其他容器挂载。

```shell
# 创建一个数据卷容器 dbdata, 在其中创建一个数据卷挂载到 /dbdata
docker run -it -v /dbdata --name dbdata ubuntu
# 在其他容器中使用
docker run -it --volumes-from dbdata --name db1 ubuntu
```

使用 `--volumes-from` 参数所挂载数据卷的容器自身并不需要保持在运行状态。如果删除了挂载的容器，数据卷并不会被自动删除，如果要删除一个数据卷，必须在删除最后一个还挂载着它的容器时显式使用 `docker rm -v` 命令来指定同时删除关联的容器。

##### 利用数据卷容器来迁移数据

可以利用数据卷容器对其中的数据卷进行备份、恢复、以实现数据的迁移

* 备份

  ```shell
  # 备份 dbdata 数据卷容器内的数据卷
  docker run --volumes-from dbdata -v $(pwd):/backup --name worker ubuntu tar cvf /backup/backup.tar /dbdata
  ```

  使用 Ubuntu 镜像创建一个容器 worker，使用 `--volumes-from dbdata` 参数来让 worker 容器挂载 dbdata 容器的数据卷（即 dbdata 数据卷）；使用 `-v $(pwd):/backup` 参数来挂载本地的当前目录到 worker 容器的 `/backup` 目录。worker 容器启动后，使用 `tar cvf/backup/backup.tar /dbdata` 命令将 `/dbdata` 下内容备份为容器内的 `/backup/backup.tar`，即宿主机当前目录下的 backup.tar

* 恢复数据到一个容器

  ```
  # 创建一个带有数据卷的容器 dbdata2:
  docker run -v /dbdata --name dbdata2 ubuntu /bin/bash
  # 创建一个新的容器，挂载 dbdata2 容器，并解压备份文件到所挂载的容器卷中
  docker run --volumes-from dbdata2 -v $(pwd):/backup busybox tar xvf /backup/backup.tar
  ```

  

  

