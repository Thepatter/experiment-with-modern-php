### SQL 语句执行相关

#### 查询语句相关

##### 执行流程

###### 逻辑查询处理

1.  FROM

    对 FROM 子句中左表和右表执笛卡尔积，产生虚拟表 VT1，包含左表和右表乘积行数据，列由源表定义

2.  ON

    对虚拟表 VT1 应用 ON 筛选，只有那些符合 join condition 的行才被插入虚拟表 VT2 中。对于 ON 过滤条件下的 NULL 值比较，此时的比较结果为 UNKNOWN（ORDER BY 子句把所有 NULL 值排列在一起）产生虚拟表 VT2 时，会增加一个额外的列表示 ON 过滤条件的返回值，返回值有 TRUE、FALSE、UNKNOWN，取出 TRUE 的记录，产生虚拟表 VT2

    *   添加外部行

        只有在连接类型为 OUTER JOIN 时才发生：LETF OUTER JOIN 把左表作为保留表，RIGHT OUTER JOIN 把右表作为保留表，FULL OUTER JOIN 把左右表都记为保留表。添加外部行的工作就是在 VT2 表的基础上添加保留表中被过滤条件过滤掉的数据，非保留表中的数据被赋予 NULL 值，最后生成虚拟表 VT3

3.  JOIN

    如果指定了 OUTER JOIN（如 LEFT OUT JOIN、RIGHT OUTER JOIN），那么保留表中未匹配的行作为外部行添加到虚拟表 VT2 中，产生虚拟表 VT3。如果 FROM 子句包含两个以上表，则对上一个连接生成的结果表 VT3 和 下一个表重复执行步骤 1～3，直到处理完所有的表为止

4.  WHERE

    对虚拟表 VT3 应用 WHERE 过滤条件，只有符合 <where_condition> 的记录才被插入虚拟表 VT4 中

    在应用 WHERE 过滤器时，有两种过滤是不被允许的：

    *   由于数据还没有分组，现在还不能在 WHERE 过滤器中使用 where_condition=MIN(col) 这类统计过滤
    *   由于没有进行列的选取操作，在 SELECT 中使用的列的别名也是不被允许的

    对于 OUTER JOIN 中的过滤，在 ON 过滤器之后还会添加保留表中被 ON 条件过滤掉的记录，而 WHERE 条件中被过滤掉的记录则是永久的过滤，**在 INNER JOIN 中两者没有差别，因为没有添加外部行的操作**

5.  GROUP BY

    根据 GROUP BY 子句中的列，对 VT4 中的记录进行分组操作，产生 VT5，GROUP BY 子句把所有 NULL 值分到同一组

6.  CUBE ｜ ROLLUP

    对表 VT5 进行 CUBE 或 ROLLUP 操作，产生表 VT6

7.  HAVING

    对虚拟表 VT6 应用 HAVING 过滤器，只有符合 <having_condtion> 的记录才被插入虚拟表 VT7 中。HAVING 是对分组条件进行过滤的筛选器，子查询不能用做分组的聚合函数（`HAVING COUNT(SELECT ...）< 2` 非法

8.  SELECT

    执行 SELECT 操作，选择指定的列，插入到虚拟表 VT8 中。列的别名不能再 SELECT 中的其他别名表达式中使用

9.  DISTINCT

    去除重复数据，产生虚拟表 VT9。会创建一个临时表（内存存放不下则放在磁盘上），临时表结构与 VT8 相同，不同的是对进行 DISTINCT 操作的列增加了一个唯一索引，以此来去除重复数据，对于使用了 GROUP BY 的查询，再使用 DISTINCT 是多余的，已经进行分组，不会移除任何行

10.  ORDER BY

     将虚拟表 VT9 中的记录按照 <order_by_list> 进行排序操作，产生虚拟表 VT10。可以在 ORDER BY 子句中指定 SELECT 列表中列的序列号（一般不推荐，除非对网络带宽要求）

     不要为表中的行假定任何特定的顺序，在实际使用环境中，如果确实需要有序输出行记录，必须使用 ORDER BY 子句。在 ORDER BY 子句中，NULL 值被认为是相同的值，会将其排序在一起。NULL 值在升序过程中总是首先被选出，即 NULL 值在 ORDER BY 子句中被视为最小值

11.  LIMIT

     取出指定行的记录，产生虚拟表 VT11，并返回给查询用户

执行相关语句分配的内存在执行完成后就会释放，不会在连接保存。

###### 物理查询处理



##### JOIN

