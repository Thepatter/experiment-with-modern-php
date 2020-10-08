### SQL

#### 语句

* DDL ，Data Definition language，数据库定义语言。用来定义数据库对象，包括数据库，数据表和列。通过使用 DDL，可以创建、删除和修改数据库和表结构
* DML，Data Manipulation Language，数据操作语言，用它操作和数据库相关的记录，增，删，改表中的记录
* DCL，Data Control Language，数据控制语言，用它来定义访问权限和安全级别
* DQL，Data Query Languge，数据查询语言，用它来查询想要的记录

##### 语句执行

###### Oracle 语句执行

SQL 在 Oracle 中执行流程：

1. 语法检查：检查 SQL 拼写是否正确，如果不正确，Oracle 会报语法错误

2. 语义检查：检查 SQL 中的访问对象是否存在。如操作或访问的库表是否存在。语法检查和语义检查的作用是保证 SQL 语句没有错误

3. 权限检查：看用户是否具备访问该数据的权限

4. 共享池检查：共享池 `shared Pool` 是一块内存池，最主要的作用是缓存 SQL 语句和该语句的执行计划。Oracle 通过检查共享池是否存在 SQL 语句的执行计划，来判断进行软解析还是硬解析

    *   软解析

        在共享池中，Oracle 首先对 SQL 语句进行 Hash 运算，然后根据 Hash 值在库缓存（Library Cache）中查找，如果存在 SQL  语句的执行计划，直接进入执行器环节

    *   创建解析树进行解析，生成执行计划，进入优化器步骤

5. 优化器：优化器中就是要进行硬解析，创建解析树，生成执行计划

6. 执行器：当有了解析树和执行计划后，就可以在执行器中执行语句了

共享池是 Oracle 中的术语，包括库缓存，数据字典缓冲区等。库缓存主要缓存 SQL 语句和执行计划。而数据字典缓冲区存储的是 Oracle 中的对象定义，比如表、视图、索引等。当对 SQL  语句进行解析的时候，如果需要相关的数据，会从数据字典缓冲区中提取

##### 查询执行流程

查询语句有几个子句构成，在 MYSQL 中只有 SELECT 子句是必不可少的

| 子句名称 |                   使用目的                   |
| :------: | :------------------------------------------: |
|  SELECT  |          确定结果集中应该包含那些列          |
|   FROM   | 指明所要提取数据的表，以及这些表是如何连接的 |
|  WHERE   |               过滤不需要的数据               |
| GROUP BY |        用于对具有相同列值的行进行分组        |
|  HAVING  |               过滤掉不需要的组               |
| ORDER BY | 按一个或多个列，对最后的结果集中的行进行排序 |

执行流程：

###### 执行流程

1.  先执行 `FROM` 这一步的。在这个阶段，如果是多张表联合查询，还会经历以下几个步骤

    1.  首先通过 `CROSS JOIN` 求笛卡尔积，相当于得到虚拟表 `vt1-1`
    2.  通过 `ON` 进行筛选，在虚拟表 `vt1-1` 的基础上进行筛选，得到虚拟表 `vt1-2`
    3.  添加外部行。如果使用的是左链接，右链接或者全链接，就会涉及到外部行，即在虚拟表 `vt1-2` 的基础上增加外部行 ，得到虚拟表 `vt1-3`
    4.  直到所有表被都被处理完为止

    FROM 子句获取最终的虚拟表 vt1

2.  再进行 `WHERE` 阶段，在这个阶段中，会根据 `vt1` 表的结果进行筛选过滤，得到虚拟表 `vt2`

3.  `GROUP`

    在虚拟表 `vt2` 的基础上进行分组

4.  `HAVING`

    在虚拟表 `vt2` 的基础上进行分组过滤的

5.  `SELECT`

    会提取想要的字段

6.  `DISTINCT`

    过滤掉重复的行

7.  `ORDER BY`

    按照指定的字段进行排序

8.  `LIMIT`

    取出指定行的记录

###### 查询子句

*   select

    支持表达式、列名（别名）、内置函数、自定义函数

*   from

    支持临时表（子查询返回）、普通表、虚拟表、表别名

##### 连接

###### SQL 92 连接

* 笛卡尔积

    笛卡尔积是两个集合的所有可能组合。即交叉连接，`CROSS JOIN`，它的作用是可以把任意表进行连接，即使这两张表不相关。

* 等值连接

    两张表的等值连接就是用两张表中都存在的列进行连接。也可以对多张表进行等值连接

* 非等值连接

    当进行多表查询的时候，如果连接多个表的条件是非等号时，即非等值连接

