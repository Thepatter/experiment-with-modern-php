## java.sql.ResultSet

* `boolean next()`

  将结果集中的当前行向前移动一行。如果已经到达最后一行的后面，则返回 `false`。初始情况下必须调用该方法才能转到第一行

* `Xxx getXxx(int columnNumber)`

* `Xxx getXxx(String columnLabel)`

  （`Xxx` 指数据类型，如 `int`、`double`、`String`、`Date`）

* `<T> T getObject(int columnIndex, Class<T> type)`

* `<T> T getObject(String columnLable, Class<T> type)`

* `void updateObject(int columnIndex, Object x, SQLType targetSqlType)`

* `void updateObject(String columnLabel, Object x, SQLType targetSqlType)`

  用给定的列序号或列标签返回更新该列的值，并将值转换成指定的类型。列标签是 `SQL` 的 AS 子句中指定的标签，在没有使用 AS 时，它就是列名

* `int findColumn(String columnName)`

  根据给定列名，返回该列的序号

* `void close()`

  立即关闭当前的结果集

* `boolean isClosed()`

  如果该语句被关闭，则返回 `true`

* `SQLWarning getWarnings()`

  返回未处理警告中的第一个，或者在没有未处理警告时返回 null

* `Blob getBlob(int columnIndex)`

* `Blob getBlob(String columnLabel)`

* `Clob getClob(int columnIndex)`

* `Clob getClob(String columnLabel)`

  获取给定列的 `BLOB` 或 `CLOB`

* `int getType()`

  返回结果集地类型。返回值为以下常量之一 `TYPE_FORWARD_ONLY`、`TYPE_SCROLL_INSENSITIVE` 、`TYPE_SCROLL_SENSITIVE`

* `int getConcurrency()`

  返回结果集地并发设置。返回值为以下常量之一：`CONCUR_READ_ONLY` 、`CONCUR_UPDATABLE`

* `boolean previous()`

  将游标移动到前一行。如果游标位于某一行上，则返回 `true`；如果游标位于第一行之前地位置，则返回 `false`

* `int getRow()`

  得到当前行地序号。所有行从 1 开始编号

* `boolean absolute(int r)`

  移动游标到第 r 行。如果游标位于某一行上，则返回 true

* `boolean first()`

* `boolean last()`

  移动游标到第一行或最后一行。如果游标位于某一行上，则返回 `true`

* `void beforeFirst()`

* `void afterLast()`

  移动游标到第一行之前或最后一行之后位置

* `boolean isFirst()`

* `boolean isLast()`

  测试游标是否在第一行或最后一行

* `boolean isBeforFirst()`

* `boolean isAfterLast()`

  测试游标是否在第一行之前或最后一行之后的位置

* `void moveToInsertRow()`

  移动游标到插入行。插入行是一个特殊的行，可以在该行上使用 `updateXxx` 和 `insertRow` 方法来插入新数据

* `void moveToCurrentRow()`

  将游标从插入行移回调用 `moveToInsertRow` 方法之前它所在的那一行

* `void insertRow()`

  将插入行上的内容插入到数据库和结果集中

* `void deleteRow()`

  从数据库和结果集中删除当前行

* `void updateXxx(int column, Xxx data)`

* `void updateXxx(String columnName, Xxx data)`

  Xxx 指数据类型，比如 `int`，`double` ，`String`，`Date` 等更新结果中当前行上的某个字段值

* `void updateRow()`

  将当前行的更新信息发送到数据库

* `void cancelRowUpdates()`

  撤销对当前行的更新

* `ResultSetMetaData getMetaData()`

  返回与当前 `ResultSet` 对象中的列相关的元数据

