*java.sql.ResultSet*

```java
// 将结果集中的当前行向前移动一行。如果已经到达最后一行的后面，则返回 `false`。初始情况下必须调用该方法才能转到第一行
boolean next() throws SQLException;
// 获取对应索引的 Xxx 的类型数据
Xxx getXxx(int columnIndex) throws SQLException;
// 获取对应 column lable 的 Xxxv 类型数据
Xxx getXxx(String columnLable) throws SQLException;
// 用给定的列序号或列标签返回该列的类型对应的 Java 数据类型
<T> T getObject(int columnIndex, Class<T> type) throws SQLException;
<T> T getObject(String columnLable, Class<T> type) throws SQLException;
/**
 * 用给定的列序号或列标签更新该列的值
 * @param columnIndex or columnLable 列索引及列名
 * @param x column 新值
 * @param targetSqlType 要更新到数据库的 SQL 类型
 * @param scaleOrLength 对于 java.math.BigDeciaml, 为小数点后位数，对于 InputStream 和 Reader 这是长度
 *                      或读取器中的数据，对于所有其他类型，此值被忽略
 */
void upodateObject(int columnIndex, Object x, SQLType targetSqlType, int scaleOrLength) throws SQLException;
void updateObject(String columnLabel, Object x, SQLType targetSqlType, int scaleOrLength) throws SQLException;
void updateObject(int columnIndex, Object x, SQLType targetSqlType) throws SQLException;
void updateObject(String columnLable, Object x, SQLType targetSqlType) throws SQLException; 
// 返回对应列名的列索引
int findColumn(Strirng columnLable) throws SQLException;
// 关闭
void close() throws SQLException;
// 是否关闭
boolean isClose() throws SQLException;
// 获取警告
SQLWarning getWarnings() throws SQLException;
// 返回对应列索引/名称的 Blob/Clob 对象
Blob getBlob(int columnIndex);
Blob getBlob(String columnLable);
Clob getClob(int columnIndex);
Clob getClob(String columnLable);
// 返回结果集类型, TYPE_FORWARD_ONLY(光标只能向前移动)、TYPE_SCROLL_INSENSITIVE（可滚动，但对数据不敏感）、TYPE_SCROLL_SENSITIVE（可滚动，并且通常对数据更改敏感）
int getType() throws SQLException;
// 返回结果集并发设置，CONCUR_READ_ONLY （只读），CONCUR_UPDATEABLE（可更新）
int getConcurrency() throws SQLException;
// 将游标移动到前一行。如果游标位于某一行上，则返回 true；如果游标位于第一行之前地位置，则返回 false
boolean previous() throws SQLException;
// 得到当前行地序号。所有行从 1 开始编号
int getRow() throws SQLException;
// 滚动结果集 n 为正，游标向前移动。如果 n 为负，游标将向后移。如果 n 为 0，那么调用该方法将不起任何作用。如果试图将游标移动到当前行集的范围之外，即根据 n 值的正负号，游标需要被设置在最后一行之后或第一行之前，那么，该方法将返回 false，且不移动游标，如果游标位于一个实际的行上，那么该方法将返回 true
boolean relative( int rows ) throws SQLException;
// 移动游标到第 row 行。如果游标位于某一行上，则返回 true
boolean absolute( int row ) throws SQLException;
// 移动游标第一行，如果光标位于有效行上，返回 true
boolean first() throws SQLException;
// 移动游标最后一行，如果光标位于有效行上，返回 true
boolean last() throws SQLException;
// 移动游标到第一行之前或最后一行之后位置
void beforeFirst() throws SQLException;
void afterLast() throws SQLException;
// 测试光标是否是最后一行，该调用可能很昂贵
boolean isLast() throws SQLException;
// 测试光标是否在第一行
boolean isFirst() throws SQLException;
// 测试游标是否在第一行之前或最后一行之后的位置
boolean isBeforeFirst() throws SQLException;
boolean isAfterLast() throws SQLException;
// 移动游标到插入行。插入行是一个特殊的行，可以在该行上使用 updateXxx 和 insertRow 方法来插入新数据
void moveToInsertRow() throws SQLException;
// 将游标从插入行移回调用 moveToInsertRow 方法之前它所在的那一行
void moveToCurrentRow() throws SQLException;
// 将插入行上的内容插入到数据库和结果集中, 调用此方法时，光标必须位于插入行上
void insertRow() throws SQLException;
// 更新当前行（结果集和数据库），当光标位于插入行时，不能调用此方法
void updateRow() throws SQLException;
// 用 Xxx 更新指定列（当前行或插入行）的值，不会更新数据库，必须使用 updateRow 或insertRow 更新数据库
void updateXxx(int columnIndex, Xxx data);
void updateXxx(String columnLabel, Xxx data);
// 撤销对当前行的更新, 如果 updateRow 已被调用，该方法无效
void cancelRowUpdates() throws SQLException;
// 返回与当前 ResultSet 对象中的列相关的元数据
ResultSetMetaData getMetaData() throws SQLException;
```

