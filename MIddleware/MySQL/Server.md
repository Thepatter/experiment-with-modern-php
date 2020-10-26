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

###### 时区支持

MySQL Server 维护几个时区设置：

*   系统时区：服务器启动时，它将尝试自动确定主机的时区，并使用它来设置 `system_time_zone` 系统变量，此后该值不变。指定系统时区需要在 mysqld 启动之前设置 TZ 环境变量或使用 mysqld_safe 设置 `--timezone` 选项

*   服务器当前时区

    全局 `time_zone` 系统变量指示服务器当前正在运行的时区，初始 `system` 即与系统时区相同（如果设置为 `system`，则每个需要时区计算的 MySQL 函数调用都会进行系统库调用以确定当前系统时区，该调用可能受到全局互斥锁保护，从而导致争用）

    timezone 值支持偏移量字符串和命名时区（必须创建并填充了数据库中的时区信息表时，才能使用）

    ```shell
    # 填充时区表 linux/macos
    mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u{user} -p{pass} mysql
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

*   `binlog_cache_size`

    整型，默认 32768，范围 4096～平台最大值。支持动态支持，全局范围。命令行选项 `--binlog-cache-size=#`

    事务期间，用于保存更改的二进制日志的内存缓冲区的大小。在服务器上启用二进制日志记录时，如果服务器支持任何事务存储引擎，则会为每个客户端分配一个二进制日志缓存。如果用于事务的数据超过了内存缓冲区中的空间，则多余的数据将存储在一个临时文件中。当服务器上的二进制日志加密开启时，不会对内存缓存区进行加密。8.0.17 任何用于保存二进制日志缓存的临时文件都将被加密。提交每个事务后，通过清除内存缓存区并截断临时文件（如果使用）来重置二进制日志缓存

    如果经常使用大型事务，则可以通过减少或消除写入临时文件的需要来增加此缓存的大小，以获得更好的性能。`Binlog_cache_use` （用缓存或临时文件存储事务语句的数目）和 `Binlog_cache_disk_use` （使用临时文件的二进制 binlog 缓存）状态变量可用于调整此变量的大小

    `binlog_cache_size` 仅设置事务缓存的大小；语句缓存的大小由 `binlog_stmt_cahce_size` 系统变量控制

*   `--binlog-checksum={NONE|CRC32}`

    字符串（NONE、CRC32），默认 CRC32，命令行选项 `--binlog-checksum=type`

    启用此选项会为源服务器写入二进制日志的事件写入校验和。不能在事务中更改此选项。要控制 slave 从中继日志读取校验和，使用 `--slave-sql-verify-checksum` 选项，主服务器上的此变量设置为从服务器无法识别的值会导致从服务器将其自身的 `binlog_checksum` 值设置为 NONE，并因错误而停止复制

    8.0.21 之前，组复制不能使用校验和，且不支持二进制日志中的校验和，因此组成员必须将该值设置为 NONE。8.0.21 开始，组复制支持校验和，组成员可以使用默认值。

    更改该值会导致二进制日志轮换。必须为整个文件写入校验和，而非一部分。使用 `binlog_transaction_compression` 启用二进制日志事务压缩时，不会在压缩的事务有效负载中为单个事件写入校验和，将为 GTID 事件写入一个校验和，并为压缩的 Transaction_payload_event 写入一个校验和

*   `binlog_direct_non_transactional_updates`

    布尔，默认 OFF，支持动态设置，全局会话范围。命令行选项 `--binlog-direct-non-transactional-updates[={OFF|ON}]`

    由于并发问题，当事务同时包含对事务表和非事务表的更新时，副本可能会变得不一致。服务器试图通过将非事务性语句写入事务高速缓存来保留这些语句之间的因果关系，事务高速缓存将在提交后刷新。但是，当代表事务对非事务表所做的修改对其他连接而言立即可见时，就会出现问题，因为这些更改可能不会立即写入二进制日志中。

    该 `binlog_direct_non_transactional_updates` 变量为该问题提供了一种可能的解决方法。默认情况下，此变量是禁用的。启用 `binlog_direct_non_transactional_updates` 会使对非事务表的更新直接写入二进制日志，而不是事务高速缓存

    从 8.0.14 开始，设置此系统变量的会话是受限制操作。

    `binlog_direct_non_transactional_updates` 仅适用于使用基于语句的二进制日志记录格式复制的语句；它仅在 `binlog_format` 为 `STATEMENT` 或 `MIXED` （使用基于语句的格式复制给定语句时）才有效。当二进制日志格式为 ROW 或 `MIXED` 使用基于行的格式复制给定语句时，此变量无效

    在启用此变量之前，必须确保事务表和非事务表之间没有依赖关系。（如 `INSERT INTO myisam_table SELECT * FROM innodb_table`）否则，这样可能导致从库偏离

*   `binlog_encryption`

    布尔类型，全局范围，支持动态设置，默认 OFF。为服务器上的二进制日志文件和中继日志文件启用加密。索引文件不会加密，需要启用密钥环插件。运行过程中启用或关闭会刷新日志文件

*   `binlog_error_action`

    枚举（`IGNORE_ERROR`（继续进行中的事务，记录该错误然后停止记录二进制日志，并继续执行更新。要重启二进制日志需要重启服务器，此设置提供了与旧版的向后兼容。）、`ABORT_SERVER`）默认 `ABORT_SERVER`（停止并关闭），支持动态设置，全局范围，命令行选项 `--binlog-error-action=[value]`

    控制当服务器无法写入，刷新或同步二进制日志之类的错误时行为。该错误可能导致主持不一致，或失去同步

*   `binlog_expire_logs_seconds`

    整型 0 ～ INT32.MAX，默认 2592000，支持动态设置，全局范围，命令行选项 `--binlog-expire-logs-seconds`

    设置二进制日志的有效期（以秒为单位）。到期后自动删除日志文件。默认为 30 天，同时设置该变量和 `expire_logs_days`，以该变量为准。要禁用自动清除二进制日志，显式设置该变量为 0，且不设置 `expire_logs_days`，或 `expire_logs_days` 显式设置为 0，且不为 `binlog_expire_logs_seconds` 指定值

