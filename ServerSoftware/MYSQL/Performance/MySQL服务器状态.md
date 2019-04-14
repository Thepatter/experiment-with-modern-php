## MySQL服务器状态

MySQL以多种方式来暴露服务器内部消息。5.5中的 `PERFORMACE_SCHEMA` ，而标准的 `INFORMATION_SCHEMA` 库从 5.0 开始就存在，此外一直存在一系列的 `SHOW` 命令，有些通过 `SHOW` 命令获取的信息并不在 `INFORMATION_SCHEMA` 中存在。

### 系统变量

MySQL通过 `SHOW VARIABLES` 的SQL命令显露了许多系统变量，可以在表达式中使用这些变量，或在命令行中通过 `mysqladmin variables` 试验。从 5.1 开始，可以通过访问 `INFORMATION_SCHEMA` 库中的表来获取这些信息

这些变量反映了一系列配置信息，如果服务器的默认存储引擎 （`storage_engine`）

### show status

show status 命令会显示每个服务器变量的名字和值。和上面讲的服务器参数不一样，状态变量是只读的，可以在MySQL客户端里运行 `SHOW STATUS` 或在命令行里运行 `mysqladmin extended-status` 来查看这些变量。如果使用SQL命令，可以使用 `LIKE` 或 `WHERE` 来限制结果。可以用 LIKE 对变量名做标准模式匹配。命令将返回一个结果表，但不能对它排序，与另外一个表做联合操作，或像对MySQL表做一下操作

`show status` 的行为自5.0版本后有了非常大的改变，5.0之前版本只有全局变量，5.1及以后的版本中，有的变量是全局的，有的变量是连接级别的。因此 `SHOW STATUS` 混杂了全局和会话变量。其中许多变量有双重域：既是全局变量，也是会话变量。使用 `SHOW GLOBAL STATUS` 查看全局变量

大部分要么是计数器，要么包含某些状态指标的当前值。每次 MySQL 做一些事情都会导致计数器的增长，比如开始初始化一个全表扫描。度量值

### 线程和连接统计

这些变量用来跟踪尝试的连接、退出的连接、网络流量和线程统计

* `Connections`，`Max_used_connections`，`Threads_connected`
* `Aborted_clients`，`Aborted_connects`
* `Bytes_received`，`Bytes_sent`
* `Slow_launch_threads`，`Threads_cached`，`Threads_created`，`Threads_running`

如果 `Aborted_connects` 不为 0，可能意味着网络有问题或某人尝试连接但失败（可能用户指定了错误的密码或无效的数据库，或某个监控系统正在打开TCP的3306端口来检测服务器是否还活着）如果这个值太高，可能有严重的副作用；导致MySQL阻塞一个主机

`Aborted_clients` 有类似的名字但意思完全不同。如果这个值增长，一般意味着曾经有一个应用错误，例如程序在结束之前忘记正确地关闭MySQL连接。这一般并不表明有大问题

### 二进制日志状态

`Binlog_cache_use` 和 `Binlog_cache_disk_use` 状态变量显示了在二进制日志缓存中有多少事务被存储过，以及多少事务因超过二进制日志缓存而必须存储到一个临时文件中。MySQL 5.5 还包含 `Binlog_stmt_cache_use` 和 `Binlog_stmt_cache_disk_use`，显示了非事务语句相应的度量值。

### 命令计数器

`Com_*` 变量统计了每种类型的 SQL 或 C API 命令发起过的次数。`Com_select` 统计了 `SELECT` 语句的数量，`Com_change_db` 统计一个连接的默认数据库被通过USE语句或C API调用更改的次数。`Questions` 变量统计总查询量和服务器收到的命令数。然而，它并不完全等于所有 `Com_*` 变量的总和，这与查询缓存名中、关闭和退出的连接，以及其他可能的因素有关。

### 临时文件和表

可以通过下列命令查看 `MySQL` 创建临时表和文件的计数

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Created_tmp%';
```

这显示了关于隐式临时表和文件的统计一一执行查询时内部创建。在 `Percona Server` 中，同样有展示显示临时表（即由用户通过CREATE TEMPORARY TABLE 所创建）的命令。

```mysql
mysql> SHOW GLOBAL TEMPORARY TABLES
```

### 句柄操作

句柄 API 是 MySQL 和存储引擎之间的接口。`Handler_*` 变量用于统计句柄操作，例如 MySQL 请求一个存储引擎来从一个索引中读取下一行的次数。可以通过下列命令查看

```mysql
SHOW GLOBAL STATUS LIKE 'Handler_%'
```

### MyISAM 键缓冲

`Key_*` 变量包含度量值和关于 `MyISAM` 键缓冲的计数。可以通过下列命令查看这些变量

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Key_%';
```

### 文件描述符

如果主要使用 `MyISAM` 存储引擎，那么 `Open_*` 变量揭示了 `MySQL` 每隔多久会打开每个表的 `.frm` ，`.MYI` 和 `.MYD` 文件。`InnoDB` 保持所有的数据在表空间文件中，因此如果主要使用 `InnoDB`，那么这些变量并不精确。可以通过下列命令查看 `Open_*` 变量

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Open_%'
```

### SELECT 类型

`Select_*`变量是特定类型的 `SELECT `查询的计数器。能帮助了解使用各种查询计划的 `SELECT` 查询比率。不幸的是，没有关于其他查询类型（UPDATE之类）的状态变量。可以看一下 `Handler_*` 状态变量大致了解非 `SELECT` 查询的相对数量。

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Select_%';
```

`Select_*` 状态变量可以按花费递增的顺序如下排列

* `Select_range`

  在第一个表上扫描一个索引区间的联接数目

* `Select_scan`

  扫描整个第一张表的联接数目。如果第一个表中每行都参与联接，这样计数并没有问题；如果并不想要所有行但又没有索引以查找到所需要的行

* `Select_full_range_join`

  使用在表 n 中的一个值来从表 n + 1 中通过参考索引的区间内获取行所做的联接数。这个值或多或少比 `Select_scan` 开销多些，具体多少取决于查询

* `Select_range_check`

  在表 n + 1 中重新评估表 n 中的每一行的索引是否开销最小所做的联接数。这一般意味着在表 n + 1 中对该联接而言并没有有用的索引。这个查询有非常高的额外开销

* `Select_full_join`

  交叉联接或并没有条件匹配表中行的连接的数目。检查的行数是每个表中行数的乘积。这通常是个坏事情

### 排序

当 MySQL 不能使用一个索引来获取预先排序的行时，必须使用文件排序，这会增加 `Sort_*` 状态变量。除 `Sort_merge_passes` 外，可以只是增加 `MySQL` 会用来排序的索引以改变这些值。`Sort_merge_passes` 依赖 `sort_buffer_size` 服务器变量。MySQL 使用排序缓冲来容纳排序的行块。当完成排序后，它将这些排序后的行合并到结果集中，增加 `Sort_merge_passes`，并且用下一个待排序的行块填充缓存。

```mysql
SHOW GLOBAL STATUS LIKE 'Sort_%'
```

当 MySQL 从文件排序结果中读取已经排好序的行并返回给客户端时，`Sort_scan` 和 `Sort_range` 变量会增长。不同在于：前者是当查询计划导致 `Select_scan` 增加时增加，而后者是当 `Select_range` 增加时增加。二者的实现和开销完全一样；仅仅指示导致排序的查询计划类型

