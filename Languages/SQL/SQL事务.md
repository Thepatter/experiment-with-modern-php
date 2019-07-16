### SQL 事务

#### 事务概览

![](./Images/事务处理.png)

#### 事务特性ACID

* Atomicity 原子性

  不可分割，事务要么全部执行，要么全部不执行

* Consistency 一致性

  数据库在进行事务操作后，会由原来的一致状态，变成另一种一致状态。即事务提交或回滚后，数据库完整性约束不被破坏

* Isolation 隔离性

  每个事务彼此独立，不受其他事务的执行影响

* Durability 持久性

  事务提交之后对数据的修改是持久性的，即使在系统出故障的情况下，数据个修改依然有效。持久性通过事务日志来保证

#### 事务控制

Oracle 支持事务，MySQL 中，部分引擎支持事务，可以通过 `SHOW ENGINES` 命令来查看当前 MySQL 支持的引擎有那些及是否支持事务。

##### 事务控制语句

* `START TRANSACTION` 或 `BEGIN`，显式开启一个事务

* `COMMIT` 提交事务

* `ROLLBACK` 或 `ROLLBACK TO [SAVEPOINT]`

  回滚事务（撤销正在进行的所有没有提交的修改），回滚到某个保存点

* `SAVEPOINT`

  在事务中创建保存点，方便后续针对保存点进行回滚，一个事务中可以存在多个保存点

* `RELEASE DAVEPOINT`

  删除某个保存点

* `SET TRANSACTION`

  设置事务的隔离级别

##### 显式事务与隐式事务

隐式事务实际上就是自动提交，Oracle 默认不自动提交，需要手写 COMMIT 命令，而 MySQL 默认自动提交，可以配置 MySQL 参数

```sql
# 关闭自动提交
mysql>set autocommit = 0;
# 开启自动提交
mysql>set autocommit = 1;
```

* 当 `autocommit = 0` 时，不论是否采用 `start tansaction` 或 `begin` 的方式来开启事务，都需要用 `COMMIT` 进行提交，让事务生效，使用 `ROLLBACK` 对事务进行回滚

* 当 `autocommint = 1` 时，每条 SQL 语句都会自动进行提交。此时，需要采用 `START TRANSACTION` 或 `BEGIN` 的方式来显式地开启事务，这个事务只有在 `COMMIT` 时才会生效，在 `ROLLBACK` 时才会回滚

MySQL 中的 `completion_type` 参数的作用 `set @@completion_type = 1`

* `completion_type = 0`

  默认情况。当执行 COMMIT 的时候会提交事务，在执行下一个事务时，还需要使用 `START TRANSACTION` 或者 `BEGIN` 来开启。

* `completion_type = 1`

  提交事务后，相当于执行了 `COMMIT AND CHAIN`，即开始了一个链式事务，即提交事务后会开启一个相同隔离级别的事务

* `completion_type = 2`

  这种情况下 `COMMIT = COMMIT AND REPLEASE`，提交后，会自动断开服务器连接

