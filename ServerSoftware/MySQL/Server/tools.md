### mysqld tools

#### Server Tools

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

#### 内置工具

##### Explain

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
|  select_type  |                 指示对应行是简单还是复杂查询                 |
|     table     |                    指示对应行正在访问的表                    |
|     type      |             关联类型，MySQL 决定如何查找表中的行             |
| possible_keys | 指示查询可以使用的索引，基于查询访问的列和使用的比较操作符来判断，该列表是优化早期创建，有些罗列出来的索引可能对于后续优化过程没用 |
|      key      | 指示 MySQL 决定采用那个索引访问表，如果该索引未出现在 possible_keys 列中，选择可能出于别的原因 |
|    key_len    | 指示 MySQL 在索引里使用的字节数（使用的索引中字段的字节长度和） |
|               |                                                              |
|               |                                                              |
|               |                                                              |
|               |                                                              |

