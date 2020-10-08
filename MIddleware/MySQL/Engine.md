### Engine

#### 引擎特性

##### 引擎设置

建表时可以指定数据表引擎。省略时使用默认的存储引擎（5.5 之后为 InnoDB）。可以使用 `--default-storage-engine` 启动项或 `default-storage-engine` 配置项设置默认引擎

```mysql
CREATA TABLE t1 (i int) ENGINE = INNODB;
ALTER TABLE t ENGINE = MEMORY
SET default_storage_engine=NDBCLUSTER;
```

如果使用未编译或已禁用但未编译的存储引擎，则 MySQL 会使用默认存储引擎创建一个表，此时会产生一个警告（启用 `NO_ENGINE_SUBSTITUTION` SQL 模式会产生错误，且不会创建或修改表）

MySQL 可能将表的索引和数据存储在一个或多个其他文件中，具体取决于存储引擎。表和列的定义存储在 MySQL 数据字典中。各个存储引擎会创建它们管理的表所需的任何其他文件。如果表名包含特殊字符，则表文件的名称包含这些字符的编码版本

##### 可插拔存储引擎体系结构

使用可插拔的存储引擎体系结构，使存储引擎可以从运行中的 MySQL 服务器加载或卸载

###### 加载存储引擎

使用存储引擎前，必须使用 INSTALL PLUGIN 语句将存储引擎插件 so 文件加载到 MySQL 中。so 文件必须位于 MySQL 的插件目录中（该位置由 plugin_dir 系统变量指定，默认 `/usr/lib/mysql/plugin/`）。且操作用户必须具有权限

```mysql
INSTALL PLUGIN {example} SONAME '{ha_example.so}';
```

###### 卸载存储引擎

使用 UNINSTALL PLUGIN 语句卸载，卸载后现有使用该引擎的表将不可访问，但仍存储在磁盘上

```mysql
UNINSTALL PLUGIN {example}
```

#### MyISAM

每个 MyISAM 表都以两个文件存储在磁盘上，以表名开头，数据文件以 `.MYD`（MYData）结尾，索引文件以 `.MYI`（MYIndex）结尾，表定义存储在 MySQL 数据字典中

##### 特性

5.1 及之前的版本中，是默认的存储引擎

*   支持：B 树索引、备份基于时间点恢复、压缩数据（必须使用 compressed row 格式，且仅用于只读表）加密数据（通过加密函数在服务层实现）、全文索引搜索、地理空间、索引缓存、表锁、复制（服务器非引擎实现）、存储限制 256T、更新数据字典的统计信息

*   不支持：集群数据库、集群索引、数据缓存、外键、哈希索引、MVCC、T 树索引、事务、分区（早期版本创建的分区 MyISAM 表不能在 8.0 中使用）

*   所有数据都使用低字节存储。所有数组健值对都先存储高字节。在支持大文件的文件系统和操作系统上，支持大文件（文件长度最大为 63 位）

*   最大索引数为 64，索引的最大列数为 16。最大密钥长度为 1000 字节，对于超过 250 字节的密钥，将使用比默认值 1024 字节更大的密钥块大小。当按排序顺序插入行时（如使用 AUTO_INCREMENT 列），索引树将被拆分，以便更高级节点仅包含一个键。可以提高索引树的空间利用率。索引列中允许使用 NULL 值，每个密钥占 0 ～ 1 个字节。BLOB 和 TEXT 列可以创建索引（myisampack 可以包装 BLOB 和 VARCHAR）。每个字符列可以具有不同的字符集

*   AUTO_INCREMENT 支持内部处理，自动更新此列的 INSERT 和 UPDATE 操作。序列顶部的值在删除后不会重复使用（当 AUTO_INCREMENT 列定义为多列索引的最后一列时，确实会重复使用从序列顶部删除的值）可以使用 ALTER TABLE 或 myisamchk 重置该值

*   当将删除与更新和插入混合时，动态大小的行的碎片化要少的多。通过自动组合相邻的已删除块并通过扩展块来完成此操作

*   支持并发插入，如果表在数据文件的中间没有空闲块

*   MyISAM 索引文件中有个标志，指示表是否正确关闭，如果使用 `myisam_recover_options` 启动数据库，MyISAM 在打开表时会自动对其进行检查，如果未正确关闭表，则会对其进行修复