* 外连接

    除了查询满足条件的记录外，外连接还可以查询某一方不满足条件的记录。两张表的外连接，会有一张表是主表，另一张表是从表。如果是多张表的外连接，那么第一张表是主表，即显示全部的行，而剩下的表则显示对应连接的信息。

    **左外连接**：左边的表是主表，需要显示左边表的全部行，而右侧的表是从表`(+)` 表示哪个是从表。

    **右外连接**：右边的表是主表，显示右边表的全部行，而左侧的表是从表。`LEFT JOIN` 和 `RIGHT JOIN` 只存在于 SQL99 标准，在 SQL 92 中只能使用 `(+)` 标识从表

    ```sql
    # 左连接SQL92
    select * from player, team where player.team_id = team.team_id(+);
    # 右连接SQL92
    select * from player, team where player.team_id(+) = team.team_id;
    # 左连接SQL99
    select * from player left join team on player.team_id = team.team_id;
    # 右连接SQL99
    select * from player right join team on player.team_id = team.team_id;
    ```

* 自连接

    自连接可以对多个表进行操作，也可以对同一个表进行操作。即查询条件使用了当前表的字段

###### SQL 99 连接

*   交叉连接（笛卡尔集）

*   自然连接（NATURAL JOIN）

    两张连接表中所有相同的字段进行等值连接

    ```sql
    # 把 player 表和 team 表进行等值连接，相同的字段是 term_id
    # SQL 92
    SELECT player_id, a.team_id, player_name, height, team_name FROM player as a, team as b where a.team_id = b.team_id
    # SQL 99 使用 NATURAL JOIN 替代了 WHERE player.team_id = team.team_id
    SELECT player_id, team_id, player_name, height, team_name FROM player NATURAL JOIN team
    ```

*   ON 连接

    可以指定连接条件，在 SQL 99 中，需要连接的表会采用 JOIN 进行连接，ON 指定了连接条件，后面可以是等值连接，也可以是非等值连接

    ```sql
    SELECT player_id, player.team_id, player_name, height, team_name FROM player JOIN team ON player.team_id = team.team_id
    ```

*   USING 连接

    使用 USING 指定连接的同名字段进行等值连接

    ```mysql
    SELECT player_id, team_id, player_name, height, team_name FROM player JOIN team USING(team_id)
    ```

    与自然连接不同的是，USING 指定了具体的相同字段名称，需要在 USING 的括号 `()` 中填入要指定的同名字段。使用 JOIN USING 可以简化 JOIN ON 的等值连接

*   外连接

    * 左外连接：LEFT JOIN 或 LEFT OUTER JOIN

    * 右外连接：RIGHT JOIN 或 RIGHT OUTER JOIN

    * 全外连接：FULL JOIN 或 FULL OUTER JOIN

        全外连接是左外连接和右外连接的结合。（MySQL 不支持全外连接）全外连接 = 左右表匹配的数据 + 左表没有匹配的数据 + 右表没有匹配的数据

*   自连接（SQL 92 相同）

    自连接是通过已知的自身数据表进行条件判断，因此在大部分 DBMS 中都对自连接处理进行了优化

##### 子查询

子查询是一种嵌套查询，可以根据子查询是否执行多次，将子查询分为：

*   关联子查询

    如果子查询需要执行多次，即采用循环的方式，先从外部查询开始，每次都传入子查询进行查询，然后再将结果反馈给外部，嵌套的执行方式

    如果子查询的执行依赖于外部查询，通常情况下都是因为子查询中的表用到了外部的表，并进行了条件关联，因为每执行一次外部查询，子查询都要重新计算一次，这样的子查询即是关联子查询，关联子查询，则需要将主查询的字段值传入子查询中进行关联查询

*   非关联子查询：子查询从数据表中查询了数据结果，如果这个数据结果只执行一次，然后这个数据结果作为主查询的条件进行执行

###### EXISTS 子查询

关联子查询通常也会和 `EXISTS` 或 `NOT EXISTS` 一起来使用，EXISTS 子查询用来判断条件是否满足，满足的话为 TRUE，不满足为 FALSE。

某些情况下，EXISTS 和 IN 可以得到相同的效果，具体使用那个执行效率更高，则需要看字段的索引情况以及表 A 和表 B 那个表更大。

```sql
SELECT * FROM A WHERE EXISTS (SELECT cc FROM B WHERE B.cc=A.cc)
```

在 cc 列建立索引的情况下，我们还需要判断表 A 和表 B 的大小。表 A 比表 B 大，IN 子查询的效率要比 EXISTS 子查询效率高。如果表 A 比表 B 小，那么使用 EXISTS 效率更高

###### 集合子查询

