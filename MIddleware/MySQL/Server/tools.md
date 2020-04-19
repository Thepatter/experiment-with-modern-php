### mysqld tools

#### Command Tools

##### mysqladmin

###### 常用选项

* status

  显示服务器状态信息，包括正常运行时间、线程数、查询数、常规统计数据，提供服务器健康状况的一个快照

* extended-status

  显示系统统计信息的完整列表，类似 SQL SHOW STATUS

* processlist

  显示当前进程的列表，类似 SQL SHOW PROCESSLIST

* Kill thread id

  杀死某个指定的线程。它与 processlist 结合使用，可以帮助管理失控或挂起的线程

* variables

  显示服务器变量和值，类似 SQL SHOW VARIABLES

###### 使用

```shell
# 每 3 秒刷新一次进程列表
mysqladmin -p processlist --sleep 3
```

##### mysqlbinlog

###### 常用选项

* short-form

  该选项只输出发出的SQL语句信息，而忽略二进制日志中事件的注释信息。当仅仅使用mysqlbinlog将事件重放到服务器时，这个选项是非常有用的。如果想审查binlog文件中的错误，就需要注释信息，不能使用该选项

* force-if-open

  如果binlog没有被正确关闭，比如binlog文件仍在写入或服务器崩溃，mysqlbinlog都将输出一条警告说这个binlog文件没有被正确关闭。该选项用于禁止输出那条警告。

* Base64-outout=never

  该选项阻止mysqlbinlog输出base64编码的事件。如果要输出base64编码的事件，也会输出二进制日志的Format_description事件，表明使用了哪种编码。这对基于语句的复制是不必要的，因此使用这个选项来阻止该事件。

* read-from-remote-server

  选项读取远程 binlog 文件，而不需要给出全路径。

* result-file=prefix

  该选项给出创建写入文件的前缀，这个前缀可以是目录名（带有反斜杠），或者任何其他前缀。默认值是一个空字符串，所以如果不使用这个选项，即将写入的文件与 master上的文件同名。

* to-last-log

  一般来说，只有命令行给定的文件才会被传送。但如果提供了这个选项，只需要给定开始传送的二进制日志文件，然后 mysqlbinlog 会把后面所有文件都传送出去。

* stop-never

  到达最后一个日志文件末尾也不停止，继续等待更多输入。在做即时恢复备份的时候，这个选项很有用。

###### 使用

```shell
# 获取远程 binlog 文件备份
mysqlbinlog --raw --read-from-remote-server \
	--host=master.example.com --user=repl_user \
	master-bin.000012 master-bin.000012
```

##### mysqlddumpslow

自带的慢查询统计，依赖  perl

###### 开启慢查询

```mysql
# 查看慢查询是否开启
show variables like '%slow_query_log%';
# 开启慢查询
set global slow_query_log = 'ON';
# 查询慢查询时间阈值
show variables like '%long_query_time%';
# 修改慢查询记录时间
set global long_query_time = 3;
```

###### 常用选项

* -s

    采用 order 排序的方式，排序方式可以有：c（访问次数）、t（查询时间）、l（锁定时间）、r（返回时间）、ac（平均查询次数）、al（平均锁定时间）、ar（平均返回记录数）、at（平均查询时间，默认）

* -t

    返回前 N 条数据

* -g

    后面跟正则，大小写不敏感

###### 使用

```shell
# 按照查询时间，查看前两条 SQL 语句
perl mysqldumpslow.pl -s t -t 2 "C:\ProgramData\MySQL\MySQL Server 8.0\Data\DESKTOP-4BK02RP-slow.log"
```

#### Inner Tools

##### Explain

EXPLAIN 命令是查看查询优化器是如何决定执行查询的主要方法。这个功能有局限性，并不总是正确。

要使用 EXPLAIN 命令，只需在查询中的 SELECT 关键字前添加 EXPLAIN。MySQL 会在查询上设置一个标记。当执行查询时，这个标记会使其返回关于在执行计划中每一步的信息，而不是执行它。