*   `bingo_format`

    枚举（`ROW`、`STATEMENT`、`MIXED`）默认 ROW，支持动态设置，全局会话范围，命令行选项 `--binlog-format=format`。

    设置二进制日志格式，某些情况下无法动态（存在临时表或正在运行复制）设置，设置主服务器不会导致副本服务器更改其日志格式。如果副本服务器启用了二进制日志，运行时修改会导致问题。

*   `binlog_group_commit_sync_delay`

    整型 0 ～ 1000000，默认 0，支持动态设置，全局范围。命令行格式 `--binlog-group-commit-sync-delay=#`

    控制二进制日志提交在将二进制日志文件同步到磁盘之前等待多时微秒。默认为 0，表示没有延迟。设置该值可使更多事务一次同步到磁盘上，从而减少提交一组事务的总时间。

    设置会增加服务器上事务的延迟，可会会影响客户端应用程序。在高并发工作负载上，延迟可能会增加争用并降低吞吐量。

*   `binlog_group_commt_sync_no_delay_count`

    整型 0 ～ 1000000，默认 0，支持动态设置，全局范围。命令行选项 `--binlog-group-commit-sync-no-delay-count=#`。

    指定当前要等待的最大事务数，如果 `binlog_group_commit_sync_delay` 设置为 0，则此选项无效。

*   `binlog_order_commit`

    布尔，默认 ON，支持动态设置，全局范围，命令行选项 `--binlog-order-commits[={OFF|ON}]`。

    复制源服务上启用此变量后，发布到存储引擎的事务提交指令将在单个线程上序列化，事务始终以与二进制日志相同的顺序提交。禁用将允许多个线程发出事务提交指令，与二进制日志组提交结合使用，可以防止单个事务的提交速率成功吞吐量的瓶颈，提高性能。

*   `binlog-row-event-max-size=N`

    整型，范围 256 ～ 平台最大值，默认 8192，8.0.14 支持全局范围，但不支持动态设置。命令行格式 `--binlog-row-event-max-size=#`

    当使用基于行的二进制日志记录时，设置基于行的二进制日志事件的最大大小（以字节为单位）的软限制。如果可能，将二进制日志中存储的行分组为大小不超过此设置值的事件。如果事件无法拆分，则可以超过最大大小。该值必须是 256 的倍数（否则将被舍入为 256）

*   `binlog_row_value_options`

    支持动态设置，命令行选项 `--binlog-row-value-options=#`，全局和会话范围、字符串，默认空串、有效值为 `PARTIAL_JSON`，此时将以节省空间的二进制日志格式仅修改 JSON 文档的部分更新。将导致基于行的复制仅将 JSON 文档修改后的部分写入 after-image，而不是完整文档。如果修改所需的空间大于完整文档的空间，或者服务器无法生存部分更新，则使用完整文档。

    必须二进制格式为行时和该值同时设置时才生效。基于语句的复制始终仅记录 JSON 文档的修改部分。要最大限度的节省空间使用 `binlog_row_image=NOBLOB/MINIMAL` 与该选项一起使用。`binlog_row_image=FULL` 占用较多空间在于全部 JSON 文档存储在 before-image 中，部分更新仅存储在 before-image 中

    如果无法将修改应用于副本上的 JSON 文档，则 MySQL 复制会产生错误。

    mysqlbinlog 输出使用 base64 编码的字符串事件形式的 JSON 部分更新，指定 `--verbose` 选项会使用伪 SQL 语句将 JSON 部分更新显示为可读的 JSON
    
*   `general_log`

    布尔，默认 OFF，全局范围，动态设置，命令行选项 `--general-log[={OFF|ON}` 是否启用一般查询日志，如果 `log_output=NONE`，即使启用了日志，也不会写入任何日志条目

*   `general_log_file`

    文件名，默认 `host_name.log` 全局范围，动态设置，命令行选项 `--general-log-file=file_name`。指定常规查询日志文件名称

*   `log_bin`

    指定用于二进制日志文件的基本名称。不提供或提供的 `--log-bin` 选项不是字符串或是空字符串，则基本名称默认为 `host_name-bin`。默认位置是数据目录。要更改该位置使用 `--log-bin=/absolute/path/`，绝对路径地址。

    8.0 之前，默认禁用二进制日志，指定该选项则启用，8.0 开始，无论是否指定 `--log-bin` 选项，默认情况下都会启用二进制日志记录。启用二进制后，`log_bin` 系统变量为 `ON`

    禁用二进制日志记录，可以在启动时指定 `--skip-log-bin` 或 `--disable-log-bin` 选项，当与 `log-bin` 选项同时设置时，后设置选项会覆盖之前选项。

    在服务器上使用 GTID 时，如果在异常关闭后重新启动服务器时禁用了二进制日志记录，则某些 GTID 可能会丢失，从而导致复制失败。在正常关闭状态下，当前二进制日志文件中的 GTID 集会保存在 `mysql.gtid_executed` 表。在恢复过程中，只要仍启用了二进制日志记录，就会将 GTID 从二进制日志文件添加到表中。如果在重新启动服务器时禁用了二进制日志记录，则服务器将无法访问二进制日志文件以恢复 GTID，因此无法启动复制。正常关闭后，可以安全的禁用二进制日志。

    `--log-slave-updates` 和 `--slave-preserve-commit-order` 选项需要二进制日志。如果禁用二进制日志，则忽略这些选项或指定 `--log-slave-updates=OFF` 和 `--skip-slave-preserve-commit-order` 如果同时指定这些选项和禁用二进制日志，则警告报错

    5.7 中，启用二进制日志记录时必须指定服务器ID，否则服务器无法启动。8.0 中，`server_id` 系统变量默认设置为 1。对于复制拓扑中使用的服务器，必须为每个服务器指定一个唯一的非零服务器 ID

