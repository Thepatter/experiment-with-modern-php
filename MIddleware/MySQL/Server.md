### MySQL 服务器管理

#### 系统变量

##### 组成

```mysql
# 输出所有配置项及系统变量默认值
mysqld --verbose --help
mysqladmin variables
mysqladmin extended-status
```

###### 结构化系统变量

结构变量值是一个结构，可以有多个实例，每个名称不同，指向不同资源，支持以下结构化系统变量：`key_buffer_size`、`key_cache_block_size`、`key_cache_division_limit`、`key_cache_age_threshold`

结构变量格式： `instance_name.component_name`

```mysql
SET GLOBAL hot_cache.key_buffer_size = 10*1024*1024;
SET @@GLOBAL.hot_cache.key_buffer_size = 10*1024*1024;
# 不支持 show 语法查询
SELECT @@GLOBAL.hot_cache.key_buffer_size;
```

###### 获取系统变量

show 语句不指定范围时默认返回 SESSION 值。

```mysql
SHOW VARIABLES LIKE 'max_join_size';
SHOW SESSION VARIABLES LIKE 'max_join_size';
SHOW GLOBAL VARIABLES LIKE '%size%';
```

##### 配置

###### 配置验证

8.0.16 开始，支持使用 `--validate-config` 选项进行服务器配置项检查（不会启用服务器，不会初始化存储引擎和其他插件、组件，也不验证未初始化的子系统的关联选项）。如果存在错误配置项，显示错误信息，如果存在参数项值错误的情况可以使用 `--log_error_verbosity` 显示警告信息

```shell
mysqld --validate-config
# 显示警告信息
mysqld --validate-config --log_error_verbosity=2
# 检查特定配置文件选项
mysqld --defaults-file=./my.cnf-test --validate-config
```

###### SET 语法

变量设置语法

```mysql
SET variable = expr [, variable = expr] ...

variable: {
    user_var_name
  | param_name
  | local_var_name
  | {GLOBAL | @@GLOBAL.} system_var_name
  | {PERSIST | @@PERSIST.} system_var_name
  | {PERSIST_ONLY | @@PERSIST_ONLY.} system_var_name
  | [SESSION | @@SESSION. | @@] system_var_name
}
```

*   用户变量设置

    用户定义的变量在会话内本地创建，仅在该会话的上下文中存在

    ```mysql
    SET @var_name = expr;
    SET @name = 43;
    SET @total_tax = (SELECT SUM(tax) FROM taxable_transactions);
    ```

设置字符集

```mysql
SET {CHARACTER SET | CHARSET}
    {'charset_name' | DEFAULT}
```

设置会话系统变量：`character_set_client` 和 `character_set_results` 为给定字符集。`character_set_connection`、设置 `character_set_connection` 会同时修改 `character_set_database` 的值。`charset_name` 可以添加或不添加引号。某些字符集不能用作客户端字符集，设置时可能会报错

```mysql
# 客户端不支持 ucs2、utf16、utf16le、utf32，以下设置报错
SET character_set_client = 'ucs2'; 
SET CHARSET utf16
```

设置字符集排序集

```mysql
SET NAMES {'charset_name'
    [COLLATE 'collation_name'] | DEFAULT}
```

设置会话的字符集和排序集，会设置 `character_set_client`、`character_set_connection`、`character_set_results` 为指定值，设置 `character_set_connection` 为 `charset_name` 还会设置 `collation_connection` 的排序规则为 `charset_name` 的默认值，设置某些客户端不能使用的字符集时会报错

###### 系统变量修改

每个系统变量都有一个默认值，可以在服务器启动时使用命令行或选项文件中的选项设置系统变量。或在运行时使用 SET 命令动态修改。*变量名称中下划线和中连接线等效，值后缀K、M、G不区分大小写*

全局系统变量会影响服务器整体操作，会话系统变量影响单个客户端连接的操作

*   服务器启动时，将每个全局变量初始化为其默认值（默认值可以通过命令行或配置文件中指定选项更改）
*   为每个连接的客户端维护一组会话变量，客户端的会话变量来在连接时使用相应全局变量的当前值进行初始化

设置系统变量

```mysql
# 设置全局系统变量
SET GLOBAL max_connections = 1000;
SET @@GLOBAL.max_connections = 1000;
# 设置会话系统变量
SET SESSION sql_mode = 'TRADITIONAL';
SET @@SESSION.sql_mode = 'TRADITIONAL';
SET @@sql_mode = 'TRADITIONAL';
```