会返回一行或多行信息，显示出执行计划中的每一部分和执行的次序。在查询中每个表在输出中只有一行。如果查询时两个表的连接，输出中会有两行，别名表也算一个表（表包含：子查询，UNION 结果，实体表）

###### 扩展

- EXPLAIN EXTENDED 和正常 EXPLAIN 一样，但会告诉服务器”逆向编译“执行计划为一个 SELECT 语句。可以通过紧接其后运行 `SHOW WARNINGS` 看到生成的这个语句。这个语句直接来自执行计划， 而不是原 SQL 语句。大部分场景下与原语句不同。（**已废弃**）
- EXPLAIN PRITITIONS 会显示查询将访问的分区，如果查询时基于分区表的话

###### 局限性

- 如果查询在 FROM 子句中包括子查询，MySQL 实际会执行子查询，将其结果放在临时表中，然后完成外层查询优化。必须在完成外层查询优化之前处理类似的子查询。在 5.6 中取消该限制
- EXPLAIN 是近似结果
- 不区分具有相同名字的事物。内存排序和临时文件都使用 filesort，磁盘和内存临时表都显示 Using temporary
- 不会显示执行计划的所有信息，及特定优化，不支持存储过程。
- 不支持非 SELECT 语句

###### 分析结果列

|     字段      |                             含义                             |
| :-----------: | :----------------------------------------------------------: |
|      id       |                  编号，表示 SELECT 所属的行                  |
|  select_type  |                      指示对应行查询类型                      |
|     table     | 指示对应行正在访问的表（表名或 SQL 中别名）可以在这一列中从上往下观察 MySQL 的关联优化器为查询选择的关联顺序 |
|     type      |             关联类型，MySQL 决定如何查找表中的行             |
| possible_keys | 指示查询可以使用的索引，基于查询访问的列和使用的比较操作符来判断，该列表是优化早期创建，有些罗列出来的索引可能对于后续优化过程没用 |
|      key      | 指示 MySQL 决定采用那个索引访问表，如果该索引未出现在 possible_keys 列中，选择可能出于别的原因 |
|    key_len    | 指示 MySQL 在索引里使用的字节数（使用的索引中字段的字节长度和，如果 MySQL 正在使用的只是索引里的某些列，可以用这个值计算具体列） |
|      ref      |    显示之前的表在 key 列记录的索引中查找只所用的列或常量     |
|     rows      | 估计为找到所需的行而需要读取的行数。这个数字是内嵌循环关联计划里的循环数目。不是 MySQL 认为它最终要从表里读取出来的行数，而是 MySQL 为了找到符合查询的每一点上标准的那些行而必须读取的行的平均数，是 MySQL 认为它要检查的行数，非结果集里的行数。通过把所有 rows 列的值相乘，可以粗略估算整个查询会检查的行数 |
|   filtered    | 针对表里符合某个条件（WHERE 子句或联接条件）的记录数的百分比所做的一个悲观估算。如果把 rows 列和这个百分比相乘，就能看到 MySQL 估算它将和查询计划里前一个表关联的行数。优化器只有在 ALL，INDEX，RANGE，INDEX_MERGE 访问方法时才会用这一估算 |
|     Extra     |      补充信息：Using index（使用覆盖索引，避免访问表）       |

###### 查询类型

* SIMPLE

    简单查询，不包括子查询和 UNION。

如果查询有任何复杂的子部分，则最外层部分会标记为 PRIMARY。其他部分标记为：

* SUBQUERY

    包含在 SELECT 列表中的子查询中的 SELECT（不在 FROM 子句中）

* DERIVED

    用来表示包含在 FROM 子句的子查询中的 SELECT，MySQL 会递归执行并将结果放到一个临时表中。服务器内部标识为派生表（该表是从子查询中派生而来）

* UNION

    在 UNION 中的第二个和随后的 SELECT 被标记为 UNION。如果 UNION 被 FROM 子句中的子查询包含，那么它的第一个 SELECT 会被标记为 DERIVED。

* UNION RESULT

    用来从 UNION 的匿名临时表检索结果的 SELECT 被标记为 UNION RESULT