在可以使用被驱动表的索引情况下：使用 join 语句，性能比强行拆成多个单表执行 SQL 语句的性能要好。如果使用 join 语句的话，需要让小表做驱动表，在 join 语句执行过程中，驱动表是全表扫描

可以使用 `stright_join` 指定驱动方式，优化器会按指定的方式连接

```mysql
// 指定 t1 是驱动表，t2 是被驱动表
select * from t1 straight_join t2 on (t1.a=t2.a)
```

`LEFT JOIN` 时，左边的表不一定是驱动表，如果需要 `LEFT JOIN` 的语义，就不能把被驱动表的字段放在 where 条件里面做等值判断或不等值判断，必须都写在 on 里面。`JOIN` 将判断条件是否全部放在 on 部分没有区别

###### Join 执行

###### 联接算符

是 MySQL 数据库用于处理联接的物理策略，目前 MySQL 数据库仅支持 Nested-Loop Join 算符，MariaDB 还支持 Classic Hash Join 算法

*   Index Nested-Loop Join 索引嵌套循环执行流程：

    联接的表上有索引时。其联接的时间复杂度为 O(N)，若没有索引，则最坏复杂度为 O(n^2) 遍历驱动表，取出每一行去被驱动表做连接字段匹配。此时被驱动表的连接字段上有索引，执行索引树搜索。执行时间 `O(n*logn)`，扫描行数（去除索引搜索和回表操作）：驱动表行数与被驱动表行数之和

    INNER JOIN 中可以使用索引下推的优化方式，但不能直接在 OUTER JOIN 中使用该方式，有些不满足联接条件的记录会通过外部表行的方式再次添加到结果中

*   Simple Nested-Loop Join

    被驱动表用不上索引的情况下（Simple Nested-Loop Join），此时连接匹配每次会在被驱动表做全表扫描，执行时间为：`O(n*m)`，扫描行数为：驱动表行数与被驱动表行数之积

*   Block Nested-Loop Join

    Simple Nested-Loops Join 算法在内层循环时，外部表的每行记录需要读取内部表一次，在内部表的联接上有索引的情况下，其扫描成本为 O(Rn)；如果没有索引，则扫描成本为 O（Rn * Sn）。如果内部表 S 有相当多的记录，则 Simple Nested-Loops Join 算法会扫描内部表很多次。

    Block Nested-Loops Join 对于被驱动表上关联字段没有索引时，将被驱动表读入线程内存 `join_buffer`，扫描驱动表，从驱动表中取出每一行与 `join_buffer` 中数据对比。

    在这个过程中，对表都会做全表扫描，总扫描行数是两者之和。由于 `join_buffer` 是以无序数组的方式组织的，因此在内存中做的判断次数是两者之积。

    `Simple Nested-Loop Join`  和 `Block Nested-Loop Join` 算法时间复杂度一致，区别在于磁盘扫描和内存扫描
    
    `join_buffer` 由 `join_buffer_size` 设定的，默认值是 256k。如果放不下被驱动表的所有数据，就会分段放，此时会分段放入被驱动表的部分数据与驱动表数据对比，然后清空 `join_buffer` 放入下一段被驱动表数据。此时应使用小表做驱动表

MySQL 使用 Join Buffer 原则：

系统变量 `join_buffer_size` 决定 Join Buffer 的大小，Join Buffer 可被用于联接上 all、Index、range 的类型，每次联接使用一个 Join Buffer，多表的联接可以使用多个 Join Buffer。Join Buffer 在联接发生之前进行分配，在 SQL 语句执行完后释放。只存储需要进行查询操作的相关列数据，而不是整行记录

SQL 执行计划的 Extra 列中的 Using join buffer，代表使用了 Block Nested-Loops Join 算法，5.6 会详细指示 Using join buffer（Block Nested-Loop）。在 5.5 版本中，Join Buffer 只能在 INNER JOIN 中使用，在 OUTER JOIN 则不能使用。5.6 和 MariaDB 5.3 开始，在 OUTER JOIN 中使用 Join Buffer 受到支持。5.6 中优化了 Join Buffer 在多张表之间联接的内存使用效率，MySQL 5.6 将 Join Buffer 分为 Regular join buffe 和 Incremental join buffer：假设 B1 是表 t1 和 t2 联接使用的 Join Buffer，B2 是 t1 和 t2 联接产生的结果和表 t3 进行联接使用的 Join Buffer，如果 B2 是 Regular join buffer，那么 B2 就会包含 B1 的 Join Buffer 中表 vt1 相关的列，以及表 t3 中相关的列。如果 B2 是 Incremental join buffer，那么 B2 包含表 t2 中的数据及一个指针，该指针指向 B1 中 vt1 相对应的数据。对于第一次联接的表，使用都是 Regular join buffer，之后再联接，则使用 Incremental join buffer。