集合比较子查询的作用是与另一个查询结果集进行比较，可以在子查询中使用 IN、ANY、ALL、SOME 操作符

* IN

    判断是否在集合中

    ```mysql
    select player_id, team_id, player_name from player where player_id in (select player_id from player_score where player.player_id = player_score.player_id)
    ```

    在 `player_id` 列存在索引时，player 表比 player_score 大，in 查询效率更高，反之 exists 效率更高

* ANY

    需要与比较操作符一起使用，与子查询返回的任何值做比较

    ```mysql
    select player_id, player_name, height from player where height > any (select height from player where team_id = 1002)
    ```

* ALL

    需要与比较操作符(>, =, <, >=, <=, <>)一起使用，与子查询返回的所有值做比较

    ```mysql
    select player_id, player_name, height from player where height > all (select height from player where team_id = 1002)
    ```

* SOME

    ANY 的别名

ANY，ALL 关键字必须与一个比较操作符一起使用，如果不使用比较操作符，就起不到集合比较的作用。

###### 将子查询作为计算字段

子查询也可以作为主查询的计算字段

```mysql
select team_name, (select count(*) from player where player.team_id = team.team_id) as player_num from team
```

#### 事务

##### 特性

* Atomicity 原子性

    不可分割，事务要么全部执行，要么全部不执行

* Consistency 一致性

    数据库在进行事务操作后，会由原来的一致状态，变成另一种一致状态。即事务提交或回滚后，数据库完整性约束不被破坏

* Isolation 隔离性

    每个事务彼此独立，不受其他事务的执行影响

* Durability 持久性

    事务提交之后对数据的修改是持久性的，即使在系统出故障的情况下，数据修改依然有效。持久性通过事务日志来保证

###### 事务隔离级别

SQL-92 中定义了事务并发处理时的异常情况：

*   脏读（读到其他事务还没有提交的数据）
*   不可重复读（同一事务中对某条数据进行读取，两次读取的结果不一致，其他事务对这个数据进行更新）不可重复读是同一条记录的内容被修改了，重点在与 UPDATE 或 DELETE
*   幻读（事务 A 根据条件查询得到了 N 条数据，同时事务 B 更改或者增加了 M 条符合事务 A 查询条件的数据，这样当事务 A 再次进行查询的时候发现会有 N + M 条数据，产生了幻读）幻读是查询某一个范围的数据行变多了或少了，重点在于 INSERT。即 SELECT 显示不存在，但 INSERT 的时候发现已存在，说明符合条件的数据行发生了变化

SQL-92 标准还定义了 4 种隔离级别来解决这些异常情况

|     隔离级别     | 脏读可能性 | 不可重复读可能性 | 幻读可能性 | 加锁读 |
| :--------------: | :--------: | :--------------: | :--------: | :----: |
| READ UNCOMMITTED |    Yes     |       Yes        |    Yes     |   No   |
|  READ COMMITED   |     No     |       Yes        |    Yes     |   No   |
| REPEATABLE READ  |     No     |        No        |    Yes     |   No   |
|   SERIALIZABLE   |     No     |        No        |     No     |  Yes   |

* 读未提交

    允许读到未提交的数据，这种情况下查询是不会使用锁的，可能会产生脏读、不可重复读、幻读等情况

* 读已提交

    只能读到已提交的内容，可以避免脏读的产生，（SQL Server 和 Oracle 的默认隔离级别），但如果想要避免不可重复读或幻读，就需要在 SQL 查询的时候编写带加锁的 SQL 语句

    一个事务从开始直到提交前，所做的任何修改对其他事务都是不可见的。这个级别有时候也叫做不可重复读，因为两次执行同样的查询，可能会得到不一样的结果

* 可重复读

    保证一个事务在相同查询条件下两次查询得到的数据结果是一致的，可以避免不可重复读和脏读，但无法避免幻读。MySQL 默认隔离级别就是可重复读

* 可串行化

    将事务进行串行化，即在一个队列中按照顺序执行，可串行化是最高级别的隔离等级，可以解决事务读取中所有可能出现的异常情况，但牺牲了系统的并发性

```sql
# 查看当前事务隔离级别
SHOW VARIABLES LIKE 'transaction_isolation'
# 设置事务隔离级别
SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;
```

##### 事务执行

###### 控制语句

* `START TRANSACTION` 或 `BEGIN`，显式开启一个事务，在 MySQL 连续 BEGIN，当开启了第一个事务时，还没有进行 COMMIT 提交时，直接进行第二个事务的 BEGIN，这时数据库会隐式地 COMMIT 第一个事务，然后再进入到第二个事务

* `COMMIT` 提交事务

