*Connection.md*
```java
```java
// // 立即关闭当前的连接，并释放由它所创建的 JDBC 资源

void close();

// // 返回未处理警告的下一个警告，或者在到达链尾时返回 null

SQLWarning getWarnings();

// // 创建一个空的 BLOB

Blob createBlob();

// // 创建一个空的 CLOB 对象

Clob createClob();

/**
// * 创建一个预备语句，且该语句可以产生指定类型和并发模式的结果集

// * @parameter command 预处理语句

// * @parameter type ResultSet 接口中下列常量之一：TYPE_FORWARD_ONLY、TYPE_SCROLL_INSENSITIVE、

*                           TYPE_SCROLL_SENSITIVE
// * @parameeter concurrency ResultSet接口中的下列常量之一：CONCUR_READ_ONLY、CONCUR_UPDATABLE

*/
PreparedStatement prepareStatement(String command, int type, int concurrency);

// //  返回一个含预编译语句的 PreparedStatement 对象。sql 代表一个 SQL 语句，该语句可以包含一个或多个由 ？字符指明的参数占位符

PreparedStatement prepareStatement(String sql);

// // 创建一个语句，参数同上

Statement createStatement(int type, int concurrency);

// // 创建一个 Statement 对象，用以执行不带参数的 SQL 查询和更新

Statement createStatement();

// // 返回一个 DatabaseMetaData 对象，该对象封装了有关数据库连接的元数据

DatabaseMetaData getMetaData();

// // 获取该连接中的自动提交模式

boolean getAutoCommit();

// // 设置该连接中的自动提交模式。如果自动更新为 true，那么所有语句将在执行结束后立刻被提交

void setAutoCommit(boolean b);

// // 提交自上次提交以来所有执行过的语句

void commit();

// // 撤销自上次提交以来所有执行过的语句所产生的影响

void rollback();

// // 设置匿名保存点

Savepoint setSavepoint();

// // 设置一个具名的保存点

Savepoint setSavepoint(String name);

// // 回滚到给定保存点

void rollback(Savepoint svpt);

// // 释放给定的保存点

void releaseSavepoint(Savepoint svpt);

// // 返回表示数据库的元数据的 DatabaseMetaData 对象，元数据包含了描述数据库的相关信息

DatabaseMetaData getMetaData();

```
```