除了这些值，SUBQUERY 和 UNION 还可以被标记为 DEPENDENT 和 UNCACHEABLE。DEPENDENT 意味着SELECT 依赖于外层查询中发现的数据；UNCACHEABLE 意味着 SELECT 中的某些特性阻止结果被缓存于一个Item_cache 中。（Item_cache未被文档记载；它与查询缓存不是一回事，尽管它可以被一些相同类型的构件否定，例如 RAND() 函数。）

###### 派生表和联合

* 当 FROM 子句中有子查询或有 UNION 时，table 列会变得复杂的多。在这些场景下，确实没有一个表可以参考，因为 MySQL 创建的匿名临时表仅在查询执行过程中存在

* 当在 FROM 子句中有子查询时，table 列是  <derivedN> 的形式，其中 N 是子查询的 id。总是向前引用，N 指向 EXPLAIN 输出中后面的一行

* 当有 UNION 时，UNION RESULT 的 table 列包含一个参与 UNION 的 id 列表。总是向后引用，因为 UNION RESULT 出现在 UNION 中所有参与行之后。

###### 关联类型

关联类型的性能：

1. NULL

    MySQL 能在优化阶段分解查询语句，在执行阶段不再访问表或者索引。

2. const、system

    对查询的某部分进行优化并将其转换成一个常量时，它会使用该访问类型

3. eq_ref

    索引查找，该类型最多只返回一条符合条件的记录

4. ref

    索引访问，返回所有匹配某个单个值的行。可能会返回多个符号条件的行。ref 是因为索引要根某个参考值比较，这个参考值或者是一个常数，或者是来自多表查询前一个表里的结果值

    ref_or_null 是 ref 之上的一个变体，它意味着 MySQL 必须在初次查找的结果里进行第二次查找以找出 NULL 条目。

5. range

    范围扫描是一个有限制的索引扫描，它开始于索引里的某一点，返回匹配这个值域的行

    当 MySQL 使用索引去查找一系列的值时，in 和 or 列表，也会显示为范围扫描（不同的访问类型，存在性能差异）

6. index

    根全表扫描一样，只是扫描表时按索引次序进行而不是行。优点是避免了排序，缺点是承担按索引次序读取整个表的开销。

     Extra 列中看到 Using index 说明 MySQL 正在使用覆盖索引，它只扫描索引的数据，而不是按索引次序的每一行。比按索引次序全表扫描的开销要少很多

7. all

    全表扫描（这里也有个例外，例如在查询里使用了 LIMIT，或者在 Extra 列中显示 Using distinct/not exists）

###### 补充信息

- Using index

    此值表示 MySQL 将使用覆盖索引，比避免访问表。

- Using where

    MySQL 服务器将在存储引擎检索行后再进行过滤，此时引擎层已锁住获取的所有行。

    许多 WHERE 条件里涉及索引中的列，当它读取索引时，就能被存储引擎检验，因此不是所有带 WHERE 子句的查询都会显示 Using where（5.6 引入索引下推）

- Using temporary

    MySQL 在对查询结果排序时会使用一个临时表

- Using filesort

    MySQL 会对结果使用一个外部索引排序，而不是按索引次序从表里读取行。（文件或内存排序，无法反映具体排序方式）

- Range checked for each record (index map: N)

    意味没好用的索引，新的索引将在联接的每一行上重新估算。N 是显示在 possible_keys 列中索引的位图，并且是冗余的

##### SEVER STATUS

服务器内部状态

###### system variables

MySQL通过 SHOW VARIABLES 的SQL命令显露了许多系统变量，可以在表达式中使用这些变量，或在命令行中通过 mysqladmin variables 试验。从 5.1 开始，可以通过访问 INFORMATION_SCHEMA 库中的表来获取这些信息

这些变量对应一系列配置信息

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