* `ROLLBACK` 或 `ROLLBACK TO [SAVEPOINT]`

    回滚事务（撤销正在进行的所有没有提交的修改），回滚到某个保存点。ROLLBACK 是针对当前事务的

* `SAVEPOINT`

    在事务中创建保存点，方便后续针对保存点进行回滚，一个事务中可以存在多个保存点

* `RELEASE DAVEPOINT`

    删除某个保存点

* `SET TRANSACTION`

    设置事务的隔离级别

关于事务的 ACID，在使用 COMMIT 和 ROLLBACK 来控制事务的时候，在一个事务的执行过程中可能会失败。遇到失败的时候是进行回滚，还是将事务执行过程中已经成功操作的来进行提交，这个逻辑需要开发者自决：这里开发者可以决定，如果遇到了小错误是直接忽略，提交事务，还是遇到任何错误都进行回滚。如果强行进行 COMMIT，数据库会将这个事务中成功的操作进行提交

###### 显式与隐式事务

隐式事务实际上就是自动提交，Oracle 默认不自动提交，需要手写 COMMIT 命令，而 MySQL 默认自动提交，可以配置 MySQL 参数

```sql
# 关闭自动提交
mysql>set autocommit = 0;
# 开启自动提交
mysql>set autocommit = 1;
```

* 当 `autocommit = 0` 时，不论是否采用 `START TRANSACTION` 或 `BEGIN` 的方式来开启事务，都需要用 `COMMIT` 进行提交，让事务生效，使用 `ROLLBACK` 对事务进行回滚

* 当 `autocommint = 1` 时，每条 SQL 语句都会自动进行提交。此时，需要采用 `START TRANSACTION` 或 `BEGIN` 的方式来显式地开启事务，这个事务只有在 `COMMIT` 时才会生效，在 `ROLLBACK` 时才会回滚

MySQL 中的 `completion_type` 参数的作用

* `completion_type = 0` or `completion_type = NO_CHAIN`

    默认情况。当执行 `COMMIT` 的时候会提交事务，在执行下一个事务时，还需要使用 `START TRANSACTION` 或者 `BEGIN` 来开启。

* `completion_type = 1` or  `completion_type = COMMIT AND CHAIN`

    提交事务后立即开启一个链式事务，即提交事务后会开启一个相同隔离级别的事务

* `completion_type = 2` or `completion_type = COMMIT AND REPLEASE`

    这种情况下 `COMMIT = COMMIT AND REPLEASE`，提交后，会自动断开服务器连接

#### 游标

提供了一种灵活的操作方法，可以从数据结果集中每次提取一条数据记录进行操作。

SQL 中，游标是一种临时的数据库对象，可以指向存储在数据库表中的数据行指针。这里游标充当了指针的作用，可以通过操作游标来对数据行进行操作

##### 游标操作

###### 使用游标

使用游标，一般需要五个步骤，不同 DBMS 中，使用游标的语法可能略有不同

1.  定义游标

    *   适用于 MySQL、SQL Server、DB2、MariaDB

        ```sql
        DECLARE [cursor_name] CURSOR FOR [select_statement]
        ```

    *   适用于 Oracle、PostgreSQL，要使用 SELECT 语句来获取数据结果集

        ```sql
        DECLARE [cursor_name] CURSOR IS [select_statement]
        ```

2.  打开游标

    ```sql
    OPEN [cursor_name]
    ```

    使用游标必须先打开游标。打开游标的时 SELECT 语句的查询结果集就会送到游标工作区

3.  从游标中取得数据

    ```sql
    FETCH [cursor_name] INTO [var_name]...
    ```

    使用 `cursor_name` 这个游标来读取当前行，并且将数据保存到 `var_name` 这个变量中，游标指针指向下一行。如果游标读取的数据行有多个列名，则在 INTO 关键字后面赋值给多个变量名。

    当游标溢出时（当游标指向到最后一行数据后继续执行会报错）。可以定义一个 `continue` 的事件，指定这个事件发生时修改变量 `done` 的值，以此来判断游标是否已经溢出

    ```sql
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;
    ```

4.  关闭游标

    ```sql
    CLOSE [cursor_name]
    ```

    使用完游标后需要关闭该游标。关闭后，就不能再检索查询结果中的数据行，如果需要检索只能再次打开游标

5.  释放游标

    ```sql
    DEALLOCATE PREPARE [cursor_name]
    ```

    如果不释放游标，游标会一直存在于内存中，直到进程结束后才会自动释放。

###### 应用场景

* 需要找特定数据，用 SQL 查询写起来会比较困难，如两表或多表之间的嵌套循环查找，如果用 JOIN 会非常消耗资源，效率也不高，而用游标则会比较高效