*   `--log-bin-index[=file_name]`

    文件名，不支持动态设置，全局范围，命令行选项 `--log-bin-index=file_name`。二进制日志索引文件的名称，其记录二进制日志文件的名称。默认情况下，它的位置和基本名称与使用 `--log-bin` 选项二进制日志文件指定的值相同加扩展名为 `.index`。如果未指定 `--log-bin`，则默认的二进制日志索引文件名为 `binlog.index`。如果指定的 `--log-bin` 选项不带任何字符串或为空字符串，则默认的二进制日志索引文件名是 `host_name-bin.index`

*   `log_error`

    指定错误日志输出目标，不支持动态设置，全局范围，命令行选项 `--log-error[=file_name]`。默认为数据目录下的 `host_name.err` 文件。如果指定了 `--pid-file` 则文件名是 PID 文件名称。如果指定了文件名，则为数据目录下以 `.err` 后缀的文件名。除非指定了绝对路径名。如果错误日志无法重定向到错误日志文件，则发生错误并启动失败。如果输出目标是控制台，则此值为 `stderr`。

*   `log_error_filter_rules`

    字符串类型，默认 `IF prio>=INFORMATION THEN drop. IF EXISTS source_line THEN unset source_line.`，全局范围，支持动态设置，命令行选项 `--dragnet.log-error-filter-rules=value`

    指定 `dragnet` 组件过滤规则（有零个或多个规则组成，每个规则以 `IF` 开始和 `.` 结尾）。如果未安装或未启用 `log_filter_dragnet` 插件，该参数无效。8.0.12 开始，可以查询 `dragnet.Status` 变量以确定对该参数的最新赋值结果，之前设置该值成功后查看 `show warnings` 则会产生一条注释。

    ```mysql
    # 删除 INFORMATION 或以上事件，其他事件删除 source_line 字段
    SET GLOBAL dragnet.log_error_filter_rules = '
      IF prio>=INFORMATION THEN drop.
      IF EXISTS source_line THEN unset source_line.
    ';
    ```

*   `log_error_services`

    字符串，默认 `log_filter_internal`、`log_sink_internal`。命令行参数 `--log-error-services=value` 全局范围，支持动态设置，

    启用错误日志记录的组件。支持 0、1、或字符串（逗号、分号分隔（8.0.12），可选后跟空格）列表。组件顺序很重要。服务器按照列出的顺序执行组件（最后一个组件不能是过滤器）。值中的（非内置）命名组件必须先安装才能使用。

*   `log_error_verbosity`

    整型，默认 2（ERROR、WARNING）， 范围 1（ERROR） ～ 3（ERROR、WARNING、INFORMATION），全局范围，支持动态设置，命令行参数 `--log-error-verbosity=#`。指定用于处理错误日志的详细程度，影响 `log_filter_internal` 过滤器行为，如果未启用 `log_filter_internal` 则该选项无效。值为 2 或更大：记录不安全语句；3 将记录连接和访问错误。如果使用复制，建议使用 2 或更大的值。对于 SYSTEM （0）级别错误（包含启动关闭设置更改等）无论该值如何，都将记录到错误日志中

*   `log_error_suppression_list`

    字符串，默认空串，8.0.13 支持，全局范围，支持动态设置。影响 `log_filter_internal` 过滤器行为，如果该过滤器禁用，该变量无效。指定要抑制的错误代码（字符或数字指定，可以带或不带 `MY-` 前缀）以逗号分隔。指定的错误码必须由 MySQL 使用，指定不允许范围（值必须在允许范围内：1～999 服务器和客户端使用的全局错误代码；10000 服务器错误代码，将写入错误日志，不发给给客户端）。只能丢弃警告或信息级别消息，如果 `log_error_verbosity=1` 时，该值无效

*   `log_output`

    集合类型（TABLE（记录到 `mysql.general_log` 和 `mysql.slow.log`）、FILE（由 `general_log_file` 和 `slow_query_log_file` 指定）、NONE（不记录日志，同时存在时优先））默认 FILE，支持动态设置，全局范围，命令行选项 `--log-output=name`。指定（general.log 和 slow.log）输出目的地，此变量仅指定输出目标，不会启动日志

*   `log_raw`

    布尔，默认 OFF（重写），8.0.19 支持全局范围，8.0.19 支持动态设置，命令行选项 `--log-raw={OFF|ON}`。指定服务器重写以纯文本形式写入查询日志、慢查询日志、二进制日志语句中的密码。如果安装了查询重写插件，该选项将影响语句记录：不使用该选项，将记录查询重写插件返回的语句；使用该选项，将记录原始语句

*   `log_timestamps`

    枚举（UTC、SYSTEM）、默认 UTC，支持动态设置，全局范围，命令行选项 `--log-timestamps=#`。控制写入文件错误日志、查询日志、慢查询日志消息中的时间戳时区。但不影响写入表的查询、慢查询日志记录。

*   `long_query_time`

    整型，默认 10（秒），全局会话范围，动态设置，命令行选项 `--long-query-time=#`。如果查询超过该值，则增加 `Slow_queries` 值，如果启用了慢查询日志，则将查询记录到慢查询日志

*   `--max-binlog-dump-events=N`

    MySQL 测试套件在内部使用此选项进行复制测试和调试。

*   `slow_query_log`

    布尔，默认 OFF，全局范围，动态设置，命令行选项 `--slow-query-log[={OFF|ON}]` 指定是否启用慢查询日志。如果 `log_output=NONE`，即使启用了日志，也不会写入任何日志条目。由 `long_query_time` 指定记录阈值

*   `slow_query_log_file`

*   `--sporadic-binlog-dump-fail`

    MySQL 测试套件在内部使用此选项进行复制测试和调试。

###### InnoDB 设置

