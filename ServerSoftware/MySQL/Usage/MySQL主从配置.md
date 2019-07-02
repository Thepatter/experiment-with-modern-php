## 建立主从配置

### 基本复制步骤

建立基本单一主从复制有三个步骤：

* 配置一个服务器作为 `master`
* 配置一个服务器作为 `slave`
* 将 `slave` 连接到 `mater`

#### 配置 master

将服务器配置为 `master` ，要确保该服务器有一个活动的二进制日志和唯一的服务器ID。

*配置`my.cnf`*

```mysql
[mysqld]
log-bin = master-bin
log-bin-index = master-bin.index
server-id = 1
```

* `log-bin` 选项：给出了二进制日志产生的所有文件的基本名（二进制文件会有多个），如果创建一个以 `log-bin` 为基本名的扩展文件名，该扩展名将被忽略，而只使用文件的基本名，即没有扩展的文件名。默认值为 `hostname-bin`。如果修改了主机名，`binlog` 文件也会随之改变，但是索引文件仍可以获取正确的值
* `log-bin-index` 选项：给出了二进制索引文件的文件名，这个索引文件保存所有 `binlog` 文件的列表。如果没有为 `log-bin-index` 赋予任何值，其默认值与 `binlog` 文件的基本名相同即默认为 `hostname-bin`。即也会随着主机名的改变而改变。**如果改变主机名然后重启服务器，将找不到索引文件，从而认为二进制文件不存在，导致二进制日志为空**
* `server-id` 选项：每个服务器都有一个唯一的服务器 ID，如果一个 `slave` 连接了 `master` ，并且其 `server-id` 的参数与 `master` 相同，则会报 `master` 和 `slave` 服务器 ID 相同的错误

创建用户并赋权

```mysql
master>create user repl_user;
master>grant replication slave on *.* TO repl_user identified by 'secret'
```

拥有 `replication slave` 权限的用户能够获取 `master` 上的二进制日志。执行 `flush` 命令需要 `reload` 权限。执行 `show master status` 和 `show slave status` 命令需要 `super` 和 `replication client` 权限。执行 `change master to` 需要 `super` 权限

#### 配置 slave

需要为 `slave` 分配唯一的 `server-id`  和 `relay-log` 中继日志文件 和 `relay-log-index` 中继日志索引文件的文件名

```
[mysqld]
server-id=2
relay-log-index=slave-relay-bin.index
relay-log=slave-relay-bin
```

#### 连接 master 和 slave

```mysql
slave>change master to
-> master_host = 'master-1',
-> master_port = 3306,
-> master_user = 'repl_user',
-> master_password = 'secret';
slave>start slave
```