* 游标会带来一些性能问题，在使用游标的过程中，会对数据行进行加锁。而且因为游标是在内存中进行的处理，还会造成内存不足

#### 范式

##### 键与范式

###### 键定义

|    键    |                             定义                             |
| :------: | :----------------------------------------------------------: |
|   超键   |                    能唯一表示元组的属性集                    |
|  候选键  |        如果超键不包括多余的属性，那么这个超键即候选键        |
|   主键   |              用户可以从候选键中选择一个作为主键              |
|   外键   | 如果数据表 R1 中的某属性集不是 R1 的主键，而是另一个数据表 R2 的主键，那么这个属性集就是数据表 R1 的外键 |
|  主属性  |               包含在任一候选键中的属性为主属性               |
| 非主属性 |        与主属性相对，即不包含在任何一个候选键中的属性        |

##### 范式定义

数据库的范式设计越高阶，冗余度就越低。高阶的范式一定符合低阶范式的要求。设计表时，要根据需要进行范式与反范式结合使用

###### 分类

目前关系数据库一个有六种范式，按照范式级别从低到高分别是：

*   1 NF（第一范式）

    指的是数据库表中的任何属性都是原子性的，不可再分

    即设计某个字段的时候，对于字段 x 来说，不能把字段 X 拆分成字段 X-1 和 字段 X-2。即任何 DBMS 都满足第一范式要求。需要保障表中每个属性都保持原子性

*   2 NF (第二范式)

    2 NF 指的是数据表里的非主属性都要和这个数据表的候选键有完全依赖关系

    不能仅依赖候选键的一部分属性，而必须依赖全部属性。某种程度上 2 NF 是对 1 NF 原子性的升级。1 NF 定义原子性的字段，2 NF 定义一张表就是一个独立的对象。需要保证表中的非主属性与候选键完全依赖

*   3 NF（第三范式）

    在满足 2 NF 的同时，对任何非主属性都不传递依赖于候选键

    即不能存在非主属性 A 依赖与非主属性 B，非主属性 B 依赖于候选键的情况。3 NF 需要保证表中的非主属性与候选键不存在传递依赖

*   BCNF (巴斯 - 科德范式)

    3 NF 基础上消除主属性对于候选键的部分依赖或传递依赖

*   4 NF（第四范式）

*   5 NF（第五范式）

###### 三范式不足

即使数据表符合 3NF，同样可能存在插入、更新、删除的异常情况

###### 反范式

*   范式的目的是降低数据冗余度，反范式会增加数据冗余度
*   多表关联时，要提升查询效率，可能需要适当冗余度
*   数据仓库通常会采用反范式设计

#### 存储过程

存储过程与视图一样，都是对 SQL 代码进行封装，可以反复利用。存储过程是程序化的 SQL，可以直接操作底层数据表。存储过程是由 SQL 语句和流控制语句构成的语句集合，可以接收参数，也可以返回输出参数给调用者，返回计算结果

##### 存储过程适用场景

###### 存储过程优点

* 存储过程可以一次编译多次使用。存储过程只在创造时进行编译，之后的使用都不需要重新编译，这就提升了 SQL 的执行效率

* 设定存储过程的时候可以设置对用户的使用权限，安全性较强

* 可以减少网络传输量

###### 存储过程缺点

* 可移植性差，不能跨数据库移植

* 调试较困难，开发维护难度大

* 不能进行版本控制，数据表索引发生了变化，可能会导致存储过程失效

* 不适合高并发场景

##### 语法

```sql
DELIMITER |
CREATE PROCEDURE [procedure_name]()
BEGIN
    [SQL]
END;
DELIMITER ;
```

* 使用 `create procedure [proceducr_name]()` 语法创建

* 如果存储过程接受参数，将在 `()` 中列举出来。

* `BEGIN` 和 `END` 语句用来限定存储过程体，过程体是流控制语句与SQL

* `DELIMITER` 作用

    默认情况下 SQL 采用 `;` 分号作为结束符，这样当存储过程中的每一句 SQL 结束之后，采用 `;` 作为结束符，就相当于告诉 SQL 可以执行这一句。但是存储过程是一个整体，不希望 SQL 逐条执行，而是采用存储过程整段执行的方式，因此需要临时定义新的 DELIMIETER。如果使用的是 Navicat 工具，在编写存储过程的时候，Navicat 会自动设置 DELIMITER 为其他符号

* 存储过程的 3 种参数类型

    IN 参数类型，不会返回，向存储过程传入参数，存储过程中修改该参数的值，不能被返回；

    OUT 参数类型，会返回，把存储过程计算的结果放到该参数中，调用者可以得到返回值

    INOUT 会返回，IN 和 OUT 的结合，即用于存储过程的传入参数，同时又可以把计算结果放到参数中，调用者可以得到返回值

