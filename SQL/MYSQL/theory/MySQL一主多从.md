## 一主多从的架构

### 一主多从基本结构

*一主多从基本结构*

![](./Images/一主多从基本结构.png)

即：虚线箭头表示的是主备关系，A 和 `A'` 互为主备，从库 B，C，D 指向的是主库 A。一主多从的设置，一般用于读写分离，主库负责所有的写入和一部分读，其他的读请求则由从库分担

*一主多从基本结构--主备切换*

![](./Images/一主多从主备切换.png)

一主多从结构在切换完成后，`A'` 称为新的主库，从库 B，C，D 也更改连接到 `A'`，由于多了从库 B，C，D 重新指向的这个过程，主备切换的复杂性也相应增加了

### 主备切换过程

#### 基于位点的主备切换

把节点 B 设置成节点 `A'` 的从库的时候，需要执行一条 `change master` 命令

```mysql
CHANGE MASTER TO
MASTER_HOST=$host_name
MASTER_PORT=$port
MASTER_USER=$user_name
MASTER_PASSWORD=$password
MASTER_LOG_FILE=$master_log_name
MASTER_LOG_POS=$master_log_pos
```

这条命令的参数：`MASTER_HOST`，`MASTER_PORT`，`MASTER_USER`，`MASTER_PASSWORD` 四个参数，分别代表了主库 `A'` 的 IP、端口、用户名和密码， `MASTER_LOG_FILE` 和 `MASTER_LOG_POS` 表示，要从主库的 `master_log_name` 文件的 `master_log_pos` 这个位置的日志继续同步。这个位置即是同步位点，即是主库对应的文件名和日志偏移量。

原来节点 B 是 A 的从库，本地记录的是 A 的位点。但是相同的日志，A 的位点和 `A'` 的位点是不同的。因此从库 B 要切换的时候，就需要先经过 “找同步位点” 这个逻辑

但是这个位点很难精确取到，只能取一个大概位置。考虑到切换过程中不能丢数据，所以找位点的时候，总是要找一个“稍微往前”的，然后再通过判断跳过那些在从库 B 上已经执行过的事务

一种取同步位点的方法是这样的：

1.等待新主库 `A'` 把中转日志（`relay log`）全部完成；

2.在 `A'` 上执行 `show master status` 命令，得到当前 `A'` 上最新的 `File` 和 `Position`

3.取原主库的 A 故障的时刻 T

4.用 `mysqlbinlog` 工具解析 `A'` 的 `File`，得到 T 时刻的位点

```mysql
mysqlbinlog File --stop-datetime=T --start-datetime=T
```

![](./Images/备库mysqlbinlog输出.png)

`end_log_pos` 的值就是 `A'` 这个实例，在 T 时刻写入新的 `binlog` 的位置。把这个值作为 `$mater_log_pos` ，用在节点 B 的 `change master` 命令中。但是这个值并不精确

假设在 T 这个时刻，主库 A 已经执行完成了一个 `insert` 语句插入了一行数据 `R`，并且已经将 `binlog` 传给了 `A'` 和 B，然后在传完的瞬间主库 A 的主机就掉电了。此时系统的状态为

1.在从库 B 上，由于同步了 `binlog` ，R 这一行已经存在；

2.在新主库 `A'` 上，R 这一行也已经存在，日志是写在 123 这个位置之后

3.在从库 B 上执行 `change master` 命令，指向 `A'` 的 `File` 文件的 123 位置，就会把插入 R 这一行数据的 `binlog` 又同步到从库 B 去执行

此时，从库 B 的同步线程会报告 `Duplicate entry 'id_of_R' for key 'PRIMARY'` 错误，出现了主键冲突，然后停止同步

通常情况下，在切换任务的时候，要先主动跳过这些错误，有两种常用的方法

一种做法是，主动跳过一个事务，跳过命令的写法是

```mysql
set global sql_slave_skip_counter=1;
start slave;
```

因为在切换过程中，可能不会不止重复执行一个事务，所以需要在从库 B 刚开始连接到新主库 `A'` 时，持续观察，每次碰到这些错误就停下来，执行一次跳过命令，直到不再出现停下来的情况，以此来跳过可能涉及的所有事务

另一种方式是，通过设置 `slave_skip_errors` 参数，直接设置跳过指定的错误

在执行主备切换时，会经常遇到这两类错误

* 1062 错误是插入数据时唯一键冲突
* 1032 错误是删除数据时找不到行

因此可以把 `slave_skip_errors` 设置为 “1032,1062”，这样中间碰到这两个错误时就直接跳过。这种直接跳过指定错误的方法，针对的是主备切换时，由于找不到精确的同步位点，所以只能采用这种方法来创建从库和新主库的主备关系。这这种场景中，直接跳过 1032 和 1062 这两类错误是无损的，等到主备间的同步关系建立完成，并稳定执行一段时候之后，还需要把这个参数设置为空，以免之后出现此场景之外的主从数据不一致。

