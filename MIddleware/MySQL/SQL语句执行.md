### SQL 语句执行相关

#### 查询语句相关

##### JOIN 相关

在可以使用被驱动表的索引情况下：使用 join 语句，性能比强行拆成多个单表执行 SQL 语句的性能要好。如果使用 join 语句的话，需要让小表做驱动表，在 join 语句执行过程中，驱动表是全表扫描

可以使用 `stright_join` 指定驱动方式，优化器会按指定的方式连接

```mysql
// 指定 t1 是驱动表，t2 是被驱动表
select * from t1 straight_join t2 on (t1.a=t2.a)
```

`LEFT JOIN` 时，左边的表不一定是驱动表，如果需要 `LEFT JOIN` 的语义，就不能把被驱动表的字段放在 where 条件里面做等值判断或不等值判断，必须都写在 on 里面。`JOIN` 将判断条件是否全部放在 on 部分没有区别

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