###### 流控制语句

  * `BEGIN...END`

    BEGIN...END 中间包含了多个语句，每个语句都以 `;` 分号为结束符

  * `DECLARE`

    声明变量，用于 BEGIN...END 语句中间，需要在其他语句使用之前声明

  * `SET`

    赋值语句，用于对变量进行赋值

  * `SELECT...INTO`

    把从数据表中查询的结果存放到变量中，即为变量赋值

  * `IF...THEN...ENDIF`

    条件判断语句，可以在 `IF...THEN..ENDIF` 中使用 `ELSE` 和 `ELSEIF` 来进行条件判断

  * `CASE`

    CASE 语句同于多条件的分支判断，ELSE 为所有条件都不满足时

    ```sql
    CASE
    	WHEN expression1 
    		THEN
    	WHEN expression2 
    		THEN
    	ELSE
    	    THEN
    END
    ```

  * `LOOP`、`LEAVE`、`ITERATE`

    LOOP 是循环语句，使用 LEAVE 可以跳出循环，使用 ITERATE 则可以进入下一次循环

  * `REPEAT...UBTIL...END REPEAT`

    循环语句，首先会执行一次循环，然后在 UNTIL 中进行表达式判断，如果满足条件就进行循环，不满足条件就退出循环

  * `WHILE...DO...END WHILE`

    循环语句，和 REPEAT 循环不同的是，这个语句需要先进行条件判断，如果满足条件就进行循环，不满足条件就退出循环

##### 使用

```MYSQL
# mysql 对存储过程使用为调用
CALL [procedure_name](@INPARAM, @OUTPARAM, @INOUTPARAM);
# 删除存储过程
DROP PROCEDURE IF EXISTS {procedure_name};
# 更新
ALTER PROCEDURE
# 查看存储过程
SHOW CREATE PROCEDURE {PROCEDURE_NAME}
# 显示存储过程状态
SHOW PROCEDURE STATUS
```

###### 参数

一般存储过程并不显示结果，而是把结果返回给指定的变量。每个参数必须具有指定的类型(允许的数据类型与表中使用的数据类型相同，记录集不是允许的类型，因此，不能通过一个参数返回多个行和列)，显示检索出来的变量 `select @out_value_name`

MySQL 支持 IN （传递给存储过程），OUT（从存储过程传出），INOUT（对存储过程传入和传出）类型的参数

```mysql
CREATE PROCEDURE productpricing(
	OUT pl DECIMAL(8,2),
	OUT ph DECIMAL(8,2),
	OUT pa DECIMAL(8,2)
)
BEGIN
	SELECT Min(prod_price) INTO pl FROM products;
	SELECT Max(prod_price) INTO ph FROM products;
	SELECT Avg(prod_price) INTO pa FROM products;
END;
```

#### 语法相关

##### 关键字 375 个