*   `innodb_strict_mode`

    布尔，全局会话范围，命令行选项 `--innodb-strict-mode[={OFF|ON}]`，默认 `ON`，支持动态设置。启用时，部分操作下（`CREATE TABLE`、`ALTER TABLE`、`CREATE INDEX`、`OPTIMIZE TABLE`），InnoDB 将返回错误，而不是警告。

    会启用记录大小检查，此时 `INSERT` 和 `UPDATE` 不会因为记录大于所选页面而失败。

    Oracle 建议在 `CREATE TABLE`，`ALTER TABLE` 和 `CREATE INDEX` 语句中使用 `ROW_FORMAT` 和 `KEY_BLOCK_SIZE` 子句时启用。禁用时，InnoDB 将忽略冲突的子句，并在消息日志中仅带有警告的情况下创建表或索引。结果表可能具有与预期不同的特征，例如在尝试创建压缩表时缺少压缩支持。启用时，此类问题会立即产生错误，并且不会创建表或索引。规则不适用通用表空间
    
*   `innodb_fast_shutdown`

    指定 InnoDB 关闭模式。整型、全局范围、支持动态设置、默认 1，范围 0（慢速关闭，关闭之前完全清除和合并缓冲区），1（跳过操作，直接关闭，快速关闭）2（刷新日志并关闭，像服务器崩溃时，不会丢失已提交事务，但奔溃恢复在下次启动时耗时较长），命令行选项 `--innodb-fast-shutdown=#`


###### 服务端设置

*   `admin_address`

    8.0.14 引入，字符串内容，默认空串，全局范围，不支持动态设置，命令行选项 `--admin-address=addr`。指定管理接口监听的连接 IP 地址。如果在启动时未设置该变量，则服务器不维护任何管理界面。指定时必须满足以下条件：该值必须是单个 IPv4/IPv6 地址或主机名（如果主机名解析为多个 IP 地址，则服务器使用第一个 IPv4 地址（如果有），没有使用第一个 IPv6 地址）、该值不能使用通配符、8.0.22 开始，该值可能包含网络名称空间说明符

    如果绑定地址失败，服务器错误，启动失败

*   `admin_port`

    8.0.14 引入，整型，默认 33062，命令行选项 `--admin-por=port_num` 指定管理接口的端口号。为 0 时使用默认值。如果未指定 `admin_address` 则设置无效

*   `--admin-ssl`/`--skip-admin-ssl`

    8.0.21 引入，无系统变量，仅命令行选项配置，布尔，指示管理接口的 ssl 选项。如果该选项为 OFF 或 `--disable-admin-ssl` 或 `--skip-admin-ssl` 管理接口将不使用加密连接，`admin_tsl_xxx`/`admin_ssl_xxx` 配置将失效

*   `bind_address`

    全局范围，不支持动态设置，字符串类型，默认 `*`，命令行选项 `--bind-address=addr`。指定服务器监听连接的地址。当值为列表时，服务器在每个值上监听一个套接字，每个套接字都绑定到一个网络接口。

    *   8.0.13 之前，接受单独的地址，可以指定单个非通配符 IP 地址或主机名，或通配符地址

    *   8.0.13 开始，可以接受单个值或逗号分隔的值列表。当指定值列表时，每个值必须指定一个非通配 IP 地址或主机名。值列表中不允许使用通配符地址格式
    *   8.0.22 开始，地址可以包含网络空间名称说明符

    IP 地址支持 IPv4/IPv6，主机名则使用第一个解析的 IPv4 地址（不存在使用第一个解析的 IPv6 地址）。

    服务器处理值类型：

    *   `*`

        如果地址为 `*`，则服务器在所有服务器主机的 IPv4 接口上，如果服务器支持 IPv6，则在 IPv6 接口上。值列表不允许使用该值

    *   `0.0.0.0`/`::`

        服务器在所有服务器主机上接受 IPv4/IPv6 连接。值列表不允许使用该值

    *   IPv4 地址映射

        接受 IPv4/IPv6 地址

    *   普通 IPv4/IPv6 地址

        仅接受该 IPv4/IPv6 地址连接

*   `collation_server`

    设置服务器的默认排序规则，支持全局和会话，对应启动项为 `--collation-server=name`，字符串值，默认 `utf8mb4_0900_ai_ci`

*   `collation_database`

    默认数据库使用的排序规则，每当更改默认数据库时，服务器都会设置此变量。如果没有默认数据库，与 collation_server 一致。支持全局和会话

*   `create_admin_listener_thread`

    8.0.14 引入，布尔，默认 OFF，不支持动态设置，全局范围，命令行选项 `--create-admin-listener-thread[={OFF|ON}]`。指定是否在管理接口上使用专用的管理线程。默认使用普通连接的管理器线程处理管理接口连接，如果 `admin_address` 未设置，则此变量无效（此时服务器不维护管理接口）

*   `net_buffer_length`

    整型、全局和会话范围（只读）动态扩展，默认 16384，范围 1024～1048576，命令选项 `--net-buffer-length=#`

    每个客户端线程都与连接缓冲区和结果缓冲区关联，两者都以该值初始化，但需要时可以扩展到 `max_allowed_packet` 大小。每个 SQL 语句执行结束后，结果缓冲区缩小到该值大小

    通常不应该更改此变量，如果语句超过此长度，连接缓冲区将自动扩大。

*   `max_allowed_packet`

    最大的数据包大小或生成/中间字符串大小；或准备语句 `mysql_stmt_send_long_data()`/C API 函数参数大小；默认 64 MB。

    整型，全局会话范围，支持动态设置，命令行选项 `--max-allowed-packet=#`，范围 1024~1073741824（byte）。

    包消息缓冲被初始化为 `net_buffer_length` 大小，需要时可以扩展到 `max_allowed_packet` 大小。

    如果使用大的 `BLOB` 或 `TEXT` 必须增加此变量值，和其大小匹配。

    通过更改此变量来更改消息缓冲区大小时，如果客户端允许，还应该在客户端更改缓冲区大小。客户端库内的默认值是 1G，但单个客户端可能会覆盖它（mysql 为 16 MB，mysqldump 为 24 MB，可以在命令行或选项文件中更改客户端值）

    会话值是只读的，客户端最多可以接收与会话值一样多的字节。服务器不会向客户端发送比当前全局值更多的字节

