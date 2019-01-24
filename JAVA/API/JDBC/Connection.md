## java.sql.Connection

* `Statement createStatement()`

  创建一个 `Statement` 对象，用以执行不带参数的 `SQL` 查询和更新

* `void close()`

  立即关闭当前的连接，并释放由它所创建的 `JDBC` 资源

* `SQLWarning getWarnings()`

  返回未处理警告的下一个警告，或者在到达链尾时返回 null

* `PreparedStatement prepareStatement(String sql)`

  返回一个含预编译语句的 `PreparedStatement` 对象。字符串 `sql` 代表一个 SQL 语句，该语句可以包含一个或多个由 ？字符指明的参数占位符

* `Blob createBlob()`

* `Clob createClob()`

  创建一个空的 `BLOB` 或 `CLOB`

* `Statement createStatement(int type, int concurrency)`

* `PreparedStatement prepareStatement(String command, int type, int concurrency)`

  创建一个语句或预备语句，且该语句可以产生指定类型和并发模式的结果集

  `command`		要预备的命令

  `type`			`ResultSet` 接口中的下列常量之一：`TYPE_FORWARD_ONLY`、`TYPE_SCROLL_INSENSITIVE`、`TYPE_SCROLL_SENSITIVE`

  `concurrency`	`ResultSet` 接口中的下列常量之一：`CONCUR_READ_ONLY`、`CONCUR_UPDATABLE`

* `DatabaseMetaData getMetaData()`

  返回一个 `DatabaseMetaData` 对象，该对象封装了有关数据库连接的元数据

* `boolean getAutoCommit()`

* `void setAutoCommit(boolean b)`

  获取该连接中的自动提交模式，或将其设置为 b。如果自动更新为 `true`，那么所有语句将在执行结束后立刻被提交

* `void commit()`

  提交自上次提交以来所有执行过的语句

* `void rollback()`

  撤销自上次提交以来所有执行过的语句所产生的影响

* `Savepoint setSavepoint()`

* `Savepoint setSavepoint(String name)`

  设置一个匿名或具名的保存点

* `void rollback(Savepoint svpt)`

  回滚到给定保存点

* `void releaseSavepoint(Savepoint svpt)`

  释放给定的保存点

