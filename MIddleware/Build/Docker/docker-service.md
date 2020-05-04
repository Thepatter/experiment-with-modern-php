### 使用 docker 提供服务

#### Compose

##### 结构

实现对基于 docker 容器的多应用服务的快速编排。定位于：定义和运行多个 docker 容器的应用，允许用户通过一个单独的 *docker-compose.yml* 模版文件来定义一组关联的应用容器为一个服务栈

###### 任务

一个容器被称为一个任务，任务拥有独一无二的 ID，在同一个服务中的多个任务序号依次递增

###### 服务

某个相同应用镜像的容器副本集合，一个服务可以横向扩展为多个容器实例

###### 服务栈

由多个服务组成，相互配合完成特定业务，一般由一个 *docker-compose.yml* 文件定义

##### docker-compose

是 compose 核心，大部分指令与 CLI 客户端指令含义类似，默认的模版文件名为 *docker-compose.yml*，最新版本为 v3.8，从 1.5 开始，compose 模版文件支持动态读取主机的系统环境变量

* v1

    compose 文件结构为每个顶级元素为服务名称，次级元素为服务容器的配置信息

* v2/v3

    1. 扩展了 compose 的语法，同时尽量保持根旧版兼容，可以声明网络和存储信息，添加了版本信息
    2. 将所有的服务放到 services 根下面
    3. 每个服务都必须通过 image 指令指定镜像或 build（需要 Dockerfile）等来自动构建生成镜像

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

3.3 版本增加

###### depends_on

###### deploy

###### devices

###### dns

######  dns_search

###### entrypoint

###### env_file

###### environment

###### expose

###### external_links

###### extra_hosts

###### healthcheck

###### image

###### init

###### isolation

###### labels

###### links

###### logging

###### network_mode

###### networks

###### pid

###### ports

###### restart

###### secrets

###### security_opt

###### stop_grace_period

###### stop_signal

###### sysctis

###### tmpfs

###### ulimits

###### users_mode

###### volumes

#### Machine

#### SWARM

