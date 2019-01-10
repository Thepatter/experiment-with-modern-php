## `MySQL` 主备相关

### `mysql` 主备结构

​	*主备切换流程*

![](./Images/MySQL主备切换流程.png)

在状态 1 中，客户端的读写都直接访问节点 A，而节点 B 是 A 的备库，只是将 A 的更新都同步过来，到本地执行。这样可以保持节点 B 和 A 的数据是相同的。当需要切换的时候，就切成状态 2。这时候客户端读写访问的是节点 B，而节点 A 是 B 的备库。

#### 节点 A 到 B 这条线的内部流程是：

​			*update 语句在节点 A 执行，然后同步到节点 B 的流程图*

![](./Images/MySQL备份流程.png)

主库接收到客户端的更新请求后，执行内部事务的更新逻辑，同时写 `binlog`。备库 B 跟主库 A 之间维持了一个长连接。主库 A 内部有一个线程，专门用于服务备库 B 的长连接。一个事务日志同步的完整过程是：

1.在备库 B 上通过 `change master` 命令，设置主库 A 的 IP、端口、用户名、密码、以及要从哪个位置开始请求 `binlog`，这个位子包含文件名和日志偏移量

2.在备库 B 上执行 `start slave` 命令，这时候备库会自动启动两个线程，即（`io_thread` 和 `sql_thread`）。其中 `io_thread` 负责与主库建立连接

3.主库 A 校验完用户名，密码后，开始按照备库 B 传过来的位置，从本地读取 `binlog`，发给 B

4.备库 B 拿到 `binlog` 后，写到本地文件，称为中转日志（`relay log`）

5.`sql_thread` 读取中转日志，解析出日志里的命令，并执行

后来由于多线程复制方案的引入，`sql_thread` 演化成多线程。

#### `binlog` 三种格式

`sql` 语句

```mysql
delete from t /*comment*/ where a>=4 and t_modified<='2018-11-10' limit 1;
```

##### `binlog_format = 'statement'` 格式是记录 `sql` 语句

​	*`statement`格式 `binlog`*

![](./Images/binlog的statement格式.png)

第二行的 `Begin` ，跟第四行的 `commit` 对应，表示中间是一个事务

第三行是真实执行的语句，在真实执行的 `delete` 命令之前，还有一个 `use test` 命令。这条命令不是主动执行的，而是 `MySQL` 根据当前要操作的表所在的数据库，自行添加的。这样做可以保证日志传到备库去执行的时候，不论当前的工作线程在那个库里，都能正确地更新到 `test` 库的表 `t`，`binlog` 会将注释一起记录下来

最后一行是一个 `COMMIT` 

##### 当`binlog_format = 'row'` 时的 `binlog`

​	*`row`格式`binlog`*

![](./Images/binlog的row格式.png)

与 `statement` 格式的 `binlog` 相比，前后的 `BEGIN` 和 `COMMIT` 是一样的，但是 `row` 格式的 `binlog` 里没有了 `SQL` 语句的原文，而是替换成了两个 `event`：`Table_map` 和 `Delete_rows`

`Table_map_event`，用于说明接下来要操作的表是 `test` 库的表 t

`Delete_rows_event`，用于定义删除的行为

使用 `mysqlbinlog` 工具来解析查看 `binlog` 中的内容（这个事务的 `binlog` 是从 8900 这个位置开始的）

```mysql
mysqlbinlog -vv data/master.00001 --start-position=8900;
```

​		*`row` 格式 `binlog` 示例的详细信息*

![](./Images/row格式binlog详细信息.png)

其中信息如下：

`server id 1` ，表示这个事务是在 `server_id = 1` 的这个库上执行

每个 `event` 都有 `CRC32` 的值，这是因为参数 `binlog_checksum` 值为 `CRC32`

`Table_map` 显示了要打开的表，`map` 到数字 226，如果要操作多张表，每个表都有一个对应的 `Table_map_event`，都会 `map` 到一个单独的数字，用于区分对不同表的操作

`mysqlbinlog` 的命令中，`-vv` 参数是为了把内容都解析处理，所以结果里可以看到各个字段的值

`binlog_row_image` 的默认配置是 `FULL`，因此 `Delete_event` 里面，包含了删掉的行的所有字段的值。如果把 `binlog_row_image` 设置为 `MINIMAL`，则只会记录必要的信息（此语句中只会记录 `id = 4` ）

`Xid event` 表示事务被正确提交了

