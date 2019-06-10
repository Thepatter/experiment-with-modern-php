### 基本使用

#### 更新表 `alter table`

#### 删除表 `drop table`

#### 重命名表 `rename table source_table to target_table`

#### 视图

* 视图用 `create view` 语句来创建

  ```mysql
  create view productcustomers as select cust_name, cust_contact, prod_id from customers, orders, orderitems where customers.cust_id = orders.cust_id and orderitems.order_null = orders.order_num
  ```

### 字符集和校对顺序

* 字符集：为字母和符号的集合
* 编码：为某个字符集成员的内部表示
* 校对：为规定字符如何比较的指令

#### 使用字符集和校对

* 查看所支持字符集的完整列表

  ```mysql
  show character set
  ```

* 查看校对的完整列表

  ```mysql
  show collation
  ```

  有的字符具有不止一种校对，而且许多校对出现两次，依次区分大小写（由`_cs` 表示），依次不区分大小写（由 `_ci` 表示）

* 通常系统管理在安装时定义一个默认的字符集和校对。此外，也可以在创建数据库时，指定默认的字符集和校对。查看字符集和校对

  ```mysql
  # 查看字符集
  show variables like 'character%';
  # 查看校对集
  show variables like 'collation%';
  ```

* 给表和列指定字符集和校对

  ```mysql
  CREATE TABLE table_name(
  	  column_name int,
  	  column_nam1 char(10),
      column_name2 varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci
  ) DEFAULT CHARACTER SET hebrew COLLACTE hebrew_general_ci;
  ```

* 如果指定 `CHARACTER SET` 和 `COLLATE` 两者，则使用这些值

* 如果只指定 `CHAEACTER SET` ，则使用此字符集及其默认的校对（`SHOW CHARACTER SET`)

* 如果未指定 `CHARACTER SET` 也未指定 `COLLATE` ，则使用数据库默认

* 查询时使用不同的校对(`SELECT,GROUP BY, HAVING，聚合参数，别名`)中使用

  ```mysql
  SELECT * FROM customers ORDER BY lastname, firstname COLLATE latin1_general_cs
  ```

* 串可以在字符集之间进行转换，使用 `Cast()` 或 `Convert()` 函数


#### 数据备份

* 刷新所有未写数据 `flush tables`

##### 数据库维护

* `analyze table` 用来检查表键是否正确。
* `check table` 支持一系列用于 `myisam` 表的方式
* `changed` 检查自最后一次检查以来改动过的表
* `extended` 执行最彻底的检查
* `fast` 只检查未正常关闭的表
* `medium` 检查所有被删除的链接并进行键检验
* `quick` 只进行快速扫描
* 如果 `myisam` 表访问产生不正确和不一致的结果，可能需要用 `repair table` 来修复相应的表。这条语句不应该经常使用，如果需要经常使用，可能会有更大的问题要解决
* 如果从一个表中删除大量数据吗，应该使用 `optimize table` 来收回所用的空间，从而优化表的性能

##### 日志文件

* 错误日志，包含启动和关闭问题以及任意关键错误的细节。此日志通常名为 `hostname.err` 位于 `data` 目录中，此日志名可用 `--log-error` 命令行选项更改
* 查询日志。它记录所有 MySQL 活动，此日志通常名为 `hostname.log` 位于 `data` 目录中。可用 `--log` 命令行选项更改
* 二进制日志。它记录更新过数据（或者可能更新过数据）的所有语句，此日志通常名为 `hostname-bin` 位于 `data` 目录内。可用 `--log-bin` 命令行选项更改
* 慢查询日志。记录慢查询，通常为 `hostname-slow.log` 位于 `data` 目录内，可用 `--log-slow-queries` 命令行选项更改。
* 可用 `flush logs` 语句来刷新和重新开始所有日志文件

##### 常用性能改善

* 关键生产的 `DBMS` 应该运行在自己的专用服务器上。
* 查看当前设置，`show variables` `show status`,`show processlist` 显示所有活动进程。
* 使用 `explain` 语句让 Mysql 解释它将如何执行一条 select 语句
* 正确数据类型
* 绝不要检索比需求还要多的数据，换言之，不要用 `select *`
* select 语句中有一系列复杂的 or 条件，可用使用多条 sql 并使用 union 连接，可以提升性能