*   `max_connections`

    同时允许的最大客户端连接数。整型、默认 151、范围 1 ~ 100000，全局范围，支持动态设置。

*   `max_prepared_stmt_count`

    整型，全局范围，默认 16382，范围 0~1048576(8.0.17)/4194394(8.0.18)，启动项 `--max-prepared-stmt-count=#`。限制服务器中准备好的语句总数。如果将此值设置为低于准备好的语句的当前数目，则现有语句不会收到影响，且可以使用，但直到当前数目降至限制以下才可以准备新语句。为 0，则禁用准备语句

*   `open_files_limit`

    整型，默认 5000，范围 0 ~ 平台限制，全局访问，不支持动态设置，命令行选项 `--open-files-limit=#`，指定 mysqld 可从服务器获取的文件描述符数量。启动时 mysqld 会申请保留该值指定的描述符，运行时该值表示操作系统允许 mysqld 使用的的文件描述符数量。

    在 Linux 上不能设置为大于 `ulimit -n` 的值。有效的值基于启动时指定的值（如果无法获取会报 `Too many open files` 错误）

*   `persisted_globals_load`

    指定是否从数据目录 `mysqld-auto.cnf` 加载持久配置。默认会加载

    命令行选项 `--persisted-globals-load[={OFF|ON}]`，全局范围，布尔值，默认 ON
    
*   `system_time_zone`

    全局范围，字符串类型，不支持动态设置，指示服务器系统时区，默认继承环境变量 TZ，可以使用 mysqld_safe 脚本的 `--timezone` 

*   `slow_launch_time`

    整型，全局，命令行选项 `--slow-launch-time=#`，默认 2，如果创建线程花费的时间超过该值（秒），服务器将增加 `Slow_launch_threads` 状态变量。

*   `sql_mode`

    Set 类型（`ALLOW_INVALID_DATES`、`ANSI_QUOTES`、`ERROR_FOR_DIVISION_BY_ZERO`、`HIGH_NOT_PRECEDENCE`、`IGNORE_SPACE`、`NO_AUTO_VALUE_ON_ZERO`、`NO_BACKSLASH_ESCAPES`、`NO_DIR_IN_CREATE`、`NO_ENGINE_SUBSTITUTION`、`NO_UNSIGNED_SUBTRACTION`、`NO_ZERO_DATE`、`NO_ZERO_IN_DATE`、`ONLY_FULLGROUP_BY`、`PAD_CHAR_TO_FULL_LENGTH`、`PIPES_AS_CONCAT`、`REAL_AS_FLOAT`、`STRICT_ALL_TABLES`、`STRICT_TRANS_TABLES`、`TIME_TRUNCATE_FRACTIONAL`），支持动态设置，全局和会话范围。命令行选项 `--sql-mode=name`，默认 `ONLY_FULL_GROUP_BY`、`STRICT_TRANS_TABLES`、`NO_ZERO_IN_DATE`、`NO_ZERO_DATE`、`ERROR_FOR_DIVISION_BY_ZERO`、`NO_ENGINE_SUBSTITUTION`

*   `table_open_cache`

    整型，全局范围，默认 4000，范围 1 ~ 524288。指示所有线程的打开表数。增大将增加 mysqld 所需的文件描述符数量。可以通过检查 `Opened_tables` 状态变量来检查是否需要增加表缓存。如果 `Opened_tables` 很大，且不 `FLUSH TABLE`（强制关闭并重新打开所有表），则应增加该值
    
*   `table_open_cache_instances`

    整型，默认 16，范围 1~64，全局范围，支持动态，命令行选项 `--table-open-cache-instances=#`。打开表缓存实例的数量。打开的表缓存会划分为 `table_open_cache/table_open_cache_instances`，会话只需要锁定一个实例即可访问获取 DML 语句（DDL 语句仍然需要锁定整个缓存），在多会话访问表时可以提高性能

    在 16 核心及以上建议将值设置为 8 或 16

*   `thread_cache_size`

    整型，全局，命令行选项 `--thread-cache-size=#`，默认 -1，范围 0 ~ 16384。指示服务器应缓存线程数。当客户端断开连接时，如果小于 `thread_cache_size`，将客户端线程放入缓存中。如果有许多新连接，应设置该值足够高，默认计算公式（8 + (max_connections / 100)）上限 100

*   `thread_handling`

    枚举（`no-threads`（服务器使用一个线程处理一个连接）、`one-thread-per-connection`（默认，服务器使用一个线程处理每个客户端连接）、`loaded-dynamically`（由线程池插件在初始化时设置）），全局范围，无法动态设置。指示服务器用于连接线程的线程处理模型。

*   `thread_stack`

    整型，全局范围，无法动态设置，命令行选项 `--thread-stack=#`，默认值（64 位：286720，32 位：221184），范围：131072 ~ double.max（64）/int.max(32)。块大小 1024。指示每个线程的堆栈大小。默认即可正常运行，如果线程堆栈太小，会限制服务器可以处理 SQL 的复杂性、存储过程的递归深度和其他消耗内存的操作

*   `time_zone`

    当前时区，此变量用于初始化每个连接的客户端时区，全局和会话范围。默认 `SYSTEM`，启动项 `--default-time-zone` 指定。8.0.19 开始的值范围 `-14:00~14:00`，8.0.18 之前值范围 `-12:59~13:00`

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

###### 复制相关