*   使用 SET 在运行时设置值时无法使用 M、G 等后缀，可以使用算术计算。在服务器启动选项中使用后缀合法，但无法使用算术运算
*   使用 SET 语句设置布尔值时，可以使用 ON/OFF，0/1 设置，但在配置文件和命令行中只能使用 0/1

设置变量权限

*   设置全局变量或 `SET GLOBAL` 语句，需要 `SYSTEM_VARIABLES_ADMIN`/`super` 特权

###### 持久系统变量

持久变量设置会保存到数据目录下 *mysqld-auto.cnf* （json 格式）文件，持久化全局变量或 `SET_PERSIST_ONLY` 需要 `SYSTEM_VARIABLES_ADMIN` 和 `PERSIST_RO_VARIABLES_ADMIN` 权限。会话和部分全局只读系统变量无法持久保存

```mysql
# 设置并持久化
SET PERSIST max_connections = 1000;
SET @@PERSIST.max_connections = 1000;
# 仅持久化不设置
SET PERSIST_ONLY back_log = 1000;
SET @@PERSIST_ONLY.back_log = 1000;
```

取消持久化配置会从 *mysqld-auto.cnf* 文件中，删除持久化的动态系统变量需要 `SYSTEM_VARIABLES_ADMIN`/`SUPER` 特权，删除只读系统变量需要 `SYSTEM_VARIABLES_ADMIN` 和 `PERSIST_RO_VARIABLES_ADMIN` 特权

```mysql
RESET PERSIST [[IF EXISTS] system_var_name]
# 删除所有持久变量
RESET PERSIST;
```

启动时，mysqld-auto.cnf 文件最后读取，如果在之前读取了 `persisted_globals_load` 值为假，则忽略该文件

##### 常见系统变量

###### 日志设置

###### InnoDB 设置

###### 服务端设置

*   `collation_server`

    设置服务器的默认排序规则，支持全局和会话，对应启动项为 `--collation-server=name`，字符串值，默认 `utf8mb4_0900_ai_ci`

*   `collation_database`

    默认数据库使用的排序规则，每当更改默认数据库时，服务器都会设置此变量。如果没有默认数据库，与 collation_server 一致。支持全局和会话

*   `max_allowd_packet`

*   `max_prepared_stmt_count`

    整型，全局范围，默认 16382，范围 0~1048576(8.0.17)/4194394(8.0.18)，启动项 `--max-prepared-stmt-count=#`。限制服务器中准备好的语句总数。如果将此值设置为低于准备好的语句的当前数目，则现有语句不会收到影响，且可以使用，但直到当前数目降至限制以下才可以准备新语句。为 0，则禁用准备语句

*   `long_query_time`

    整型，全局、会话范围，如果查询时间超过该值（秒），默认 10，服务器将增加 `Slow_queries` 状态。如果启用了慢查询日志，则查询将记录到慢查询日志文件中。此值是实时测量，不是 CPU 时间。可以将值指定为微秒。不建议将此值设置小于一秒。

*   `persisted_globals_load`

    指定是否从数据目录 `mysqld-auto.cnf` 加载持久配置。默认会加载

    命令行选项 `--persisted-globals-load[={OFF|ON}]`，全局范围，布尔值，默认 ON
    
*   `system_time_zone`

    全局范围，指示服务器系统时区，默认继承环境变量 TZ，可以使用 mysqld_safe 脚本的 `--timezone` 

*   `slow_launch_time`

    整型，全局，命令行选项 `--slow-launch-time=#`，默认 2，如果创建线程花费的时间超过该值（秒），服务器将增加 `Slow_launch_threads` 状态变量。

*   `time_zone`

    当前时区，此变量用于初始化每个连接的客户端时区，全局和会话范围。默认 `SYSTEM`，启动项 `--default-time-zone` 指定。8.0.19 开始的值范围 `-14:00~14:00`，8.0.18 之前值范围 `-12:59~13:00`
    
*   `table_open_cache`

    整型，全局范围，默认 4000，范围 1 ~ 524288。指示所有线程的打开表数。增大将增加 mysqld 所需的文件描述符数量。可以通过检查 `Opened_tables` 状态变量来检查是否需要增加表缓存。如果 `Opened_tables` 很大，且不 `FLUSH TABLE`（强制关闭并重新打开所有表），则应增加该值