当 `binlog_format` 使用 `row` 格式的时候，`binlog` 里面记录了真实删除行的主键 `id`，这样 `binlog` 传到备库去的时候，就会删除 `id=4` 的行，不会有主备删除不同行的问题

##### `mixed` 格式

* 有些 `statement` 格式的 `binlog` 可能会导致主备不一致，所以要使用 `row` 格式
* 但是 `row` 格式的缺点是，很占空间。（如果用 `delete` 语句删掉 10 万行数据，用 `statement` 的话就是一个 `SQL` 语句被记录到 `binlog` 中，占用几十个字节空间。但如果用 `row` 格式的 `binlog` ，就要把这 10 万条记录都写到 `binlog` 中。这样，不仅会占用更大的空间，同时写 `binlog` 也要耗费 `IO` 资源，影响执行速度）
* `mixed` 格式的意思是，`MySQL` 自己会判断这条 `SQL` 语句是否可能引起主备不一致，如果有可能，就用 `row` 格式，否则就用 `statement` 格式。即，`mixed` 格式可以利用 `statement` 格式的优点，用时又避免数据不一致的风险

#### `binlog` 格式最佳实践

**即如果线上 `MySQL` 设置的 `binlog` 格式是 `statement` 的话，基本可以认为这是一个不合理的设置。至少应该把 `binlog` 的格式设置为 `mixed` 但是越来越多的场景要求把 `MySQL` 的 `binlog` 格式设置成 `row`，直接好处是恢复数据**

* 如果执行的是 `delete` 语句，`row` 格式的 `binlog` 也会把删掉的行的整行信息保存起来。所以在执行完一条 `delete` 语句以后，发现删除错误了，可以直接把 `binlog` 中记录的 `delete` 语句转成 `insert` ，把被错删的数据插入回去就可以恢复了
* 如果执行错了 `insert` 语句，`row` 格式下，`insert` 语句的 `binlog` 里会记录所有的字段信息，这些信息可以用来精确定位刚刚被插入的那一行。这是，直接把 `insert` 语句转成 `delete` 语句，删除掉这被误插入的一行数据就可以了
* 如果执行的是 `update` 语句，`binlog` 里面会记录修改前整行的数据和修改后的整行数据。如果误执行 `update` 语句的话，只需要把这个 `event` 前后的两行信息对调一下，再去数据库里执行，就能恢复这个更新操作

#### 使用 `binlog` 恢复数据

如果用 `mysqlbinlog解析`出日志，然后把里面的 `statement` 直接拷贝出来执行的话，会存在数据一致风险，因为这些语句的执行结果是依赖于上下文命令的，直接执行的结果很可能是错误

用 `binlog` 来恢复数据的标准做法是，用 `mysqlbinlog` 工具解析出来，然后把解析结果整个发给 `MySQL` 执行，类似如下指令

```mysql
// 将 `master.000001` 文件里面从第2738字节到第2973字节中间这段内容解析出来，放到MySQL去执行
mysqlbinlog master.000001  --start-position=2738 --stop-position=2973 | mysql -h127.0.0.1 -P13000 -u$user -p$pwd;
```

### 双主结构

​	*MySQL主备双M结构*

​	![](./Images/MySQL主备双M结构.png)

双 M 结构和 M-S 结构，区别是多了一条线，即，节点 A 和 B 之间总是互为主备关系。这样在切换的时候就不用再修改主备关系

#### 双 M 结构循环复制问题

业务逻辑在节点 A 上更新了一条语句，然后再把生成的 `binlog` 发给节点 B，节点 B 执行完这条更新语句后也会生成 `binlog`。（配置参数 `log_slave_updates` 为 on，则备库执行 `reley log` 后生成 `binlog`）

如果节点 A 同时是节点 B 的备库，相当于又把节点 B 新生成的 `binlog` 拿过来执行了一次，然后节点 A 和 B 间，会不断地循环执行这个更新语句，即循环复制。

#### 解决循环复制

* 规定两个库的 `server id` 必须不同，如果相同，则它们之间不能设定为主备关系

* 一个备库连接到 `binlog` 并在重放的过程中，生成与原 `binlog` 的 `server id` 相同的新的  `binlog`

* 每个库在收到从自己的主库发过来的日志后，先判断 `server id` ，如果跟自己的相同，表示这个日志是自己生成的，就直接丢弃这个日志。此结构的日志执行流程：

  从节点 A 更新的事务，`binlog` 里面记的都是 A 的 `server id`，传到节点 B  生成的 `binlog` 的 `server id` 也是 A 的 `server id`，再传回给节点 A，A 判断到这个 `server id` 与自己的相同，就不会再处理这个日志。