* `--binlog-do-db=db_name`

    类似于 `--replicate-do-db` 影响复制的方式来影响二进制日志记录。

    此选项的效果取决于使用的是基于语句的记录格式还是基于行的日志记录格式（同 `--replicate-do-db`）。DDL 语句始终作为语句记录，而不考虑有效的记录

    **基于语句的日志记录**

    只有那些语句被写入二进制日志，要指定多个数据库，多次使用此选项，每个数据库指定一次，不能使用列表；但是，这样做不会导致在选择其他数据库或没有数据库时记录跨数据库语句（`UPDATE some_db.some_table SET foo='bar'`）。默认数据库为使用 `selected` 或 `use` 语句指定的

    ```mysql
    # --binlog-do-db=sales 以下语句不会记录到 binlog
    USE prices;
    UPDATE sales.january SET amount=amount+1000;
    # --binlog-do-db=sales 以下语句会记录 binlog，sales 是 update 发出该语句时的默认数据库
    USE sales;
    UPDATE prices.discounts SET percentage = percentage + 10;
    ```

    **基于行的日志记录**

    记录仅限于指定数据库的表，不影响默认数据库。

    ```mysql
    # --binlog-do-db=sales 不会记录，即使使用 USE sales 也不会记录
    USE prices;
    UPDATE prices.march SET amount=amount-25;
    # --binlog-do-db=sales 会记录
    USE prices;
    UPDATE sales.february SET amount=amount+100;
    ```

* `--binlog-ignore-db=db_name`

    类似 `--replicate-ignore-db` ，取决于日志格式。要指定多个忽略的数据库，多次使用此选项，每个数据库指定一次。

    **基于语句的日志记录**

    会略使用 USE 等于 db_name 的数据库语句

    ```mysql
    # --binlog-ignore-db=sales 会记录
    USE prices;
    UPDATE sales.january SET amount=amount+1000;
    ```

    **基于行的格式**

    忽略 db_name 中的表记录

    ```mysql
    # --binlog-ignore-db=sales 不会记录
    USE prices;
    UPDATE sales.january SET amount=amount+1000;
    ```

    

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
|      `Table_open_cache_hits`       |                     打开表的缓存命中查找                     |
|     `Table_open_cache_misses`      |                    打开表的缓存未命中查找                    |
|    `Table_open_cache_overflows`    | 打开表缓存的溢出次数。打开或关闭表后，高速缓存实例具有未使用的条目且实例的大小大于 `table_open_cache`/`table_open_cache_instances` 的次数 |
|          `Threads_cached`          |                      线程缓存中的线程数                      |
|        `Threads_connected`         |                       当前打开的连接数                       |
|         `Threads_created`          | 创建用于处理连接的线程数。如果该值较大，可能需要增加 `thread_cache_size` 值 |
|         `Threads_running`          |                        未休眠的线程数                        |
|              `Uptime`              |                       服务器启动的秒数                       |
|    `Uptime_since_flush_status`     |                上次 `FLUSH STATUS` 以来的秒数                |

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

MySQL 可以在不同的 SQL 模式下运行，并且可以针对不同的 clients 应用这些模式，具体取决于 `sql_mode` 系统变量。模式会影响 MySQL 支持的 SQL 语法以及它执行的数据验证检查。每种模式都可以独立开启和关闭。使用 `InnoDB` 表时，还要考虑 `innodb_strict_mode` ，5.7.6 以后该值默认开启

SQL 模式可以在运行中设置，支持会话与全局设置，复制分区表时，master 和 slave 上的不同 SQL 模式也会导致问题。为了获得最佳结果，应始终在 master 和 slave 上使用相同的服务器 SQL 模式

##### 全部模式

###### `ALLOW_INVALIDDATES`

不对日期进行全面检查。仅检查月份是否在 1 ～ 12 、日期是否在 1～31 内。适用于`date` 和 `datetime` ，不适用于 `timestamp` （它总是需要有效的日期）。禁用时：服务器要求月和日值合法，而不仅仅在在 1~12，1～31 的范围内。

禁用严格模式后，`2001-04-31` 等无效日期将转换为 `0000-00-00` 并生成警告。启用严格模式后，无效日期会生成错误

###### `ANSI_QUOTES`