###### 数据操作

*   `explicit_defaults_for_timestamp`

    指定服务器是否为列的默认值或 NULL 值处理。布尔，启动项 `--explicit-defaults-for-timestamp[=OOF|ON]`

    *   开启（默认）

        如果将 `TIMESTAMP` 列定义为 `NULL` 则不会将当前时间，要定义为 `CURRENT_TIMESTAMP`，如果未使用 `NOT NULL` 而插入了 `NULL` 值，则列值为 `NULL`，而不是当前时间戳。

    *   禁用

        未使用 `NULL` 声明的列将自动声明 `NOT NULL`，插入 `NULL` 值将设置为当前的时间戳。8.0.22 开始将报错。

        如果未使用 `NULL` 或 `DEFAULT ON UPDATE` 声明第一列，则自动将第一个 `TIMESTAMP` 列设置为 `DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` 

*   `max_sort_length`

    整数类型，指定排序数据值时使用的字节数，服务器仅使用（`GROUP BY`、`ORDER BY`、`DISTINCT`）该值前的字节排序，而忽略其后不同的值，范围 4 ~ 8388608 默认 1024，支持会话和全局，命令行 `--max-sort-length=#`，调整时，可能需要修改 `sort_buffer_size` 值

*   `sort_buffer_size`

    每个必须执行排序的会话都会分配此大小的缓冲区。非特定于引擎。`SHOW GLOBAL STATUS` 的 `Sort_merge_passes` 值很大，则可以增加该值。整数，默认 262144，范围 32768 ~ 4G - 1。

    Linux 上内存分配阈值为 256kb 和 2mb，超过阈值会导致内存分配变慢

##### 服务器状态

服务器维护了许多系统状态，提供系统信息。

```mysql
SHOW [GLOBAL | SESSION] STATUS
```

###### 服务器系统变量

```mysql
# 刷新系统变量
FLUSH STATUS;
```

###### InnoDB 相关

