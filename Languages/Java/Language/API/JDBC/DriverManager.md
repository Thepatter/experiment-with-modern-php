*java.sql.DriverManager*

```java
// 注册 JDBC 驱动器
static void registerDriver(java.sql.Driver driver);
// 注销 JDBC 驱动器
static void deregisterDriver(Driver dirver) throws SQLException;
// 建立和数据库的连接
static Connection getConnection(String url, java.util.Properties info) throws SQLException;
static Connection getConnection(String url, String user, String password) throws SQLException;
// 设置连接超时时间
static void setLoginTimeout(int seconds);
// 设定输出 JDBC 日志的 PrintWriter 对象
static void setLogWriter(java.io.PrintWriter out)
```