###### join语句使用场景判断

* 如果可以使用 `Index Nested-Loop Join` 算法，即可以用上被驱动表上的索引，此时使用 `join` 会提升效率
* 如果使用 `Block Nested-Loop Join` 算法，扫描行数就会过多。尤其是在大表上的 `join` 操作，这样可能要扫描被驱动表很多次，会占用大量的系统资源。所以这种 `join` 尽量不要用。即观察 `explain` 结果里面，`Extra` 字段里面有没有出现 `Block Nested Loop` 

###### join 语句优化

要加快 JOIN 的执行速度：加快每次 search-for-match（查询比较）操作的速度（添加索引，让优化器选择索引来进行 search-for-match），search-for-match 根据 group（组，使用 Block Nested-Loops Join）算法来进行，减少对内部表的访问次数

*   Multi-Range Read 优化，尽量使用顺序读盘。

    大多数的数据都是按照主键递增顺序插入得到的，如果按照主键的递增顺序查询的话，对磁盘的读接近顺序读，能够提升读性能。此时，语句的执行流程变成了这样：

    1.  根据被驱动表索引，定位到满足条件的记录，将 id 值放入 `read_rnd_buffer` 中；
    2.  将 `read_rnd_buffer` 中的 id 进行递增排序
    3.  排序后的 id 数组，依次到主键 id 索引中查记录，并作为结果返回

    `read_rnd_buffer` 的大小是由 `read_rnd_buffer_size` 参数控制的，如果容量不够会分块执行

    ```mysql
    # 当前优化器策略，判断耗时，会更倾向于不使用 MRR，把 mrr_cost_based 设置成 off，就是固定使用 MRR
    set optimizer_switch="mrr_cost_based=off"
    ```

    MRR 能够提升性能地核心在于，在这条查询语句在被驱动表索引上是一个范围查询（多值查询），可以得到足够多地主键 id。这样通过排序以后，再去主键索引查数据，才能体现出顺序性地优势

*   Batched Key Access

    5.6 版本后开始引入地 `Batched Key Acess(BKA)` 算法。如果要使用 BKA 优化算法，需要在执行 SQL 语句之前，先设置。该算法的思想为结合索引的 group 这两种方法（Simple Nested-Loops Join 和 Block Nested-Loops Join 只能使用一种）来提供 search-for-match 的操作，以此加快联接的执行效率。

    算法的工作步骤如下：
    
    1.  将外部表中相关的列放入 Join Buffer 中
2.  批量地将 key 索引健值发送到 Multi-Range Read 接口
    3.  Multi-Range Read 通过收到的 key，根据其对应 ROWID （主键）进行排序，然后再进行数据的读取操作
    4.  返回结果集给客户端
    
    本质是通过 Multi-Range Read 接口将非主键索引对于记录的访问，转化为根据 ROWID 排序的较为有序的记录获取，所以要想通过 Batched Key Access Join 算法来提高性能
    
    ```mysql
    set optimizer_switch='mrr=on,mrr_cost_based=off,batched_key_access=on';
    ```
    
    `join_buffer` 中放入的数据是查询需要的字段，如果 `join buffer` 容量不够，则多段放入
    
    

MRR 与 BKA 优化都是针对被驱动表上能使用索引的情况

*   BNL 转 BKA

    1.  一些情况下，可以直接在被驱动表上建索引，这时可以直接转成 BKA 算法了
    2.  如果不创建索引，可以将被驱动表过滤结果创建临时表，并在关联字段上增加索引，使用临时表作为被驱动表扩展 -hash join

*   hash join

    当前 8.0 版本已经支持 hash join，此时 `join_buffer` 中存储的是 hash 结构，此时 BNL 在 `join_buffer` 进行的关联判断使用 hash 而不是全表扫描

##### Order By

使用以下方法确定排序语句是否使用了临时文件

```sql
/* 打开 optimizer_trace，只对本线程有效 */
SET optimizer_trace = 'enabled=on';
/* @a 保存 Innodb_rows_read 的初始值 */
select VARIABLE_VALUE into @a from performance_schema.session_status where variable_name = 'Innodb_rows_read';
/* 执行语句 */
select city，name，age from t where city='杭州' order by name limit 1000;
/* 查看 OPTIMIZER_TRACE 输出 filesort_summary 信息确定是否使用了临时文件排序 */
select * from `information_schema`.`OPTIMIZER_TRACE`\G
/* @b 保存 Innodb_rows_read 的当前值 */
select VARIABLE_VALUE into @b from perfromance_schema.session_status where variable_name = 'Innodb_rows_read';
/* 计算 Innodb_rows_read 差值，获取扫描行数 */
select @b-@a;
```

