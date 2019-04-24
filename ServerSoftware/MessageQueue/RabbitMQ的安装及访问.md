## RabbitMQ base usage 

### RabbitMQ install and access

#### Install Erlang

* 添加 `/etc/apt/preferences.d/erlang` 更新策略来确定 rabbitmq 的 erlang 版本

  `etc/apt/preferences.d/erlang`

  ```
  # /etc/apt/preferences.d/erlang
  Package: erlang*
  Pin: version 1:20.1-1
  Pin-Priority: 1000
  
  Package: esl-erlang
  Pin: version 1:20.1.7
  Pin-Priority: 1000
  ```

* 添加存储库条目

  ```
  wget https://packages.erlang-solutions.com/erlang-solutions_1.0_all.deb
  sudo dpkg -i erlang-solutions_1.0_all.deb
  ```

* 添加 Erlang Solutions 公钥

  ```
  wget https://packages.erlang-solutions.com/ubuntu/erlang_solutions.asc
  sudo apt-key add erlang_solutions.asc
  ```

* 安装 erlang

  ```
  sudo apt-get update && sudo apt-get install erlang
  ```

#### Install rabbitmq-server

* 添加对应版本代码库 `lsb_release -a` 对应版本

  ```shell
  echo "deb https://dl.bintray.com/rabbitmq/debian {lsb_release -a} main" | sudo tee /etc/apt/sources.list.d/bintray.rabbitmq.list
  ```

* 添加公钥

  ```shell
  wget -O- https://www.rabbitmq.com/rabbitmq-release-signing-key.asc | sudo apt-key add -
  ```

* 安装

  ```shell
  sudo apt-get update && sudo apt-get install rabbitmq-server
  ```

#### Nomarl config

* 添加配置文件 `/etc/rabbitmq/rabbitmq.config`

  ```
  [{rabbit, [{loopback_users, []}]}].   # 结尾的 . 号必须添加,该配置为允许 guest 用户在其他主机访问
  ```

* 安装 web 插件

  `/usr/sbin/rabbitmq-plugins enable rabbitmq_management`

#### Start

`sudo systemctl rabbitmq-server.service` 或 `sudo service rabbitmq-server status`

#### Access rabbitmq

* 命令行访问

  `sudo rabbitmqctl status` ,`rabbitmqctl` 运行文件在 `/usr/sbin/rabbitmqctl`

* 安装 web 插件后浏览器访问

  http://ip:15672 			# 使用 guest,guest 登陆

#### RabbitMQ use control and roles

* RabbitMQ 的用户角色分类:

  * none

    不能访问 `management plugin`

  * management

    用户可以通过 AMPQP 做的任何事外加:

    列出自己可以通过 AMQP 登入的 virtual hosts

    查看自己的 virtual hosts 中的 queues, exchanges 和 bindings

    查看和关闭自己 channels 和 connections

    查看有关自己的 vitual hosts 的全局统计信息，包含其他用户在这些 virtual hosts 中的活动

  * policymaker

    management 权限

    查看，创建和删除自己的 virtual hosts 所属的 policies 和 parameters

  * monitoring

    management 权限

    列出所有 virtual hosts,包括他们不能登录的 virtual hosts

    查看其他用户的 connections 和 channels

    查看节点级别的数据如 clustering 和 memory 使用情况

    查看真正的关于所有 virtual hosts 的全局的统计信息

  * administrator

    policymaker 和 monitoring  权限

    创建和删除 virtual hosts

    查看，创建和删除 users

    查看创建和删除 permissions

    关闭其他用户的 connections

* 创建用户并设置角色

  * 创建管理员用户，负责整个 MQ 的运维

    `sudo rabbitmqctl add_user user_admin passwd_admin`

  * 赋予其 administrator 角色

    `sudo rabbitmqctl set_user_tags user_admin administrator`

  * 创建 RabbitMQ 监控用户，负责整个 MQ 的监控

    `sudo rabbitmqctl add_user user_monitoring passwd_monitor`

  * 赋予其 monitoring 角色

    `sudo rabbitmqctl set_user_tags user_monitoring monitoring`

#### RabbitMQ 权限控制

用户仅能对其所能访问的 virtual hosts 中的资源进行操作。这里的资源指的是 virtual hosts 中的 exchanges, queues 等，操作包括对资源进行配置，写，读。配置权限可创建，删除，资源并修改资源的行为，写权限可象资源发送消息，读权限从资源获取消息。

exchange 和 queue 的 declare 与 delete 分别需要 exchange 的读写权限

exchange 的 bind 与 unbind 需要 exchange 的读写权限

queue 的 bind 与 unbind 需要 queue 写权限 exchange 的读权限

发消息（publish)  需要 exchange 的写权限

获取或清除 (get, consume, purge) 消息需 queue 的读权限

对何种资源具有配置，写，读的权限通过正则表达式来匹配，命令

`set_permissions [-p <vhostpath>] <user> <conf> <write> < read>`

其中，`<conf> <write> <read>` 的位置分别用正则表达式来匹配特定的资源：

`^(amp\\.gen.*|amq\.default)$` 可以匹配 server 生成的和默认的 `exchange`,

`^$` 不匹配任何资源

RabbitMQ 会缓存每个 connection 或 channel 的权限验证结果，因此权限发生变化后需要重连才能生效

为用户赋权:

`sudo rabbitmqctl set_permissions -p /vhost user_admin '.*' '.*' '.*'`

该命令使用户user_admin具有/vhost1这个virtual host中所有资源的配置、写、读权限以便管理其中的资源 

查看权限:

`sudo rabbitmqctl list_user_permissions user_admin`