|                系统变量                 |                             含义                             |
| :-------------------------------------: | :----------------------------------------------------------: |
|    `Innodb_buffer_pool_dump_status`     |                    导出 Innodb 缓冲池状态                    |
|     `Innodb_buffer_poll_load_stat`      | Innodb 缓冲池热身状态（重启后会加载一个之前的时间节点快照）  |
|     `Innodb_buffer_pool_bytes_data`     |               Innodb 缓冲池包含数据的字节总数                |
|     `Innodb_buffer_pool_pages_data`     |                 Innodb 缓冲池中包含的数据页                  |
|    `Innodb_buffer_pool_bytes_dirty`     |                Innodb 缓冲池中脏页的总字节数                 |
|    `Innodb_buffer_pool_pages_dirty`     |                     Innodb 缓冲其脏页数                      |
|   `Innodb_buffer_pool_pages_flushed`    |                   Innodb 缓冲池刷新页次数                    |
|     `Innodb_buffer_pool_pages_free`     |                  Innodb 缓冲池可用的页总数                   |
|   `Innodb_buffer_pool_pages_latched`    | Innodb 缓冲池中锁定的数据页（正在读或写的页或无法刷新和删除的页面）仅使用 UNIV_DEBUG 构建服务器才会开启 |
|     `Innodb_buffer_pool_pages_misc`     |             Innodb 缓冲池中用于管理而分片的页数              |
|    `Innodb_buffer_pool_pages_total`     |                    Innodb 缓冲池的总页数                     |
|     `Innodb_buffer_pool_read_ahead`     |            Innodb 缓冲池中后台预读线程读取的页面             |
| `Innodb_buffer_pool_read_ahead_evicted` | Innodb 缓冲池中后台预读线程读取的页面（这些页面在随后没有被查询访问的情况被换出） |
|   `Innodb_buffer_pool_read_ahead_rnd`   | Innodb 缓冲池发起随机预读次数，当查询以随机顺序扫描表时会发生 |
|   `Innodb_buffer_pool_read_requests`    |                Innodb 缓冲池逻辑读取请求次数                 |
|       `Innodb_buffer_pool_reads`        |    Innodb 缓冲其不能从逻辑读取的数量，必须直接从磁盘读取     |
|   `Innodb_buffer_pool_resize_status`    | 通过动态设置参数来动态调整 Innodb 缓冲池大小的操作状态（会记录重设大小时间） |
|     `Innodb_buffer_pool_wait_free`      |    Innodb 读取/创建页面没有干净页面时，等待刷新脏页的次数    |
|   `Innodb_buffer_pool_write_requests`   |                Innodb 缓冲池页写操作完成计数                 |
|          `Innodb_data_fsyncs`           | `fsync()` 函数操作计数，`fsync()` 频率受 `innodb_flush_method` 选项影响 |
|      `Innodb_data_pending_fsyncs`       |                 当前未决的 `fsync()` 操作数                  |
|       `Innodb_data_pending_reads`       |                       当前未决的读操作                       |
|      `Innodb_data_pending_writes`       |                       当前未决的写操作                       |
|           `Innodb_data_read`            |               服务启动开始的读取数据数（字节）               |
|           `Innodb_data_reads`           |                操作系统读取文件读取的数据总数                |
|          `Innodb_data_writes`           |                         写的数据总数                         |
|          `Innodb_data_written`          |                       当前写的字节总数                       |
|      `Innodb_dblwr_pages_written`       |                    已写入双写缓冲的页面数                    |
|          `Innodb_dblwr_writes`          |                     已执行的双写缓存数量                     |
|      `Innodb_have_atomic_builtins`      |                指示服务器是否使用原子指令构建                |
|           `Innodb_log_waits`            |              等待 log buffer 刷新重做日志的次数              |
|       `Innodb_log_write_requests`       |                 写 Innodb redo log 请求次数                  |
|           `Innodb_log_writes`           |                   物理写 redo log 文件次数                   |
|         `Innodb_num_open_files`         |                   Innodb 当前打开文件数量                    |
|         `Innodb_os_log_fsyncs`          |                `fsync()` 写完成 redo log 次数                |
|      `Innodb_os_log_pending_syncs`      |              redo log 未决的 `fsync()` 操作次数              |
|     `Innodb_os_log_pending_writes`      |                  redo log 写操作未决的次数                   |
|         `Innodb_os_log_written`         |                redo log 写入的数量量（字节）                 |
|           `Innodb_page_size`            |                 Innodb 页大小，默认 16 kb。                  |
|         `Innodb_pages_created`          |                  Innodb 表操作创建的页数量                   |
|           `Innodb_pages_read`           |                  Innodb 表操作读取的页数量                   |
|         `Innodb_pages_written`          |                   Innodb 表操作写的页数量                    |
|        `Inndb_redo_log_enabled`         |               redo log 是否启用，8.0.21 中引入               |
|     `Innodb_row_lock_current_waits`     |               Innodb 表操作正在等待行锁的数量                |
|         `Innodb_row_lock_time`          |              Innodb 表等待行锁花费的时间，毫秒               |
|       `Innodb_row_lock_time_avg`        |               Innodb 表等待行锁花费的平均时间                |
|       `Innodb_row_lock_time_max`        |               Innodb 表等待行锁花费的最大时间                |
|         `Innodb_row_lock_waits`         |                  Innodb 表操作等待锁的次数                   |
|          `Innodb_rows_deleted`          |                    Innodb 表已删除的行数                     |
|         `Innodb_rows_inserted`          |                     Innodb 表插入的行数                      |
|           `Innodb_rows_read`            |                      Innodb 表读的行数                       |
|          `Innodb_rows_updated`          |                     Innodb 表更新的行数                      |
|      `Innodb_system_rows_deleted`       |               Innodb 从系统创建的表中删除的行                |
|      `Innodb_system_rows_inserted`      |               Innodb 从系统创建的表中插入的行                |
|        `Innodb_system_rows_read`        |                 Innodb 从系统创建表中读的行                  |
|    `Innodb_truncated_status_writes`     |           show innodb engine status 输出被截断次数           |
|    `Innodb_undo_tablespaces_active`     | 活动的撤销表空间数量，包含显式（用户创建）、隐式（Innodb 创建）的撤销表空间 |
|   `Innodb_undo_tablespaces_explicit`    |                  用户创建的撤销表空间的数量                  |
|    `Innodb_undo_tablespace_implicit`    | Innodb 创建的撤销表空间的数量，初始会创建两个默认撤销表空间  |
|     `Innodb_undo_tablespace_total`      |                        撤销表空间总数                        |

*   所有关于 `Innodb_buffer_pool_*` 相关统计，使用了压缩表都可能不准确

###### 服务端统计相关

