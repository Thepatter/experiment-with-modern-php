### SQL 语句执行相关

#### 查询语句相关

##### JOIN 相关

在可以使用被驱动表的索引情况下：使用 join 语句，性能比强行拆成多个单表执行 SQL 语句的性能要好。如果使用 join 语句的话，需要让小表做驱动表，在 join 语句执行过程中，驱动表是全表扫描

可以使用 `stright_join` 指定驱动方式，优化器会按指定的方式连接

```mysql
// 指定 t1 是驱动表，t2 是被驱动表
select * from t1 straight_join t2 on (t1.a=t2.a)
```

###### Join 执行

*   Index Nested-Loop Join 索引嵌套循环执行流程：

    遍历驱动表，取出每一行去被驱动表做连接字段匹配。此时被驱动表的连接字段上有索引，执行索引树搜索。执行时间 `O(n*logn)`，扫描行数（去除索引搜索和回表操作）：驱动表行数与被驱动表行数之和

*   Simple Nested-Loop Join

    被驱动表用不上索引的情况下（Simple Nested-Loop Join），此时连接匹配每次会在被驱动表做全表扫描，执行时间为：`O(n*m)`，扫描行数为：驱动表行数与被驱动表行数之积

*   Block Nested-Loop Join

    对于被驱动表上关联字段没有索引时，将被驱动表读入线程内存 `join_buffer`，扫描驱动表，从驱动表中取出每一行与 `join_buffer` 中数据对比。

    在这个过程中，对表都会做全表扫描，总扫描行数是两者之和。由于 `join_buffer` 是以无序数组的方式组织的，因此在内存中做的判断次数是两者之积。

    `Simple Nested-Loop Join`  和 `Block Nested-Loop Join` 算法时间复杂度一致，区别在于磁盘扫描和内存扫描

    `join_buffer` 由 `join_buffer_size` 设定的，默认值是 256k。如果放不下被驱动表的所有数据，就会分段放，此时会分段放入被驱动表的部分数据与驱动表数据对比，然后清空 `join_buffer` 放入下一段被驱动表数据。此时应使用小表做驱动表

###### join语句使用场景判断

* 如果可以使用 `Index Nested-Loop Join` 算法，即可以用上被驱动表上的索引，此时使用 `join` 会提升效率
* 如果使用 `Block Nested-Loop Join` 算法，扫描行数就会过多。尤其是在大表上的 `join` 操作，这样可能要扫描被驱动表很多次，会占用大量的系统资源。所以这种 `join` 尽量不要用。即观察 `explain` 结果里面，`Extra` 字段里面有没有出现 `Block Nested Loop` 

###### join 语句优化

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

    5.6 版本后开始引入地 `Batched Key Acess(BKA)` 算法。如果要使用 BKA 优化算法，需要在执行 SQL 语句之前，先设置

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

###  join 的写法

#### left join 

如果用 left join 的话，左边的表一定是驱动表吗？

如果两个表的 join 包含多个条件的等值匹配，是都要写到 on 里面呢，还是只把一个条件写到 on 里面，其他条件写到 where 部分？

构建测试表

```mysql
create table a(f1 int, f2 int, index(f1))engine=innodb;
create table b(f1 int, f2 int) engine=innodb;
insert into a values(1,1),(2,2),(3,3),(4,4),(5,5),(6,6);
insert into b values(3,3),(4,4),(5,5),(6,6),(7,7),(8,8);
```

表 a 和 b 都有两个字段 f1 和 f2，不同的是表 a 的字段 f1 上有索引。然后，往两个表中都插入了 6 条记录，其中在表 a 和 b 中同时存在的数据有 4 行

上面第二个问题，其实就是下面这两种写法的区别

```mysql
select * from a left join b on(a.f1=b.f1) and (a.f2=b.f2);  /*Q1*/
select * from a left join b on(a.f1=b.f1) where (a.f2=b.f2);  /*Q2*/
```

这两个 left join 语句的语义逻辑并不相同。

![](C:/Users/z/notes/MIddleware/MySQL/Images/Performance/leftjoin使用on与where的结果.png)

* 语句 Q1 返回的数据集是 6 行，表 a 中即使没有满足匹配条件的记录，查询结果也会返回一行，并将表 b 的各个字段值填成 NULL
* 语句 Q2 返回的是 4 行。最后的两行，由于表 b 中没有匹配的字段，结果集里面 b.f2 的值为空，不满足 where 部分的条件判断，因此不能作为结果集的一部分

*left join 多条件 on 的 explain 分析*

![](C:/Users/z/notes/MIddleware/MySQL/Images/Performance/leftjoin多条件on的explain.png)

分析结果为：驱动表是表 a，被驱动表是表 b；由于表 b 的 f1 字段上没有索引，所以使用 Block Nexted Loop Join 算法，因此这条语句的执行流程是：

1.把表 a 的内容读入 `join_buffer` 中。因为是 `select *` ，所以字段 f1 和 f2 都被放入 `join_buffer` 了。

2.顺序扫描表 b，对于每一行数据，判断 join 条件（a.f1 = b.f1 and a.f2=b.f2）是否满足，满足条件的记录，作为结果集的一行返回。如果语句中有 where 子句，需要先判断 where 部分满足条件后，再返回

3.表 b 扫描完成后，对于没有被匹配的表 a 的行，把剩余的字段补上 NULL，再放入结果集中

*left join -BNL 算法流程图*

![](C:/Users/z/notes/MIddleware/MySQL/Images/Performance/left_join_Block_Nexted_Loop算法流程图.png)

即，这条语句确实是以表 a 为驱动表，而且从执行效果看，也和使用 `straight_join` 是一样的

![](C:/Users/z/notes/MIddleware/MySQL/Images/Performance/left_join的多条件等值匹配where分析.png)

这条语句是以表 b 为驱动表的。而如果一条 join 语句的 Extra 字段什么都没写的话，就表示使用的是 `Index Nested-Loop Join` 算法。因此，语句 Q2 的执行流程是：顺序扫描表 b，每一行用 b.f1 到表 a 中去查，匹配到记录后判断 a.f2=b.f2 是否满足，满足条件的话就作为结果集的一部分返回。

Q1 和 Q2 这两个查询的执行流程差距是因为优化器基于 Q2 这个查询的语义做了优化。语句 Q2 里面 `where a.f2=b.f2` 就表示，查询结果里面不会包含 b.f2 是 null 的行，这样这个 left join 的语义就是：找到这两个表里面，f1，f2 对应相同的行，对于表 a 中存在，而表 b 中匹配不到的行，就放弃。因此，这条语句虽然用的是 `left join`，但是语义跟 join 是一致的。优化器把这条语句的 `left join` 改写成了 `join`，然后因为表 a 的 f1 上有索引，就把表 b 作为驱动表，这样就可以用上 NLJ 算法。在执行 `explain` 之后，可以执行`show warnings` 查看这个改写的结果

*优化器优化left join结果*

![](C:/Users/z/notes/MIddleware/MySQL/Images/Performance/优化器优化leftjoin为join查询.png)

即，使用 `left join` 时，左边的表不一定是驱动表。**如果需要 left join 的语义，就不能把被驱动表的字段放在 where 条件里面做等值判断或不等值判断，必须都写在 on 里面。join 将判断条件是否全部放在 on 部分没有区别**



