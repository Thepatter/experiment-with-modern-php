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

###### 服务端设置

*   `collation_server`

    设置服务器的默认排序规则，支持全局和会话，对应启动项为 `--collation-server=name`，字符串值，默认 `utf8mb4_0900_ai_ci`

*   `collation_database`

    默认数据库使用的排序规则，每当更改默认数据库时，服务器都会设置此变量。如果没有默认数据库，与 collation_server 一致。支持全局和会话

*   `persisted_globals_load`

    指定是否从数据目录 `mysqld-auto.cnf` 加载持久配置。默认会加载

    命令行选项 `--persisted-globals-load[={OFF|ON}]`，全局范围，布尔值，默认 ON

##### 服务器状态

服务器维护了许多系统状态，提供系统信息。

```mysql
SHOW [GLOBAL | SESSION] STATUS
```

###### 服务器系统变量

|        系统变量         |                  含义                  |
| :---------------------: | :------------------------------------: |
|    `Aborted_clients`    | 由于客户端未正确关闭连接而中止的连接数 |
|   `Aborted_connects`    |   连接到 MySQL 服务器的失败尝试次数    |
| `Binlog_cache_disk_use` |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |
|                         |                                        |