*   对整张表加锁，读取时会对需要读到的所有表加共享锁，写入时则对表加排他锁。但在表有读取查询的同时，也可以往表中插入新的记录（并发插入，**CONCURRENT INSERT**）

*   5.0 中，表如果是变长行，则默认支持 256T 数据（指向数据记录的指针长度是 6 个字节），5.0 之前，指针长度默认是 4 字节，只支持 4 GB 数据。所有的版本都支持 8 字节的指针，要改变 MyISAM 表指针的长度（跳高或调低）通过修改 `MAX_ROWS` 和 `AVG_ROW_LENGTH` 选项的值，两者相乘就是表可能达到的最大大小。修改这两个参数会导致重建整个表和表的所有索引

*   数据以紧密格式存储，在某些场景下的性能很好。MyISAM 有一些服务器级别的性能扩展限制，比如对索引键缓冲区（key cache）的 Mutex 锁，MariaDB 基于段（segment）的索引键缓冲区机制来避免该问题。

    但 MyISAM 最典型的性能问题还是表锁的问题，如果你发现所有的查询都长期处于 Locked 状态，那么毫无疑问表锁就是罪魁祸首。 

##### 启动选项

###### 选项和变量

|                             Name                             | Cmd-Line | Option File | System Var | Status Var | Var Scope | Dynamic |
| :----------------------------------------------------------: | :------: | :---------: | :--------: | :--------: | :-------: | :-----: |
| [bulk_insert_buffer_size](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_bulk_insert_buffer_size) |   Yes    |     Yes     |    Yes     |            |   Both    |   Yes   |
| [concurrent_insert](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_concurrent_insert) |   Yes    |     Yes     |    Yes     |            |  Global   |   Yes   |
| [delay_key_write](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_delay_key_write) |   Yes    |     Yes     |    Yes     |            |  Global   |   Yes   |
| [have_rtree_keys](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_have_rtree_keys) |          |             |    Yes     |            |  Global   |   No    |
| [key_buffer_size](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_key_buffer_size) |   Yes    |     Yes     |    Yes     |            |  Global   |   Yes   |
| [log-isam](https://dev.mysql.com/doc/refman/8.0/en/server-options.html#option_mysqld_log-isam) |   Yes    |     Yes     |            |            |           |         |
| [myisam-block-size](https://dev.mysql.com/doc/refman/8.0/en/server-options.html#option_mysqld_myisam-block-size) |   Yes    |     Yes     |            |            |           |         |
| [myisam_data_pointer_size](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_data_pointer_size) |   Yes    |     Yes     |    Yes     |            |  Global   |   Yes   |
| [myisam_max_sort_file_size](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_max_sort_file_size) |   Yes    |     Yes     |    Yes     |            |  Global   |   Yes   |
| [myisam_mmap_size](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_mmap_size) |   Yes    |     Yes     |    Yes     |            |  Global   |   No    |
| [myisam_recover_options](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_recover_options) |   Yes    |     Yes     |    Yes     |            |  Global   |   No    |
| [myisam_repair_threads](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_repair_threads) |   Yes    |     Yes     |    Yes     |            |   Both    |   Yes   |
| [myisam_sort_buffer_size](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_sort_buffer_size) |   Yes    |     Yes     |    Yes     |            |   Both    |   Yes   |
| [myisam_stats_method](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_stats_method) |   Yes    |     Yes     |    Yes     |            |   Both    |   Yes   |
| [myisam_use_mmap](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_myisam_use_mmap) |   Yes    |     Yes     |    Yes     |            |  Global   |   Yes   |
| [tmp_table_size](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_tmp_table_size) |   Yes    |     Yes     |    Yes     |            |   Both    |   Yes   |

*   `bulk_insert_buffer_size`

    批量插入优化中使用的树缓存大小，这是每个线程的限制

*   `delay_key_write=ALL`

    延迟键写入，在每次修改执行完成时，不会立刻将修改的索引数据写入磁盘，会写到内存中的键缓冲区（in-memory key buffer），只有在清理键缓冲区或者关闭表的时候才会将对应的索引块写入到磁盘。

    这种方式可以极大的提升写入性能，但是在数据库或者主机崩溃时会造成索引损坏，需要执行修复操作。延迟更新索引键的特性，可以在全局设置，也可以为单个表设置。

    ```mysql
    # 建表时指定
    create table table_name() engine=myisam delay_key_write=1;
    # 修改表
    alter table table_name delay_key_write=1;
    ```

*   `myisam_max_soft_file_size`

    在重新创建 MyISAM 索引时的，允许 MySQL 使用的临时文件的最大大小。如果文件大小大于此值，将使用键高速缓存来创建索引，以字节为单位

*   `myisam_recover_options=mode`

    设置自动恢复 MyISAM 表的模式

*   `myisam_sort_buffer_size`

    设置恢复表时使用的缓冲区大小

##### 表存储格式

MyISAM 支持三种不同的存储格式，根据使用的列的类型，自动选择 fixed（固定格式）和 dynamic（动态格式）两种（当创建没有 BLOB 或 TEXT 列的表时，可以使用 ROW_FORMAT 选项强制指定为 FIXED 或 DYNAMIC 格式），对于 compressed（压缩格式）格式，只能通过 myisampack 创建只读表

###### Fixed

默认格式，当表不包含可变长度列（VARCHAR、VARBINARY、BLOB、TEXT）时，每行使用固定数量的字节存储。该格式最快，最简单，仅适用于没有 BLOB 和 TEXT 列的表。

静态格式表具有：

*   CHAR 和 VARCHAR 使用空格填充到指定宽度，BINARY 和 VARBINARY 使用 0x00 字节填充到指定宽度

*   支持 NULL 的列需要额外空间记录其值是否为 NULL，每个支持 NULL 的列需要一位记录，四舍五入到最接近的字节

*   很快，易于缓存，崩溃后易于重构，行位于固定位置，除非删除了大量的行并想将可以磁盘空间返回给操作系统，否则不需要进行重组。

*   通常需要比动态格式表更多的磁盘空间

*   静态行的预期长度（字节）

    ```
    row length = 1
                 + (sum of column lengths)
                 + (number of NULL columns + delete_flag + 7)/8
                 + (number of variable-length columns)
    ```

    delete_flag 对于静态行格式表，该值为 1，静态表使用行记录中的一位作为标志，以指示该行是否已被删除。对于动态表为 0，该标志存储在动态行头中

###### Dynamic

如果表包含任何可变长度列（VARCAHR，VARIBINARY，BLOB，TEXT），或建表时指定 `ROW_FORMAT=DYNAMIC` 选项时将使用该格式。

每行包含一个行头，指示其长度。如果由于更新而使行变长，则行可能会变得碎片化，存储在不连续的碎片中（使用 `OPTIMIZE TABLE` 或 `myisamchk -r` 对表进行碎片整理）

动态格式具有：

*   除长度小于四的字符串列外，所有字符串列都是动态的

*   每行都有一个 bitmap 指示那些列包含空字符串（字符串列）或零值（对于数字列）。不包括包含 NULL 值的列。如果删除尾部空格后字符串列的长度为零，或者数字列的值为零，则它会在位图中标记，且不会保存到磁盘，非空字符串将另存为长度字节加上字符串内容

*   NULL 列在行中需要额外的空间记录其值是否为 NULL，每 NULL 列多一位，四舍五入到最近字节

*   所需磁盘空间较少

*   每行仅使用所需的空间，如果一行变大，则将其拆分为所需的多个碎片，从而导致碎片化，可能需要定时 `OPTIMIZE TABLE` 或 `myisamchk -r` 提高性能，使用 `myisamchk -ei` 获取表统计信息

*   崩溃后更难恢复，行可能会分成许多碎片，可能会丢失碎片

*   动态大小的行预期行长使用

    ```
    3 + (number of columns + 7) / 8
    	+ (number of char columns)
    	+ (packed size of numeric columns)
    	+ (length of strings)
    	+ (number of NULL columns + 7) / 8
    ```

    每个连接有 6 字节禁区，每当更新到值行扩大时，就会链接动态行。每个新链接至少有 20 个字节，下一个扩展可能在同一链接中进行，如果不是，则创建另一个链接。可以使用 `myisamchk -ed` 查找链接数。`OPTIMIZE TABLE` 或 `myisamchr -r` 时可以删除所有链接

###### compressed

压缩格式时使用 myisampack 工具生成的只读格式，可以使用 myisamchk 解/压缩表。压缩表：

*   只占很少磁盘空间
*   每行分别进行压缩，访问开销小。根据表中最大的行，行头占用 1 ～ 3 字节。每列的压缩方式不同，通常使用：后缀空间压缩，前缀空间压缩，值为零的数字使用一位存储等
*   可用于固定长度或动态长度的行

压缩表是只读的，不能更新或添加表中行（除非解压并修改数据后再压缩），但 DDL 操作仍然有效

##### 表问题

###### 表损坏

表损坏时，从表中查询数据时，出现

```
Incorrect key file for table: '...'. Try to repair it
```

或查询不到表中行或返回不完整的结果

以下事件可能导致表损坏：

*   mysqld 进程在写过程没终止或计算机重启，硬件故障
*   使用外部程序（myisamchk）修改服务器同时正在修改的表

可以使用 `CHECK TABLE` 检查表情况，使用 `REPAIR TABLE` 修复表。如果服务器关闭可使用 myisamchk 命令操作

```mysql
# 检查表错误
check table table_name;
# 修复表
repair table table_name;
```

###### 未正确关闭表

每个 MyISAM 索引文件（`.MYI`）文件都包含一个计数器，用于检查表是否已正确关闭。如果 `CHECK TABLE` 或 myisamchk 收到警告，则计数器不同步

```
# 不一定表示该表损坏，但需要进行检查
clients are using or haven't closed the table properly
```

##### 常用操作

###### 快速创建索引

为了高效地载入数据到 MyISAM 表中，可以先禁用索引、载入数据，然后重新启用索引：                       

```mysql
// 禁用索引
ALTER TABLE test.load_data DISABLE KEYS;
// 插入数据
// 启用索引
ALTER TABLE test.load_data ENABLE KEYS;
```

这样构建索引的工作被延迟到数据完全载入以后，这个时候已经可以通过排序来构建索引了。这样做会快很多，并且使得索引树碎片更少，更紧凑。

如果使用的是 `LOAD DATA FILE`，并且要载入的表是空的，MyISAM 也可以通过排序来构建索引，但是对唯一索引无效，因为 `DISABLE KEYS` 只对非唯一性索引有效。MyISAM 会在内存中构造唯一索引，并且为载入的每一行检查唯一性。一旦索引的大小超过了有效内存大小，载入操作就会变得越来越慢。                                                                                                                                                                               

#### Archive

创建 ARCHIVE 引擎表时，存储引擎会创建名称以表名开始 `.ARZ` 结尾的文件。在优化过程中可能会出现 `.ARN` 结尾文件

支持 INSERT（插入时将压缩，使用 zlib 压缩）、REPLACE、SELECT、ORDER BY、BLOB 列和空间数据类型。不支持地理空间参考系统。采用了行级锁。不支持将 AUTO_INCREMENT 小于当前最大列值的值插入列中（会导致 ER_DUP_KEY 错误）。不支持分区

支持 OPTIMIZE TABLE 分析表格并将其打包为较小格式，支持 CHECK TABLE。

插入类型：

*   一条 INSERT 语句仅将行压入压缩缓冲区，然后根据需要刷新该缓冲区。插入缓冲区受锁保护。
*   批量插入仅在完成后可见，除非同时发生其他插入，在这种情况下可以部分看到

查询时，按需解压缩行；没有行缓存。SELECT 操作执行完整的表扫描，SELELCT 被执行为一致读。

#### Blackhole

没有实现任何的存储机制，它会丢弃所有插入的数据，不做任何保存。查询数据返回空。但是服务器会记录表的日志。常用于：复制拓扑的中间转储、过滤二进制日志操作等

创建 BLACKHOLE 表时，服务器在全局数据字典中创建表定义。没有与该表关联的文件，支持所有类型的索引。不支持分区。

INSERT 触发器会按预期工作，UPDATE 和 DELETE 未激活触发器。

事务感知，已提交的事物将被写入二进制日志，而回滚的事务则不会。

引擎不会自动增加字段值，并且不会保留自动增加字段状态

#### CSV

##### 应用场景

可以将普通的 CSV 文件作为表处理，不支持索引。可以将 Execl 等电子表格软件中的数据存储为为 CSV 文件，然后复制到数据目录下，就能在 MySQL 中打开使用。

```mysql
CREATE TABLE test (i INT NOT NULL, c CHAR(10) NOT NULL) ENGINE = CSV;
INSERT INTO test VALUES (1, 'record one'), (2, 'record two');
```

##### 特征

使用逗号分隔值格式的文本文件存储引擎存储数据，该引擎总是被编译到 MySQL 服务器。创建 CSV 表时，服务器将创建一个纯文本数据文件，该文件的名称以表名开头以 `.CSV` 扩展结尾（Excel 可以编辑该文件）。将数据存储到表时，存储引擎会将其以逗号分隔的值格式保存到数据文件中。创建 CSV 表会创建一个元文件，存储表状态和行数，以表名开始，以 `.CSM` 结尾

支持使用 CHECK TABLE（执行时将查找正确的字段分隔符，转义的字段，与表定义进行比较的正确字段数以及是否存在 CSM 元文件来检查文件的有效性。发现第一个无效行将报告错误，返回表的 `Msg_type` 列值为 `error`）和 REPAIR TABLE 来检查和修复 CSV 表（从现有 CSV 数据中复制尽可能多的有效行，然后用 CSV 恢复的行替换现有文件，超出损坏数据的所有行都将丢失，修复期间，仅将 CSV 文件中直到第一行损坏的行复制到新表中。从第一行损坏到表末尾的所有其他行都将被删除，即使是有效行也是如此）

不支持索引和分区，创建的所有表的所有行必须 NOT NULL

#### Federated

Federated 引擎是访问其他 MySQL 服务器的一个代理，它会创建一个到远程 MySQL 服务器的客户端连接，并将查询传输到远程服务器执行，然后提取或者发送需要的数据。最初设计该存储引擎是为了和企业级数据库如 Microsoft SQL Server 和 Oracle 的类似特性竞争的，可以说更多的是一种市场行为。尽管该引擎看起来提供了一种很好的跨服务器的灵活性，但也经常带来问题，因此默认是禁用的。MariaDB 使用了它的一个后续改进版本，叫做 FederatedX。

#### Memory

##### 应用场景

如果需要快速地访问数据，并且这些数据不会被修改，重启以后丢失也没有关系，那么使用 Memory 表（以前也叫做HEAP表）是非常有用的。Memory 表至少比 MyISAM 表要快一个数量级，因为所有的数据都保存在内存中，不需要进行磁盘 I/O。Memory 表的结构在重启以后还会保留，但数据会丢失。

Memroy 表在很多场景可以发挥作用：

* 用于查找（lookup）或映射（mapping）表
* 用于缓存周期性聚合数据的结构
* 用于保存数据分析中产生的中间数据

官方建议使用 NDB 代替 MEMORY

##### 特征

*   不会在磁盘上创建任何文件，表定义存储在 MySQL 数据字典中

*   最大大小受 `max_heap_table_size` 系统变量限制，默认为 16 MB，服务器重启会将现有 MEMORY 表的最大大小设置为全局 `max_heap_table_size`。

*   使用固定长度格式，不支持 BLOB 和 TEXT 类型列，VARCHAR 使用固定长度存储。支持 AUTO_INCREMENT 列

*   支持 HASH 和 BTREE 索引，使用 `INDEX USING [BTREE | HASH] ({cloumn})` 指定，单表最多 64 个索引，每个索引最多 16 列，最大键长为 3072 字节。如果键重复性高，hash 索引回显著变慢。支持非唯一键（hash 索引实现），索引列可以包含 NULL 值

*   可以使用 `--init_file=file_name` 或 `INSERT INTO ... SELECT` 或 `LOAD DATA` 填充数据

*   复制时

    *   当源服务器关闭并重启时，MEMORY 表将清空，为了将此效果复制到副本中，源 MEMORY 在启动后首次使用给定表时，会记录一个事件，该事件通过将表的 DELETE 或（8.0.22）TRUNCATE TABLE 语句写入二进制日志来通知副本服务器清空数据
    *   当副本服务器关闭并重启时，MEMORY 表将清空，并向自己的二进制写入 DELETE 或 TRUNCATE TABLE（8.0.22）语句，该语句会传递给下游副本

    复制拓扑中使用内存表，源表与副本表在某些情况下数据会不同

#### Merge

Merge 引擎是 MyISAM 引擎的一个变种。Merge 表是由多个 MyISAM 表合并而来的虚拟表。如果将 MySQL 用于日志或者数据仓库类应用，该引擎可以发挥作用。但是引入分区功能后，该引擎已经被放弃

#### XtraDB

基于 InnoDB 引擎的改进版本，包含在 Persona 和 MariaDB 中，主要改进在性能、可测量性和操作灵活性。可以替代 InnoDB，兼容读写 InnoDB 的数据，支持 InnoDB 的所有查询