状态变量是只读的（要么是计数器，要么包含某些状态指标的当前值。每次 MySQL 做一些事情都会导致计数器的增长），可以使用 LIKE 或 WHERE 来限制结果。可以用 LIKE 对变量名做标准模式匹配。命令将返回一个结果表，但不能像 MySQL 表一样操作。使用 SHOW GLOBAL STATUS 查看全局变量

变量采用无符号整型存储，32 位编译系统上用 4 个字节，64 位用 8 个字节，当达到最大值后会重新从 0 开始

5.0之前版本只有全局变量，5.1开始，有的变量是全局的，有的变量是连接级别的。因此 SHOW STATUS 混杂了全局和会话变量。其中许多变量有双重域，既是全局变量，也是会话变量。

5.1 开始，可以直接从 INFORMATION_SCHEMA.GLOBAL_STATUS 和 INFORMATION_SCHEMA.SESSION_STATUS 表中查询。

线程和连接统计

这些变量用来跟踪尝试的连接、退出的连接、网络流量和线程统计

* Connections，Max_used_connections，Threads_connected

* Aborted_connects

    如果 Aborted_connects 不为 0，可能意味着网络有问题或某人尝试连接但失败（可能用户指定了错误的密码或无效的数据库，或某个监控系统正在打开TCP的3306端口来检测服务器是否还活着）如果这个值太高，可能有严重的副作用；导致MySQL阻塞一个主机

* Aborted_clients 

    如果这个值增长，一般意味着曾经有一个应用错误，例如程序在结束之前忘记正确地关闭 MySQL 连接。这一般并不表明有大问题

* Bytes_received，Bytes_sent
* Slow_launch_threads，Threads_cached，Threads_created，Threads_running

二进制日志状态

* Binlog_cache_use

    二进制日志缓存中有多少事务被存储过

* Binlog_cache_disk_use 

    二进制日志缓存中多少事务因超过二进制日志缓存而必须存储到一个临时文件中

* Binlog_stmt_cache_use

    5.5 新增，指示二进制日志缓存中有多少非事务语句被存储

* Binlog_stmt_cache_disk_use

    5.5 新增，指示二进制日志缓存中有多少非事务语句因超过二进制日志缓存而必须存储到一个临时文件中

命令计数器

Com_* 变量统计了每种类型的 SQL 或 C API 命令发起过的次数。_

* Com_select

    统计了 SELECT 语句的数量

* Com_change_db

    统计一个连接的默认数据库被通过 USE 语句或 C API 调用更改的次数。

* Questions

    服务器执行的语句数。 与Queries变量不同，这仅包括客户端发送给服务器的语句，而不包括在存储的程序中执行的语句。 

    此变量不计算 COM_PING，COM_STATISTICS，COM_STMT_PREPARE，COM_STMT_CLOSE，COM_STMT_RESET 命令

* Queries

    服务器执行的语句数。 与Questions变量不同，此变量包括在存储的程序中执行的语句。 

    不计算COM_PING 或 COM_STATISTICS 命令。

临时文件和表

可以通过下列命令查看 MySQL 创建临时表和文件的计数

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Created_tmp%';
```

这显示了关于隐式临时表和文件的统计一一执行查询时内部创建。在 Percona Server 中，同样有展示显示临时表（即由用户通过 CREATE TEMPORARY TABLE 所创建）的命令。

```mysql
mysql> SHOW GLOBAL TEMPORARY TABLES
```

句柄操作

句柄 API 是 MySQL 和存储引擎之间的接口。Handler_* 变量用于统计句柄操作，例如 MySQL 请求一个存储引擎来从一个索引中读取下一行的次数。可以通过下列命令查看

```mysql
SHOW GLOBAL STATUS LIKE 'Handler_%'
```

MyISAM 键缓冲

Key_* 变量包含度量值和关于 MyISAM 键缓冲的计数。可以通过下列命令查看这些变量

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Key_%';
```

文件描述符

如果主要使用 MyISAM 存储引擎，Open_* 变量揭示了 MySQL 每隔多久会打开每个表的  .frm ，.MYI 和  .MYD 文件。_