|              系统变量              |                             含义                             |
| :--------------------------------: | :----------------------------------------------------------: |
|         `Aborted_clients`          |            由于客户端未正确关闭连接而中止的连接数            |
|         `Aborted_connects`         |              连接到 MySQL 服务器的失败尝试次数               |
|      `Binlog_cache_disk_use`       |      使用临时文件存储超过 `binlog_cache_size` 的事务数       |
|         `Binlog_cache_use`         |                     binlog 缓存的事务数                      |
|    `Binlog_stmt_cache_disk_use`    |   使用文件存储超过 `binlog_stmt_cache_size`  非事务语句数    |
|      `Binlog_stmt_cache_use`       |                  binlog 缓存的非事务语句数                   |
|          `Bytes_received`          |                    接收的所有客户端字节数                    |
|            `Bytes_sent`            |                    所有客户端发送的字节数                    |
|             `Com_xxx`              |                         执行语句统计                         |
|      `Connection_errors_xxx`       |                      客户端连接错误相关                      |
|           `Connections`            |           客户端连接数（失败/成功），约等于连接 ID           |
|     `Created_tmp_disk_tables`      |                   执行时创建磁盘临时表数量                   |
|        `Created_tmp_files`         |                       创建的临时文件数                       |
|       `Created_temp_tables`        |                  执行时创建的内存临时表数量                  |
|          `Flush_commands`          |      服务器刷新表次数。`Com_flush` 统计刷新语句执行次数      |
| `group_replication_primary_member` |              显式主节点 UUID，多主时为空，废弃               |
|          `Handler_commit`          |                    内部 `COMMIT` 语句数量                    |
|          `Handler_delete`          |            从表中删除行的次数（失败/成功都统计）             |
|      `Handler_external_lock`       | 调用 `external_lock()` 时加一，通常在访问表实例开始和结束时发生。可以使用此变量发现访问分区表的语句在锁定 |
|         `Handler_mrr_init`         |               服务器使用引擎自身多范围读表相关               |
|         `Handler_prepare`          |                 两阶段提交操作准备阶段计数器                 |
|        `Handler_read_first`        | 索引中第一个条目的读取次数，如果此值很高，则服务器正在执行很多全索引扫描 |
|         `Handler_read_key`         |    基于键读取行的请求数，如果此值很高，则已为查询正确索引    |
|        `Handler_read_last`         |                  读取索引最后一个键的次数。                  |
|        `Handler_read_next`         |     读取顺序键的下一行次数（索引字段范围扫描，索引扫描）     |
|        `Handler_read_prev`         |         读取顺序键的上一行次数（ORDER BY DESC 优化）         |
|         `Handler_read_rnd`         | 基于固定位置读取行的请求数（执行很多需要对结果进行排序的查询，或扫描多表、联接未使用键） |
|      `Handler_read_rnd_next`       | 读取数据文件下一行的请求数，如果要进行大量表扫描，则此值很高（索引使用问题） |
|         `Handler_rollback`         |                 存储引擎执行回滚操作的请求数                 |
|        `Handler_savepoint`         |                  存储引擎存储保存点的请求数                  |
|    `Handler_savepoint_rollback`    |                 存储引擎回滚到保存点的请求数                 |
|          `Handler_update`          |                       更新表中行的次数                       |
|          `Handler_write`           |                       插入表中行的次数                       |
|         `Last_query_cost`          | 最后查询的成本，用于比较同一查询的不同查询计划成本。会话范围，默认 0 标识未编译任何查询。8.0.16 开始支持复制查询成本统计 |
|     `Last_query_partial_plans`     |     查询优化器在上一个查询的执行计划构建中进行的迭代次数     |
|   `Max_execution_time_exceeded`    |                   执行超时的 SELECT 语句数                   |
|       `Max_used_connections`       |                   启动以来的最大同时连接数                   |
|    `Max_used_connections_time`     |                 启动以来最大同时连接数的时间                 |
|            `Open_files`            | 打开的文件数，包含服务器打开的常规文件，不包括套接字等也不包括存储引擎内部打开文件 |
|           `Open_streams`           |                         已打开的流数                         |
|      `Open_table_definitions`      |                        缓存的表定义数                        |
|           `Open_tables`            |                         打开的表数量                         |
|           `Opened_files`           |               使用 `my_open()` 已打开的文件数                |
|     `Opened_table_definitions`     |                       已缓存的表定义数                       |
|          `Opened_tables`           |  已打开的表数，如果该值太大，则 `table_open_cache` 可能太小  |
|       `Prepared_stmt_count`        |   当前准备好的语句数（最大数由 `max_prepared_stmt_count`）   |
|             `Queries`              |           服务器执行的语句数，包含存储中执行的语句           |
|            `Questions`             |      服务器执行的语句数，仅包含客户端发送给服务器的语句      |
|         `Select_full_join`         | 由于不使用索引而执行表扫描的联接数，如果该值不为 0，应检查表索引 |
|        `Select_range_check`        |   连接使用键后，检查键后的每行。如果不是 0，应该检查表索引   |
|           `Select_scan`            |                   完全扫描第一个表的联接数                   |
|       `Slow_launch_threads`        |           线程创建超过 `slow_launch_time` 时间数量           |
|           `Slow_queries`           | 查询耗时超过 `long_query_time` 的次数。无法是否启用慢查询日志，此值都会增加 |
|        `Sort_merge_passes`         | 排序必须执行的合并次数，很多则可能需要增加 `sort_buffer_size` 值 |
|            `Sort_range`            |                    使用范围完成的排序数量                    |
|            `Sort_rows`             |                          排序的行数                          |
|            `Sort_scan`             |                    通过扫描表完成的排序数                    |
|      `Table_locks_immediate`       |                  可以立即授予表锁的请求次数                  |
|        `Table_locks_waited`        | 无法立即授予表锁并需要等待的次数，如果很高，可能有性能问题，应优化查询，拆分多表查询 |
|      `Table_open_cache_hits`       |                                                              |
|                                    |                                                              |
|                                    |                                                              |
|                                    |                                                              |
|                                    |                                                              |
|                                    |                                                              |
|                                    |                                                              |
|                                    |                                                              |
|                                    |                                                              |