#### GTID

通过 `sql_slave_skip_counter` 跳过事务和通过 `slave_skip_errors` 忽略错误的方法，虽然都最终可以建立从库 B 和新主库 `A'` 的主备关系，但这两种操作都很复杂，而且容易出错。5.6 版本引入了 `GTID` 彻底解决了这个困难。

`GTID` 即 （`Global Transaction Identifier`）全局事务 ID，是一个事务在提交的时候生成的，是这个事务的唯一标识。由两部分组成，格式为：

```mysql
GTID=server_uuid:gno
```

* `server_uuid` 是一个实例第一次启动时自动生成的，是一个全局唯一的值
* `gno` 是一个整数，初始值是 1，每次提交事务的时候分配给这个事务，并加 1

在 `MySQL` 的官方文档里，`GTID` 格式定义是

```config
GTID=source_id:transaction_id
```

`source_id` 是 `server_uuid`；`transaction_id` 在 `MySQL` 指事务 id，事务 id 是在事务执行过程中分配的，如果这个事务回滚了，事务 id 也会递增，而 `gno` 是在事务提交的时候才会分配。从效果上看，`GTID` 往往是连续的。

`GTID` 模式的启动也很简单，只需要在启动一个 `MySQL` 实例的时候，加上参数 `gtid_mode=on` 和 `enforce_gtid_consistency=on` 就可以了。

在 `GTID` 模式下，每个事务都会跟一个 `GTID` 一一对应。这个 `GTID` 有两种生成方式，而使用哪种取决于 `session` 变量 `gtid_next` 的值

1.如果 `gtid_next=automatic`，代表使用默认值。此时，`MySQL` 就会把 `server_uuid:gno` 分配给这个事务。记录 `binlog` 的时候，先记录一行 `SET @@SESSION.GTID_NEXT='server_uuid:gno'`；把这个 `GTID` 加入本实例 `GTID` 集合

2.如果 `gtid_next` 是一个指定的 `GTID` 的值，如果通过 `set gtid_next='current_gtid'` 指定为 `current_gtid`，那么就有两种可能：如果 `current_gtid` 已经存在于实例的 `GTID` 集合中，接下来执行的这个事务会直接被系统忽略；如果 `current_gtid` 没有存在于实例的 `GTID` 集合中，就将这个 `current_gtid` 分配给接下来要执行的事务，也就是说系统不需要给这个事务生成新的 `GTID` ，因此 `gno` 也不用加 1

一个 `current_gtid` 只能给一个事务使用。这个事务提交后，如果要执行下一个事务，就要执行 `set` 命令，把 `gtid_next` 设置成另外一个 `gtid` 或者 `automatic`

这样，每个 `MySQL` 实例都维护了一个 `GTID` 集合，用来对应“这个实例执行过的所有事务”

事务的 `BEGIN` 之前有一条 `SET @@SESSION.GTID_NEXT` 命令。此时，如果实例 X 有从库，那么将 `CREATE TABLE` 的 `insert` 语句的 `binlog` 同步过去执行的话，执行事务之前就会先执行这两个 SET 命令，这样被加入从库 `GTID` 集合的

### 基于 GTID 的主备切换

在 `GTID` 模式下，备库 B 要设置为新主库 `A'` 的从库的语法如下

```mysql
CHANGE MASTER TO
MASTER_HOST=$host_name
MASTER_PORT=$port
MASTER_USER=$user_name
MASTER_PASSWORD=$password
master_auto_position=1
```

`master_auto_position = 1` 表示这个主备关系使用的是 `GTID` 协议。

此时，实例 `A'` 的 `GTID` 集合记为 `set_a`，实例 B 的 `GTID` 集合记为 `set_b`，此时在实例 B 上执行 `start slave` 命令，取 `binlog` 的逻辑是这样的

1.实例 B 指定主库 `A'`，基于主备协议建立连接

2.实例 B 把 `set_b` 发给主库 `A'`

3.实例 `A'` 算出 `set_a` 与 `set_b` 的差集，就是所有存在于 `set_a`，但不存在 `set_b` 的 `GITD` 的集合，判断 `A'` 本地是否包含了这个差集需要的所有 `binlog` 事务

a.如果不包含，表示 `A'` 已经把实例 B 需要的 `binlog` 给删掉了，直接返回错误

b.如果确认全部包含 `A'` 从自己的 `binlog` 文件里面，找出第一个不在  `set_b` 的事务，发给 B

4.之后就从这个事务开始，往后读文件，按顺序取 `binlog` 发给 B 去执行







