### MySQL Server

#### Server 架构

MySQL 是典型的 C/S 架构，而 Server 层提供各种接口供 Client 调用，但 Server 层不操作数据，操作数据由引擎层负责，Server 充当 Client 与 Engine 中间代理

##### 通用架构

*组成全貌*

![](../Images/Performance/MySQL数据库结构.png)



###### 连接与安全

每个客户端连接都会在服务区进程中拥有一个线程，这个连接的查询只会在这个单独的线程中执行，服务器会缓存线程，5.5 支持线程池模式

当客户端应用连接到 MySQL 服务器时，服务器需要对其进行认证。认证基于用户名、原始主机信息、密码，如果使用了 SSL，还可以使用 X.509 证书认证。连接成功后，服务器会继续验证该客户端是否具有某个特定查询的权限

###### 优化与执行

Server 会解析查询，并创建内部数据结构（解析树），然后对其进行优化（包括重写查询，决定表的读取顺序，以及选择合适的索引）用户可以通过特殊的关键字 **HINT** 提示优化器，影响其决策

###### 在事务中混合使用存储引擎

Server 层不管理事务，事务由下层存储引擎实现，在同一个事务中，使用多种存储引擎是不可靠的。

如果在事务中混合使用了事务型和非事务型的表，在正常提交的情况下不会有什么问题，但如果该事务需要回滚，非事务型的表上的变更就无法撤销，会导致数据库处于不一致的状态，事务的最终结果将无法确定。

在非事务型的表上执行事务相关操作，Server 通常不会发出提醒，也不会报错，只会在回滚时发出警告：某些非事务型表上的变更不能被回滚。

###### Server 层锁

支持 **LOCK TABLES** 和 **UNLOCK TABLES** 语句，这是在 Server 层实现的，和存储引擎无关，它们有自己的用途。但并不能代替事务处理。

##### Schema

在 MySQL 中，数据库等价于 Schema

#### 语句执行

##### Server 层语句执行流程

#### 性能剖析

##### 定位问题

###### 定位

首先要确认是单条查询的问题还是服务器的问题，如果服务器上所有的程序都突然变慢，又突然变好，每一条查询也都变慢了，那么慢查询可能不一定是原因，而是由于其他问题导致的结果。

如果服务器整体运行没有问题，只有某条查询偶尔变慢，就需要将剖析该查询语句

###### show global status

这个方法实际上就是以较高的频率如一秒一次执行 SHOW GLOBAL STATUS 命令捕获数据，问题出现时，则可以通过某些计数器（如：Threads_running，Threads_connected，Questions 和 Queries ）的尖刺或凹陷来发现。这个方法很简单，对服务器的影响也很小。

###### show processlist

通过不停地捕获 show processlist 的输出，来观察是否有大量线程处于不正常。如果 5.6 起至今查询 INFORMATION_SCHEMA 中的 PROCESSLIST 表

```shell
mysql -e 'show processlist\G' | grep State: | sort | uniq -c | sort -rn
```

##### 剖析

###### 目标

性能剖析是测量和分析时间花费在那里的主要方法。一般步骤

1. 测量任务所花费的时间

   任务结束时间减去启动时间。

2. 对结果进行统计和排序，将重要的任务排到前面

完成一项任务所需要的时间分为

* 执行时间

  如果要优化任务的执行时间，最好是通过测量定位不同的子任务花费的时间，然后优化去掉一些子任务、降低子任务的执行频率或提升子任务的效率。

* 等待时间

5.5 开始提供了 Performance Schema，其中有一些基于时间的测量点

###### 服务器负载

* 慢查询日志
* pt-query-digest
* tcpdump

##### 剖析单条查询语句

###### explain

EXPLAIN 命令是查看查询优化器是如何决定执行查询的主要方法。这个功能有局限性，并不总是正确。

要使用 EXPLAIN 命令，只需在查询中的 SELECT 关键字前添加 EXPLAIN。MySQL 会在查询上设置一个标记。当执行查询时，这个标记会使其返回关于在执行计划中每一步的信息，而不是执行它。

会返回一行或多行信息，显示出执行计划中的每一部分和执行的次序。在查询中每个表在输出中只有一行。如果查询时两个表的连接，输出中会有两行，别名表也算一个表（表包含：子查询，UNION 结果，实体表）

###### show profile

5.1 版本引入，默认关闭，剖析会给出查询执行的每个步骤及其花费的时间，但不会告诉原因，一般使用

```mysql
# 当前会话开启 profile
set profiling = 1
# 运行待剖析 SQL 语句
# 查看查询
show profiles;
# 根据 query id 分析单条语句
show profile for query 1;
```

未来版本将废弃，5.5 版本引入的 INFORMATION_SHCEMA 库，可以查询其 profiling 数据表剖析

```mysql
set @query_id = 1;
select state, sum(duration) as total_r, round(100 * sum(duration) / (select sum(duration) from information_schema.profiling where query_id = @query_id), 2) as pct_r, count(*) as cails, sum(duration) / count(*) as "R/Call" from information_schema.profiling where query_id = @query_id group by state order by total_r desc;
```

###### show status

会返回一些计数器（包含会话和全局），可以显示某些活动的频繁程度，但无法给出消耗了多少时间，结果中只有   *Innodb_row_lock_time* （获取 Innodb 表的行锁所用的总时间毫秒）全局数据与时间有关。最有用的计数器包括：句柄、临时文件、表。一般使用时先刷新会话级别计数器，然后执行查询语句，再查询全局变量分析

```mysql
flush status;
# 执行 sql
show status where variable_name like 'Handler%' or variable_name like 'Created%';
```

##### 服务器状态