|      ACCESSIBLE      |       ACTION        |           ADD           |       AFTER       |            AGAINST            |
| :------------------: | :-----------------: | :---------------------: | :---------------: | :---------------------------: |
|      ALGORITHM       |         ALL         |          ALTER          |      ANALYZE      |              AND              |
|         ANY          |         AS          |           ASC           |    ASENSITIVE     |              AT               |
|    AUTO_INCREMENT    |   AVG_ROW_LENGTH    |         BACKUP          |      BEFORE       |             BEGIN             |
|      BENCHMARK       |       BETWEEN       |         BINLOG          |        BIT        |             BOOL              |
|         BOTH         |         BY          |          CACHE          |       CALL        |            CASCADE            |
|       CASCADED       |        CASE         |         CHANGE          |     CHARACTER     |            CHARSET            |
|        CHECK         |      CHECKSUM       |         CLIENT          |      COLLATE      |           COLLATION           |
|        COLUMN        |       COLUMNS       |         COMMIT          |     COMMITTED     |          COMPLETION           |
|      CONCURRENT      |     CONNECTION      |       CONSISTENT        |    CONSTRAINT     |            CONVERT            |
|       CONTAINS       |      CONTENTS       |         CREATE          |       CROSS       |             DATA              |
|       DATABASE       |      DATABASES      |        DAY_HOUR         |  DAY_MICROSECOND  |          DAY_MINUTE           |
|      DAY_SECOND      |     DEALLOCATE      |           DEC           |      DEFAULT      |            DEFINER            |
|       DELAYED        |   DELAY_KEY_WRITE   |         DELETE          |       DESC        |         DETERMINISTIC         |
|   DELIMITER(mysql)   |      DIRECTORY      |         DISABLE         |      DISCARD      |           DESCRIBE            |
|       DISTINCT       |     DISTINCTROW     |           DIV           |       DROP        |             DUAL              |
|       DUMPFILE       |      DUPLICATE      |          EACH           |       ELSE        |            ELSEIF             |
|        ENABLE        |      ENCLOSED       |           END           |       ENDS        |            ENGINE             |
|       ENGINES        |       ESCAPE        |         ESCAPED         |      ERRORS       |             EVENT             |
|        EVENTS        |        EVERY        |         EXECUTE         |      EXISTS       |           EXPANSION           |
|       EXPLAIN        |        FALSE        |         FIELDS          |       FILE        |             FIRST             |
|        FLOAT4        |       FLOAT8        |          FLUSH          |        FOR        |             FORCE             |
|       FOREIGN        |        FROM         |          FULL           |     FULLTEXT      |           FUNCTION            |
|      FUNCTIONS       |       GLOBAL        |          GRANT          |      GRANTS       |             GROUP             |
|        HAVING        |        HELP         |      HIGH_PRIORITY      |       HOSTS       |       HOUR_MICROSECOND        |
|     HOUR_MINUTE      |     HOUR_SECOND     |       IDENTIFIED        |      IGNORE       |       IGNORE_SERVER_IDS       |
|        INDEX         |       INFILE        |          INNER          |       INOUT       |          INSENSITIVE          |
|        INSERT        |    INSERT_METHOD    |         INSTALL         |       INT1        |             INT2              |
|         INT3         |        INT4         |          INT8           |      INTEGER      |             INTO              |
|      IO_THREAD       |         IS          |        ISOLATION        |      INVOKER      |             JOIN              |
|         KEY          |        KEYS         |          KILL           |       LAST        |            LEADING            |
|        LEAVES        |        LEVEL        |          LESS           |       LIKE        |             LIMIT             |
|        LINEAR        |        LINES        |          LIST           |       LOAD        |             LOCAL             |
|         LOCK         |        LOGS         |          LONG           |   LOW_PRIORITY    |            MASTER             |
|        MASTER        |     MASTER_HOST     | MASTER_HEARTBEAT_PERIOD |  MASTER_LOG_FILE  |        MASTER_LOG_POS         |
| MASTER_CONNECT_RETRY |   MASTER_PASSWORD   |       MASTER_PORT       |    MASTER_SSL     |         MASTER_SSL_CA         |
|  MASTER_SSL_CAPATH   |   MASTER_SSL_CERT   |    MASTER_SSL_CIPHER    |  MASTER_SSL_KEY   | MASTER_SSL_VERIFY_SERVER_CERT |
|     MASTER_USER      |        MATCH        |        MAX_ROWS         |     MAXVALUE      |           MIDDLEINT           |
|       MIN_ROWS       | MINUTE_MICROSECOND  |      MINUTE_SECOND      |        MOD        |             MODE              |
|        MODIFY        |      MODIEIES       |          NAMES          |      NATURAL      |              NEW              |
|          NO          |      NODEGROUP      |           NOT           | NO_WRITE_TOBINLOG |             NULL              |
|       NUMERIC        |         OJ          |         OFFSET          |        OLD        |              ON               |
|       OPTIMIZE       |       OPTION        |       OPTIONALLY        |       OPEN        |              OR               |
|        ORDER         |         OUT         |          OUTER          |      OUTFILE      |           PACK_KEYS           |
|       PARTIAL        |      PARTITION      |       PARTITIONS        |    PERSISTENT     |            PLUGIN             |
|       PLUGINS        |      PERCISION      |         PERPARE         |     PRESERVE      |            PRIMARY            |
|      PRIVILEGES      |      PROCEDURE      |         PROCESS         |    PROCESSLIST    |             PURGE             |
|        QUERY         |     RAID_CHUNKS     |     RAID_CHUNKSIZE      |     RAID_TYPE     |             RANGE             |
|         READ         |        READS        |       READ_WRITE        |       REAL        |            REBUILD            |
|      REFERENCES      |       REGEXP        |     RELAY_LOG_FILE      |   RELAY_LOG_POS   |            RELEASE            |
|        RELOAD        |       RENAME        |       REORGANIZE        |      REPAIR       |          REPEATABLE           |
|       REPLACE        |     REPLICATION     |         REQUIRE         |     RESIGNAL      |           RESTRICT            |
|        RESET         |       RESTORE       |         RETURN          |      RETURNS      |            REVOKE             |
|        RLIKE         |      ROLLBACK       |         ROLLUP          |      ROUTINE      |              ROW              |
|      ROW_FORMAT      |        ROWS         |        SAVEPOINT        |      SCEDULE      |            SCHEMA             |
|       SCHEMAS        | SECOND_MICROSECOND  |        SECURITY         |      SELECT       |           SENSITIVE           |
|      SEPARATOR       |    SERIALIZABLE     |         SESSION         |        SET        |             SHARE             |
|         SHOW         |      SHUTDOWN       |         SIGNAL          |      SIMPLE       |             SLAVE             |
|       SNAPSHOT       |        SOME         |         SONAME          |     SPECIFIC      |              SQL              |
|     SQLEXCEPTION     |      SQLSTATE       |       SQLWARNING        |  SQL_BIG_RESULT   |       SQL_BUFFER_RESULT       |
|      SQL_CACHE       | SQL_CALC_FOUND_ROWS |      SQL_NO_CACHE       | SQL_SMALL_RESULT  |            SPATIAL            |
|      SQL_THREAD      |         SSL         |          START          |     STARTING      |            STARTS             |
|        STATUS        |        STOP         |         STORAGE         |   STRAIGHT_JOIN   |         SUBPARTITION          |
|    SUBPARTITIONS     |        SUPER        |          TABLE          |      TABLES       |          TABLESPACE           |
|      TEMPORARY       |     TERMINATED      |          THAN           |       THEN        |              TO               |
|       TRAILING       |     TRANSACTION     |         TRIGGER         |     TRIGGERS      |             TRUE              |
|         TYPE         |     UNCOMMITTED     |          UNDO           |     UNINSTALL     |            UNIQUE             |
|        UNLOCK        |      UNSIGNED       |         UPDATE          |      UPGRADE      |             UNION             |
|        USAGE         |         USE         |          USING          |      VALUES       |         VARCHARACTER          |
|      VARIABLES       |       VARYING       |          VIEW           |      VIRTUAL      |           WARNINGS            |
|         WHEN         |        WHERE        |          WITH           |       WORK        |             WRITE             |
|         XOR          |     YEAR_MONTH      |        ZEROFILL         |       CLOSE       |           CONDITION           |
|       CONTINUE       |       CURSOR        |         DECLARE         |        DO         |             EXIT              |
|        FETCH         |        FOUND        |          GOTO           |      HANDLER      |            ITERATE            |
|       LANGUAGE       |        LEAVE        |          LOOP           |       UNTIL       |             WHILE             |