###### 复制相关

*   `Rpl` 开头变量，需要安装了源端半同步复制插件时可可用	

|                     选项                     |                             含义                             |
| :------------------------------------------: | :----------------------------------------------------------: |
|        `Rpl_semi_sync_master_clients`        |                       半同步副本的数量                       |
|   `Rpl_semi_sync_master_net_avg_wait_time`   |          源等待副本回复的平均时间（微秒），始终为 0          |
|     `Rpl_semi_sync_master_net_wait_time`     |           源等待副本回复的总时间（微秒），始终为 0           |
|       `Rpl_semi_sync_master_net_waits`       |                   源等待副本回复的总次数，                   |
|       `Rpl_semi_sync_master_no_times`        |                    源关闭半同步复制的次数                    |
|         `Rpl_semi_sync_master_no_tx`         |                    副本未成功确认的提交数                    |
|        `Rpl_semi_sync_master_status`         | 半同步复制当前是否可作为源运行，ON 为已启用并已发生提交确认。OFF 未启用插件或源因提交确认超时已回到异步复制 |
|   `Rpl_semi_sync_master_timefunc_failures`   |                    源调用时机函数失败次数                    |
|   `Rpl_semi_sync_master_tx_avg_wait_time`    |           源等待每个事务的平均时间（以微秒为单位）           |
|     `Rpl_semi_sync_master_tx_wait_time`      |                  源等待事务的总时间（微秒）                  |
|       `Rpl_semi_sync_master_tx_waits`        |                      源等待事务的总次数                      |
| `Rpl_semi_sync_master_wait_pos_backtraverse` | 源等待事件的二进制坐标比以前等待的事件低的总次数。当事务开始等待答复的顺序与写入其二进制日志事件的顺序不同时，可能发生此情况 |
|     `Rpl_semi_sync_master_wait_sessions`     |                   当前等待副本回复的会话数                   |
|        `Rpl_semi_sync_master_yes_tx`         |                     副本成功确认的提交数                     |
|         `Rpl_semi_sync_slave_status`         | 半同步复制当前是否可以副本运行。ON 为已启用且复制 I/O 线程正运行。OFF 否 |
|           `Slave_open_temp_tables`           | 复制 SQL 线程当前已打开的临时表数，如果该值大于零，则关闭副本是不安全的 |
|                                              |                                                              |
|                                              |                                                              |
|                                              |                                                              |
|                                              |                                                              |
|                                              |                                                              |
|                                              |                                                              |
|                                              |                                                              |
|                                              |                                                              |

#### SQL 模式

服务器可以在不同的 SQL 模式