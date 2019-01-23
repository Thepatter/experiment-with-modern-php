## java.sql.DatabaseMetaData

* `boolean supportsResultSetType(int type)`

  如果数据库支持给定类型的结果集，则返回 `true`。`type` 是 `ResultSet` 接口中常量之一

  `TYPE_FORWARD_ONLY`、`TYPE_SCROLL_INSENSITIVE`、`TYPE_SCROLL_SENSITIVE`

* `boolean supportsResultSetConcurrency(int type, int concurrency)`

  `type`		`ResultSet` 接口中的下列常量之一：`TYPE_FORWARD_ONLY`、`TYPE_SCROLL_INSENSITIVE`、`TYPE_SCROLL_SENSITIVE`

  `concurrency`	`ResultSet` 接口中的下列常量之一：`CONCUR_READ_ONLY`、`CONCUR_UPDATABLE`