将 `"` 作为标识符（与 ` 类似），启用后，不能使用双引号引用字符串

###### `ERROR_FOR_DIVISION_BY_ZERO`

对于除 0 的处理，包括 `MOD(N, 0)` 和数据更改（`INSERT`、`UPDATE`）操作，其效果取决于是否启用了严格的 SQL 模式。

*   未启用严格模式

    未启用该模式，除 0 将插入 `NULL` 并且不产生警告；启用此模式，则除 0 会插入 `NULL` 并产生警告。

*   启用严格模式

    启用该模式，则除非另外指定 `IGNORE`，否则除 0 会产生错误，对于 `INSERT IGNORE` ，`UPDATE IGNORE`，除 0 将插入 `NULL`，并产生警告。

对于 `SELECT` ，除 0 将返回 `NULL`。启用该模式会产生警告，无论是否启用严格模式

该模式已过时，不是严格模式的一部分，但应该于严格模式结合使用，并且默认情况下处于启用状态。如果在未启用严格模式下启用该模式，则会产生警告。如果启用该模式但未启用严格模式，也会产生警告

###### `HIGH_NOT_PRECEDENCE`

`NOT` 运算法的优先级`NOT a BETWEEN b AND c` 等表达式被解析为 `NOT (a BETWEEN b AND C)`。在某些旧版本中被解析为 `(NOT a) BETWEEN b AND c`。启用该模式，可以获得旧的解析行为。

```mysql
SET sql_mode = '';
SELECT NOT 1 BETWEEN -5 AND 5; # 返回 0
SET sql_mode = 'HIGH_NOT_PERCEDENCE';
SELECT NOT 1 BETWEEN -5 AND 5; # 返回 1
```

###### `IGNORE_SPACE`

允许函数和 `(` 之间的空格，这会使函数名被视为保留字（只适用于内置函数名）

###### `NO_AUTOVALUE_ON_ZERO`

会影响 `AUTO_INCREMENT` 列的处理，通常可以指定 `null` 或 0 来生成下一个自增值。启用该模式，则传入 0 值时不会生成自增值

###### `NO_AUTO_CREATE_USER`

除非指定了身份验证信息，否则防止 `GRANT` 语句自动创建用户。该语句必须使用 `IDENTIFIED BY` 指定非空秘密或使用 `INENTIFIED WITH` 指定身份验证插件，未来会移除

###### `NO_BACKSLASH_ESCAPES`

禁止在字符串和标识符中使用 `\` 作为转义字符。启用此模式后，反斜杠就像其他任何一个普通字符一样

###### `NO_DIR_IN_CREATE`

当创建表时，忽略所有 `INDEX DIRECTORY` 和 `DATA DIRECTORY` 指令。此选项在从属服务器上很有用

###### `NO_ENGINE_SUBSTITUTION`

当诸如 `CREATE TABLE` 或 `ALTER TABLE` 之类的语句指定禁用或未编译的存储引擎时，控制是否使用默认存储引擎自动替换。

禁用对于创建表将使用默认引擎，如果所需的引擎不可用，会警告，对于修改表，发生警告，并且不更改表。启用该模式，如果所需引擎不可用，不会修改或创建表，会报错

###### `NO_FIELD_OPTIONS`

不在 `SHOW CREATE TABLE` 的输出中打印特定于 MySQL 的列选项。`mysqldump`在可移植模式下使用此模式，5.7.22 开始，不推荐使用，未来会移除

###### `NO_KEY_OPTIONS`

不在 `SHOW CREATE TABLE` 的输出中打印 MySQL 特定的索引选项。`mysqldump` 在可移植模式下使用此模式。5.7.22 开始，不推荐使用，未来会移除

###### `NO_TABLE_OPTIONS`

不在 `show create table` 的输出中打印特定 MySQL 选项，5.7.22 开始，不推荐使用，未来会移除

###### `NO_UNSIGNED_SUBTRACTION`

默认情况下，无符号整数值之间的减法会产生无符号结果。如果结果为负，则将导致错误。如果启用了该模式，则结果为负。如果将此类操作的结果用于更新 `UNSIGNED` 整数列，则结果将被裁剪为该列类型的最大值，如果启用该模式，则将其裁剪为 0。启用严格模式后，将发生错误，并且列保持不变。

###### `NO_ZERO_DATE`

是否允许将 `0000-00-00` 作为有效日期。其效果取决于是否启用了严格模式

*   未启用严格模式

    未启用该模式，`0000-00-00` 允许插入不会产生警告。启用该模式 `0000-00-00` 则允许插入会产生警告。

*   如果启用此模式和严格模式，则不允许 `0000-00-00` 插入且会产生错误。除非指定 `IGNORE`，对于 `INSERT IGNORE` 和 `UPDATE IGNORE`，`0000-00-00` 允许插入并产生警告

不推荐使用，`NO_ZERO_DATE` 不是严格模式的一部分，但应与严格模式结合使用，并且默认情况下处于启用状态。如果未启用严格模式启用了该模式则会产生警告，反之亦然。

###### `NO_ZERO_IN_DATE`

影响服务器是否允许年份部分非零，但月份和日期部分为 0（`2010-00-01`，`2019-01-00`）但不影响 `0000-00-00`

*   未启用严格模式

    未启用该模式，则允许零部分的日期，插入不会产生警告。启用此模式，则将零部分日期插入为 `0000-00-00` 并产生警告。

*   启用严格模式

    如果启用了此模式除非同时指定 `IGNORE`，否则不允许插入，且会报错。对于 `INSERT IGNORE` 和 `UPDATE IGNORE`，将零部分的日期作为 `0000-00-00` 插入并产生警告

###### `ONLY_FULL_GROUP_BY`

对于使用 `GROUP BY` 进行查询的 SQL，不允许 `SELECT` 部分出现 `GROUP BY` 中未出现的字段即 `SELECT` 查询的字段必须是 `GROUP BY` 中出现的或者使用聚合函数的或者是具有唯一属性的。不论是否启用该模式，`HAVING` 子句都可以引用别名

###### `PAD_CHAR_TO_FULL_LENGTH`

默认情况下，在检索时从 `CHAR` 列值修剪尾部空格。启用该模式则不会修剪。并且检索的 `CHAR` 值将填充到其全长。此模式不适用于 `VARCHAR` 列，在检索时保留尾部空格，8.0.13 开始弃用

###### `PIPES_AS_CONCAT`

将 `||` 视为连接操作符与 `CONCAT` 相同，而不是或的同义词

###### `REAL_AS_FLOAT`

将 `REAL` 作为 `FLOAT` 代名词，默认情况下，`REAL` 作为 `DOUBLE` 代名词

###### `STRICT_ALL_TABLES`

为所有存储引擎启用严格的 SQL 模式，无效的数据值将被拒绝。

###### `STRICT_TRANS_TABLES`

为事务性存储引擎以及可能的情况下为非事务性存储引擎启用严格的 SQL 模式。如果不能按照给定值插入事务表中，则中止该语句。对于非事务表，如果该值出现在单行语句或多行语句第一行中，则中止该语句

###### `TIME_TRUNCATE_FRACTIONAL`

控制是否舍入或截断插入时 `TIME`，`DATE`，`TIMESTAMP` 的超过声明允许的小数部分，默认舍入，启用此模式截断

##### 组合 SQL 模式

提供以下特殊模式作为模式值组合的简写

###### `ANSI`

相当于 `REAL_AS_FLOAT`，`PIPES_AS_CONCAT`，`ANSI_QUOTES`，`IGNORE_SPACE`，`ONLY_FULL_GROUP_BY`。还会导致无法将具有外部引用的聚合函数集合到已解决外部引用的外部查询中

```mysql
# max(t1.b) 不能再外部查询中聚合，因为它出现再 where 查询子句中此时返回错误
SELECT * FROM t1 WHERE t1.a IN (SELECT MAX(t1.b) FROM t2 WHERE ...);
```

###### `TRADITIONAL`

相当于 `STRICT_TRANS_TABLES`，`STRICT_ALL_TABLES`，`NO_ZERO_IN_DATE`，`NO_ZERO_DATE`，`ERROR_FOR_DIVISION_BY_ZERO`，`NO_ENGINE_SUBSTITUTION`

##### 严格 SQL 模式行为

###### 严格 SQL 模式

严格模式即单独或同时启用 `STRICT_TRANS_TABLES` 或 `STRICT_ALL_TABLES` 模式

严格模式控制 MySQL 如何处理数据更改语句（`INSERT` 或 `UPDATE`）中的无效或缺失值，还会影响 DDL 语句。如果严格模式未启用，则 MySQL 会为无效或缺失的值插入调整后的值并产生警告。在严格模式下，可以通过 `INSERT IGNORE` 和 `UPDATE IGNORE` 来达到此效果。对于 `SELECT` 不更改数据的语句，无效值会在严格模式下生成警告，而不是错误。严格模式不影响是否检查外键约束（`foreign_key_cheks`）。如果试图创建超过最大长度的索引，严格模式会产生错误。未启用严格模式会导致警告把索引截断为最支持长度

`STRICT_ALL_TABLES` 和 `STRICT_TRANS_TABLES` 区别：

* 事务表

    启用 `STRICT_ALL_TABLES` 时，数据更改语句中的无效值和缺失值会发生错误，该语句被中止并回滚

* 非事务表

    如果在要插入或更新的第一行中出现错误，语句中止且表保持不变。如果该语句插入或修改了多行，且错误值出现在第二行或更高行中，则结果取决于启用了那种严格模式：

    *   `STRICT_ALL_TABLES`

        MySQL 返回错误，并忽略其余行，由于已插入或更新了较早的行，因此结果是部分更新的。为了避免这种情况，请使用单行语句，该语句可以在不更改表的情况下中止；

    *   `STRICT_TRANS_TABLES`

        MySQL 将无效值转换为该列的最接近的有效值，并插入调整后的值。如果缺少值，MySQL 将为列数据类型插入隐式默认值。无论哪种情况，MySQL 都会生成警告而不是错误，并继续处理该语句

严格模式会影响除零（`MOD(N,0)`）的处理，对于数据更改操作：如果未启用严格模式，则除以零将插入 NULL 并且不产生警告；如果启用了严格模式，则除非 `IGNORE` 同样给出，否则除零会产生错误。对于 `INSERT IGNORE` 和 `UPDATE IGNORE`，除零将插入 NULL 并产生警告；对于 select 除零返回 null。启用严格模式也会引起警告

如果未启用严格模式，`0000-00-00` 则允许插入不会产生任何警告，启用严格模式，不允许插入且会报错，除非指定 `IGNORE` 关键字，将 `0000-00-00` 插入并产生警告；

对于月份和日期部分为 0 的日期，如果未启用严格模式，则允许零部分的日期，并且插入不会产生任何警告。如果启用了严格模式，则除非 `IGNORE` 同时指定，否则不允许使用，插入会产生错误，对于 `INSERT IGNORE` 和 `UPDATE IGNORE`，将作为 `0000-00-00` 插入，并产生警告

严格模式适用于以下语句：`ALTER TABLE`、`CREATE TABLE`、`CREATE TABLE...SELECT`、`DELETE`、`INSERT`、`LOAD DATA`、`LOAD XML`、`SELECT SLEEP()`、`UPDATE`。在存储中，如果在严格模式生效时定义了程序，则以上语句将在严格模式下指向。严格模式适用于以下错误

```
ER_BAD_NULL_ERROR
ER_CUT_VALUE_GROUP_CONCAT
ER_DATA_TOO_LONG
ER_DATETIME_FUNCTION_OVERFLOW
ER_DIVISION_BY_ZERO
ER_INVALID_ARGUMENT_FOR_LOGARITHM
ER_NO_DEFAULT_FOR_FIELD
ER_NO_DEFAULT_FOR_VIEW_FIELD
ER_TOO_LONG_KEY
ER_TRUNCATED_WRONG_VALUE
ER_TRUNCATED_WRONG_VALUE_FOR_FIELD
ER_WARN_DATA_OUT_OF_RANGE
ER_WARN_NULL_TO_NOTNULL
ER_WARN_TOO_FEW_RECORDS
ER_WRONG_ARGUMENTS
ER_WRONG_VALUE_FOR_TYPE
WARN_DATA_TRUNCATED
```

###### IGNORE 关键字

当 `IGNORE` 关键字和严格模式都有效时，`IGNORE` 优先。对于多行语句，`IGNORE` 使该语句跳至下一行而不是中止（对于不可忽略的错误，忽略 `IGNORE` 关键字，报错）。`IGNORE` 关键字忽略以下错误

```
ER_BAD_NULL_ERROR
ER_DUP_ENTRY
ER_DUP_ENTRY_WITH_KEY_NAME
ER_DUP_KEY
ER_NO_PARTITION_FOR_GIVEN_VALUE
ER_NO_PARTITION_FOR_GIVEN_VALUE_SILENT
ER_NO_REFERENCED_ROW_2
ER_ROW_DOES_NOT_MATCH_GIVEN_PARTITION_SET
ER_ROW_IS_REFERENCED_2
ER_SUBQUERY_NO_1_ROW
ER_VIEW_CHECK_FAILED
```

`IGNORE` 关键字对以下语句生效

*   `CREATE TABLE...SELECT: IGNORE`

    `IGNORE` 关键字不适用 `CREATE TABLE` 和 `SELECT` 部分，但适用于插入表中的 SELECT 值，与唯一键值上现有行重复的行将被丢弃

*   `DELETE`

    删除行过程中忽略错误

*   `INSERT`

    将删除再唯一键值上重复的现有行。或将错误数值修改为最接近的有效值。对于没有找到与给定值匹配的分区的分区表，IGNORE 对于包含不匹配值的行，会静默失败

*   `LOAD DATA`、`LOAD XML`

    重复的唯一键上的值都会丢弃

*   `UPDATE`

    不会更新在唯一键值上发生重复键冲突的行。错误的值会修改为最接近的有效值



