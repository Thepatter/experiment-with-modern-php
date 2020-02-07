*java.sql.DatabaseMetaData*
```java
boolean supportsResultSetType(int type);

// 如果数据库支持给定类型的结果集，则返回 `true`。`type` 是 `ResultSet` 接口中常量之一

`TYPE_FORWARD_ONLY`、`TYPE_SCROLL_INSENSITIVE`、`TYPE_SCROLL_SENSITIVE`
boolean supportsResultSetConcurrency(int type, int concurrency);

// `type`		`ResultSet` 接口中的下列常量之一：`TYPE_FORWARD_ONLY`、`TYPE_SCROLL_INSENSITIVE`、`TYPE_SCROLL_SENSITIVE`

// `concurrency`	`ResultSet` 接口中的下列常量之一：`CONCUR_READ_ONLY`、`CONCUR_UPDATABLE

ResultSet getTables(String catalog, String schemaPattern, String tableNamePattern, String types[]);

// 返回某个目录中所有表的描述，该目录必须匹配给定的模式、表名字模式以及类型标准。（模式用于描述一组相关的表和访问权限，而目录描述的是一组相关的模式）

// `catalog` 和 `schema` 参数可以为 ""， 用于检索那些没有目录或模式的表。如果不想考虑目录和模式，也可以将上述参数设为 null

// `types` 数组包含了所需的表类型的名称，通常表类型有 `TABLE`、`VIEW`、`SYSTEM`、`TABLE`、`GLOBAL`、`TEMPORARY`、`ALIAS`、`SYNONYM`。如果 `types` 为 null，则返回所有类型的表

// 返回的结果集共有 5 列，均为 `String` 类型

// `TABLE_CAT`		表目录（可以为 null）

// `TABLE_SCHEM`	表模式（可以为 null）

// `TABLE_NAME`		表名称

// `TABLE_TYPE`		表类型

// `REMARKS`		表的注释

int getJDBCMajorVersion();

int getJDBCMinorVersion();

// 返回建立数据库连接的 JDBC 驱动程序的主版本号和次版本号。

int getMaxConnections();

// 返回可同时连接到数据库的最大并发连接数

int getMaxStatements();

// 返回单个数据库连接允许同时打开的最大并发语句数。如果对允许打开的语句数目没有限制或不可知，则返回 0

boolean supportsBatchUpdates();

// 如果驱动程序支持批量更新，则返回 true

```