InnoDB 保持所有的数据在表空间文件中，因此如果主要使用 InnoDB，那么这些变量并不精确。可以通过下列命令查看 Open_* 变量

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Open_%'
```

SELECT 类型

Select_* 变量是特定类型的  SELECT 查询的计数器，能帮助了解使用各种查询计划的 SELECT 查询比率。_

没有关于其他查询类型（UPDATE之类）的状态变量。可以看一下 Handler_* 状态变量大致了解非 SELECT 查询的相对数量。

```mysql
mysql> SHOW GLOBAL STATUS LIKE 'Select_%';
```

Select_* 状态变量可以按花费递增的顺序如下排列（如果最后两个变量快速增长，可能表明一个糟糕的查询引入到系统中了）

* Select_range

    在第一个表上扫描一个索引区间的联接数目

* Select_scan

    扫描整个第一张表的联接数目。如果第一个表中每行都参与联接，这样计数并没有问题

* Select_full_range_join

    使用在表 n 中的一个值来从表 n + 1 中通过参考索引的区间内获取行所做的联接数。这个值或多或少比 Select_scan 开销多些，具体多少取决于查询

* Select_range_check

    在表 n + 1 中重新评估表 n 中的每一行的索引是否开销最小所做的联接数。这一般意味着在表 n + 1 中对该联接而言并没有有用的索引。这个查询有非常高的额外开销

* Select_full_join

    交叉联接或并没有条件匹配表中行的连接的数目。检查的行数是每个表中行数的乘积。这通常是个坏事情

排序

当 MySQL 不能使用一个索引来获取预先排序的行时，必须使用文件排序，这会增加 Sort_* 状态变量。

除 Sort_merge_passes 外，可以只是增加 MySQL 会用来排序的索引以改变这些值。

Sort_merge_passes 依赖 sort_buffer_size 服务器变量。MySQL 使用排序缓冲来容纳排序的行块。当完成排序后，它将这些排序后的行合并到结果集中，增加 Sort_merge_passes，并且用下一个待排序的行块填充缓存。

```mysql
SHOW GLOBAL STATUS LIKE 'Sort_%'
```

当 MySQL 从文件排序结果中读取已经排好序的行并返回给客户端时，Sort_scan 和 Sort_range 变量会增长。不同在于：前者是当查询计划导致 Select_scan 增加时增加，而后者是当 Select_range 增加时增加。二者的实现和开销完全一样；仅仅指示导致排序的查询计划类型

表锁

* Table_locks_immediate

    多少表锁可以立即获取

* Table_locks_waited

    多少表锁需要等待

Innodb 相关

Innodb_* 变量展示了 SHOW ENGINE INNODB STATUS 中包含的一些数据。这些变量按名字分组。

这些变量存在于 MySQL 5.0 或更新版本中，它们有重要的副作用：它们会创建一个全局锁，然后在释放该锁之前遍历整个InnoDB缓冲池。同时，另外一些线程也会遇到该锁而阻塞，直到它被释放。这歪曲了一些状态值，比如Threads_running，因此，它们看起来比平常更高（可能高许多，取决于系统此时有多忙）。当运行 SHOW ENGINE INNODB STATUS 或通过 INFORMATION_SCHEMA 表（在MySQL 5.0或更新版本中，SHOW STATUS 和SHOW VARIABLES 与对 INFORMATION_SCHEMA 表的查询在幕后映射了起来）访问这些统计时，有相同的副作用。

插件相关

MySQL 5.1和更新的版本中支持可插拔的存储引擎，并在服务器内对存储引擎提供了注册它们自己的状态和配置变量的机制。如果你在使用一个可插拔的存储引擎，也许会看到许多插件特有的变量。类似的变量总是以插件名开头。

###### processlist

进程列表是当前连接到 MySQL 的连接或线程的清单。show processlist 列出这些线程，以及每个线程的状态信息。

##### INNODB STATUS

没有行和列，分为很多小段，没一段对应 InnoDB 引擎不同部分信息，输出内容包含一些平均值的统计信息，这些平均值是自上次输出结果生成以来的统计数（或内部复位间隔之后统计数）

###### Status

声明了输出开始，其内容包括当前的日期和时间，以及自上次输出以来经过的时长或距离内部复位的时长

###### BACKGROUND THREAD

显示了由后台线程完成的工作，srv_master_thread 行显示了由后台主线程完成的工作

###### SEMAPHORES

线程等待信号量的统计信息，并统计线程需要旋转或等待互斥量或读写锁信号量的次数。spin rounds per wait 行显示每个操作系统等待互斥锁的自旋锁轮数（互斥量指标报告 SHOW ENGINE INNODB MUTEX）

###### TRANSACTIONS

事务信息

* 当前事务 ID
* Innodb 清除旧 MVCC 行时所用的事务 ID，将这个值与当前事务 ID 比较，可以知道有多少老版本的数据未被清除，undo 日志 ID
* 历史记录长度，即位于 InnoDB 数据文件的撤销空间里页面的数目，如果事务执行了更新并提交，这个数字就会增加，而清理进程移除旧数据，它就会递减
* 事务锁信息

###### FILE I/O

显示有关 Innodb 用于执行各种类型的 I/O 的线程的信息，和有关挂起的 I/O 操作的信息和有关 I/O 性能统计的信息

I/O 读写线程数量由 *innodb_read_io_threads* 和 *innodb_write_io_threads* 参数控制。始终会包含 4 类线程（insert buffer thread 插入缓冲合并，log thread 负责异步刷日志，read thread 执行预读操作以尝试预先读取 Innodb 预感需要的数据，write thread 刷脏缓冲）

###### INSERT BUFFER AND ADAPTIVE HASH INDEX

插入缓冲区和（更改缓冲区）和自适应哈希索引的状态

###### LOG

显示 InnoDB 日志的信息，包含日志详细信息

* 当前日志序号（写到日志文件中的字节数，可以用来计算日志缓冲还有多少没有写入到日志文件中）
* 日志刷新位置
* last check point

###### BUFFER POOL AND MEMORY

缓冲池统计信息

* |             Name             |                         Description                          |
    | :--------------------------: | :----------------------------------------------------------: |
    |    Total memory allocated    | The total memory allocated for the buffer pool in bytes.（总大小，字节） |
    | Dictionary memory allocated  | The total memory allocated for the `InnoDB` data dictionary in bytes.（字典分配大小，字节） |
    |       Buffer pool size       | The total size in pages allocated to the buffer pool.（页面总大小，以 innodb_page_size 为单位） |
    |         Free buffers         | The total size in pages of the buffer pool free list（可用列表的页面总大小） |
    |        Database pages        | The total size in pages of the buffer pool LRU list（LRU 列表的页面总大小） |
    |      Old database pages      | The total size in pages of the buffer pool old LRU sublist（老 LRU 子列表的页面总大小） |
    |      Modified db pages       | The current number of pages modified in the buffer pool（当前修改的页面数） |
    |        Pending reads         | The number of buffer pool pages waiting to be read into the buffer pool（等待读入缓冲池的页面数） |
    |      Pending writes LRU      | The number of old dirty pages within the buffer pool to be written from the bottom of the LRU list（从 LRU 列表的底部开始写入的缓冲池中旧脏页数） |
    |  Pending writes flush list   | The number of buffer pool pages to be flushed during checkpointing（检查点期间要刷新的缓冲池页面数） |
    |  Pending writes single page  | The number of pending independent page writes within the buffer pool（缓冲池中暂存的独立页面写入书） |
    |       Pages made young       | The total number of pages made young in the buffer pool LRU list (moved to the head of sublist of “new” pages) |
    |     Pages made not young     | The total number of pages not made young in the buffer pool LRU list (pages that have remained in the “old” sublist without being made young). |
    |           youngs/s           | The per second average of accesses to old pages in the buffer pool LRU list that have resulted in making pages young. See the notes that follow this table for more information. |
    |         non-youngs/s         | The per second average of accesses to old pages in the buffer pool LRU list that have resulted in not making pages young. See the notes that follow this table for more information. |
    |          Pages read          |     The total number of pages read from the buffer pool.     |
    |        Pages created         |  The total number of pages created within the buffer pool.   |
    |        Pages written         |   The total number of pages written from the buffer pool.    |
    |           reads/s            | The per second average number of buffer pool page reads per second. |
    |          creates/s           | The per second average number of buffer pool pages created per second. |
    |           writes/s           | The per second average number of buffer pool page writes per second. |
    |     Buffer pool hit rate     | The buffer pool page hit rate for pages read from the buffer pool memory vs from disk storage. |
    |      young-making rate       | The average hit rate at which page accesses have resulted in making pages young. See the notes that follow this table for more information. |
    |   not (young-making rate)    | The average hit rate at which page accesses have not resulted in making pages young. See the notes that follow this table for more information. |
    |       Pages read ahead       |       The per second average of read ahead operations.       |
    | Pages evicted without access | The per second average of the pages evicted without being accessed from the buffer pool. |
    |      Random read ahead       |   The per second average of random read ahead operations.    |
    |           LRU len            |     The total size in pages of the buffer pool LRU list.     |
    |        unzip_LRU len         |  The total size in pages of the buffer pool unzip_LRU list.  |
    |           I/O sum            | The total number of buffer pool LRU list pages accessed, for the last 50 seconds. |
    |           I/O cur            |   The total number of buffer pool LRU list pages accessed.   |
    |        I/O unzip sum         | The total number of buffer pool unzip_LRU list pages accessed. |
    |        I/O unzip cur         | The total number of buffer pool unzip_LRU list pages accessed |

###### INDIVIDUAL BUFFER POLL INFO

单个 buffer pool 详细信息

###### ROW OPERATIONS

指示主线程在做什么，包括每种行操作的数量和性能比率

###### LATEST FOREIGN KEY ERROR

一般不会出现，除非服务器有外键错误。每次有新错误时，外键错误信息都会被重写

###### LATEST DETECTED DEADLOCK

只有当服务器内有死锁时才出现，死锁错误信息在每次有新错误时都会重写

###### innodb mutex

SHOW ENGINE INNODB MUTEX返回InnoDB互斥体的详细信息，主要对洞悉可扩展性和并发性问题有帮助。每个互斥体都保护着代码中一个临界区

##### PERFORMANCE_SCHEMA

###### schema

开启，配置  my.cnf，或使用 --performanceschema 参数启动，包含指示条件变量、互斥体、读写锁文件IO 实例的表

```ini
[mysqld]
performance_schema=ON
```

* set_instruments 表

    监控点，按需开启

* set_consumers

    与监视点相关的消费者（事件表）

##### INFORMATION_SCHEMA

###### 使用

是一个系统视图集合，其中许多视图与 MySQL 的 SHOW 命令对应。

缺点是视图与相应的 SHOW 命令相比，有时非常慢。它们一般会取所有的数据，存在临时表中，然后使查询可以获取临时表。当服务器上数据量大或表非常多时，查询 INFORMATION_SCHEMA 表会导致非常高的负载，并且会导致服务器对其他用户而言停转或不可响应，因此在一个高负载且数据量大的生产服务器上使用时要小心。

查询时会有危险的表主要是那些包含下列表元数据的表：TABLES，COLUMNS，REFERENTIAL_CONSTRAINTS，KEY_COLUMN_USAGE，等等。对这些表的查询会导致 MySQL 向存储引擎请求获取类似服务器上表的索引统计等数据，而这在 InnoDB 里是非常繁重的。

###### profiling

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

##### replication

###### master status

* show master status

    在主库执行，可显示主库的复制状态和配置

* show binary logs

    显示主库 binlog 列表

* show binlog events

    查看 binlog 日志中的事件

    ```mysql
    # 查看二进制事件
    SHOW BINLOG EVENTS [IN 'log_name'] [FROM pos] [LIMIT [offset,] row_count]
    SHOW BINLOG EVENTS IN 'mysql-bin.000222' FROM 12345\G;
    ```

###### slave status

* show slave status

    查看备考状态