通过查看 OPTIMIZER_TRACE 结果来确认是否使用了外部排序（`number_of_tmp_files` 即排序过程中使用的临时文件数量，sort_mode 排序模式，examined_rows 参与排序的行数）或观察服务器状态来判断是否使用了外部排序

###### 内部排序算法

*   全字段排序

    在执行线程分配 sort_buffer 大小内存，将查询结果集中 select 的字段放入 sort_buffer 中，对 order by 字段进行排序。排序过程可能在内存中完成，也可能需要使用外部排序（如果排序数据量超过 sort_buffer_size 则会使用临时文件排序）优先选择

*   rowid 排序

    对于单行长度太大的数据，使用全字段排序效率会很低。 mysql 使用 `max_length_for_sort_data` 参数来控制用于排序的行数据的长度的参数，如果要排序的单行长度超过这个值，会使用 rowid 排序算法。该算法放入 sort_buffer 中的字段只有排序的字段和主键。执行流程比全字段排序多一次聚族索引访问

###### 利用索引消除排序

根据索引的有序性

#### 管理语句

##### Kill

在 MySQL 中有两个 kill 命令：

- 一个是 `kill query + 线程 id`，表示终止这个线程中正在执行的语句；
- 一个是 `kill connection + 线程 id`，这里 `connection` 可缺省，表示断开这个线程的连接，如果这个线程有语句正在执行，会先停止正在执行的语句的

使用了 `kill` 命令，却没能断开这个连接。再执行 `show processlist` 命令，这条语句的 `Command` 列显示的是 `killed`

其实大多数情况下，`kill query/connection` 命令是有效的。执行一个查询的过程中，发现执行时间太久，要放弃继续查询，这时我们可以用 `kill query` 命令，终止这条查询语句。

还有一种情况是，语句处于锁等待的时候，直接使用 `kill` 命令也是有效的。kill 并不是马上停止的意思，而是告诉执行线程，这条语句已经不需要继续执行了，可以开始执行停止逻辑了。当用户执行 `kill query thread_id_B` 时，`mysql` 里处理 `kill` 命令的线程做了两件事：

1.把 `session B` 的运行状态改成 `THD::KILL_QUERY`(将变量 `killed` 赋值为: `THD::KILL_QUERY`)

2.给 `session B` 的执行线程发一个信号

`session B` 处于锁等待状态，如果只是把 `session B` 的线程状态设置 `THD:KILL_QUERY`，线程 B 并不知道这个状态变化，还是会继续等待，发一个信号的目的，就是让 `session B` 退出等待，来处理这个 `THD::KILL_QUERY` 状态

上面的分析中，隐含了这么三层意思：

1.一个语句执行过程中有多处“埋点”，在这些“埋点”的地方判断线程状态，如果发现线程状态是 `THD:KILL_QUERY`，才开始进入语句终止逻辑

2.如果处于等待状态，必须是一个可以被唤醒的等待，否则根本不会执行到“埋点”处

3.语句从开始进入终止逻辑，到终止逻辑完全完成，是一个过程

如果一个线程的状态是 `KILL_CONNECTION`，就把 `Command` 列设置成 `killed`。客户端虽然断开了连接，但实际上服务端这条语句还在执行过程中，线程没有执行到判断线程状态的逻辑。跟这种情况相同的，还有由于 IO 压力过大，读写 IO 的函数一直无法返回，导致不能及时判断线程的状态

终止逻辑耗时较长。这时候，从 `show processlist` 结果上看也是 `Command=killed`，需要等到终止逻辑完成，语句才算真正完成。这类情况，比较常见的场景有以下几种

1.超大事务执行期间被 `kill`。这时候，回滚操作需要对事务执行期间生成的所有新数据版本做回收操作。耗时很长

2.大查询回滚。如果查询过程中生成了比较大的临时文件，加上此时文件系统压力大，删除临时文件可能需要等待 IO 资源，导致耗时较长

3.DDL 命令执行到最后阶段，如果被 `kill`，需要删除中间过程的临时文件，如果此时文件系统压力大，删除临时文件可能需要等待 IO 资源，导致耗时较长

直接在客户端执行 `Ctrl+C` 的话。同样会指向线程终止相关操作