##### 函数

|         函数          |                             作用                             |
| :-------------------: | :----------------------------------------------------------: |
|        `ABS()`        |                           取绝对值                           |
|        `MOD()`        |                             取余                             |
|       `ROUND()`       |                  四舍五入为指定的小数点位数                  |
|      `CONCAT()`       |                     将多个字符串拼接起来                     |
|      `LENGTH()`       |                计算字段的长度，汉字算三个字符                |
|    `CHAR_LENGTH()`    |         计算字段的长度，汉字，数字，字母都算一个字符         |
|       `LOWER()`       |                  将字符串中的字符转化为小写                  |
|       `UPPER()`       |                  将字符串中的字符转化为大写                  |
|      `REPLACE()`      | 替换函数，有 3 个参数：要替换的表达式或字段名、想要查找的被替换字符串、替换成那个字符串 |
|     `SUBSTRING()`     | 截取字符串，有 3 个参数：待截取的表达式或字段名、开始截取的位置、想要截取的字符串长度 |
|   `CURRENT_DATE()`    |                         系统当前日期                         |
|   `CURRENT_TIME()`    |                 系统当前时间，没有具体的日期                 |
| `CURRENT_TIMESTAMP()` |                 系统当前时间，包括日期+时间                  |
|      `EXTRACT()`      |                     抽取具体的年、月、日                     |
|       `DATE()`        |                      返回时间的日期部分                      |
|       `YEAR()`        |                      返回时间的年份部分                      |
|       `MONTH()`       |                      返回时间的月份部分                      |
|        `DAY()`        |                      返回时间的天数部分                      |
|       `HOUR()`        |                      返回时间的小时部分                      |
|      `MINUTE()`       |                      返回时间的分钟部分                      |
|      `SECOND()`       |                       返回时间的秒部分                       |
|   `FROM_UNIXTIME()`   |                                                              |
|  `UNIX_TIMESTAMP()`   |                                                              |
|       `CAST()`        | 数据类型转换，参数是一个表达式，表达式通过 AS 关键词分割  2 个参数，分别是原始数据和目标数据类型 |
|     `COALESCE()`      |                      返回第一个非空数值                      |
|                       |                                                              |
|                       |                                                              |
|                       |                                                              |
|                       |                                                              |

