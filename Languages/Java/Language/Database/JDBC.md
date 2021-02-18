###  JDBC

#### JDBC 的设计

JDBC 以 Java 类库来取代数据库厂商的专有 API，客户端只需要调用 JDBC API，而由 JDBC 的实现层（JDBC 驱动程序）去处理数据的通信，不依赖于任何数据库平台。同一个 java 程序可以访问多种数据库服务器。

JDBC 的实现包括三部分：

* 驱动管理器：*java.sql.DriverManager* 类，由 Oracle 公司实现，负责注册特定 JDBC 驱动器，以及根据特定驱动器建立与数据库的连接

* 驱动器 API：由 Oracle 公司制定，最主要的接口是 *java.sql.Driver*

* 驱动器：由数据库供应商或其他第三方工具提供商创建，JDBC 驱动器实现了 JDBC 驱动器 API，负责与特定的数据库连接，以及处理通信细节。JDBC 驱动器可以注册到 JDBC 驱动管理器中

  JDBC 驱动器才是真正的连接 Java 应用程序与特定数据库的纽带，Java 应用如果希望访问某种数据库，必须先获得相应的 JDBC 驱动器的类库，然后把它注册到 JDBC 驱动管理器中

JDBC 驱动程序分为：

* JDBC-ODBC 桥

  JDBC-ODBC 桥本身也是一个驱动，利用这个驱动，可以使用 JDBC API 通过 ODBC 去访问数据库。实际把标准 JDBC 调用转换成相应的 ODBC 调用，通过 ODBC 库把它们发送给 ODBC 数据源。效率较低调用流程：

  1. Java 应用访问 JDBC API
  2. JDBC API 调用 JDBC-ODBC 桥 
  3. ODBC API 访问 ODBC 层
  4. ODBC 层访问数据库

  利用 JDBC-ODBC 桥访问数据库，需要客户端机器上具有 JDBC-ODBC 桥、ODBC 驱动和数据库本地 API。JDK 中，JDBC-ODBC 桥的实现类（sun.jdbc.odbc.JdbcOdbcDriver），Java 8 开始不再提供

* 部分本地 API，部分 Java 驱动程序

  JDBC 驱动将调用请求转换为厂商提供的本地 API 调用，数据库处理完请求将结果通过这些 API 返回，进而返回给 JDBC 驱动程序，JDBC 驱动将结果转化为 JDBC 标准形式，再返回给客户程序

  此种访问下，需要在客户端机器上安装本地 JDBC 驱动程序和特定厂商的本地 API

* JDBC 网络纯 Java 驱动程序

  利用作为中间件的应用服务器来访问数据库。应用服务器（WebLogic、Websphere）作为一个到多个数据库的网关，客户端通过它可以连接到不同的数据库服务器。应用服务器通常有自己的网络协议，Java 客户端通过 JDBC 驱动程序将 JDBC 调用发送给应用服务器，应用服务器使用本地驱动程序访问数据库

* 本地协议纯 Java 驱动程序

  完全由 Java 编写，通过与数据库建立直接的套接字连接，采用具体于厂商的网络协议把 JDBC API 调用转换为直接的网络调用（如 Oracle Thin JDBC Driver）

##### JDBC API

JDBC API 包含在 JDK 中，被分为 java.sql 和 javax.sql

###### java.sql

定义了访问数据库的接口和类，其中一些接口由驱动程序提供商实现

* Driver 接口，驱动器

    所有 JDBC 驱动器都必须实现 Driver 接口，JDBC 驱动器由数据库厂商或第三方提供。这个接口是提供给数据库厂商使用的。在编写访问数据库的 Java 程序时，必须把特定数据库的 JDBC 驱动器的类库加入到 classpath 中，`Driver.Connection()` 建立到数据库的连接

    *常用数据库驱动类名*

    |                      类名                      |     数据库     |
    | :--------------------------------------------: | :------------: |
    | `com.microsoft.jdbc.sqlserver.SQLServerDriver` | SQLServer 2005 |
    |       `oracle.jdbc.driver.OracleDriver`        |     Oracle     |
    |            `com.mysql.jdbc.Dirver`             | MySQL 8.0 之前 |
    |           `com.mysql.cj.jdbc.Driver`           |    MySQL 8     |

* DriverManager 类，驱动管理器

    用来建立和数据库的连接以及管理 JDBC 驱动器。该类方法都是静态的

    程序中不需要直接访问这些实现了 Driver 接口的类，而是由驱动程序管理器去调用这些驱动。通过 JDBC 驱动程序管理器注册每个驱动程序，使用驱动程序管理器类提供的方法来建立数据库连接。驱动程序管理器类的连接方法调用驱动程序类的 connec 方法建立数据库连接。

    DriverManager 类是驱动程序管理器类，负责管理驱动程序，在 DriverManager 类中提供 registerDriver 方法来注册驱动程序类的实例。通常不需要手动执行，一般 Driver 接口的驱动程序包含了静态代码块会调用 DriverManager.registerDriver 方法注册自身的一个实例

    ```java
    static Connection getConnection(String url) throws SQLException;
    static Connection getConnection(String url, String user, String password) throws SQLException;
    static Connection getConnection(String url, Properties info) throws SQLException;
    ```

    *常用数据库 URL*

    |       数据库        |                             URL                              |
    | :-----------------: | :----------------------------------------------------------: |
    |   SQL Server 2000   |   `jdbc:microsoft:sqlserver://localhost:1433;database=db`    |
    |   SQL Server 2005   |        `jdbc:sqlserver://localhost:1433;database=db`         |
    | Oracle 9i、10g、11g |           `jdbc:oracle:thin:@localhost:1521:ORCL`            |
    |     MySQL 8 前      |          `jdbc:mysql://localhost:3306/databasename`          |
    |       MySQL 8       | `jdbc:mysql://localhost:3306/dbname?useSSL=false&serverTimezone=UTC` |

* Connection 接口，数据库连接

* Statement 接口，执行 SQL 语句

* PreparedStatement 接口：负责执行预处理的 SQL 语句

* CallableStatement 接口：负责执行 SQL 存储过程

* ResultSet 接口，结果集

##### 高级 SQL 类型

*JDBC支持的 SQL数据类型在 Java 语言中对应的数据类型*

|               SQL Type               |      Java Type       |
| :----------------------------------: | :------------------: |
|            INTEGER or INT            |         int          |
|          SAMLLINT，TINYINT           |        short         |
|                BIGINT                |         long         |
| NUMERIC(m,n)，DECIMAL(m,n)，DEC(m,n) | java.math.BigDecimal |
|               FLOAT(n)               |        double        |
|                 REAL                 |        float         |
|                DOUBLE                |        double        |
|       CHARACTER(n) or CHAR(n)        |        String        |
|       VARCHAR(n), LONG VARCHAR       |        String        |
|               BOOLEAN                |       boolean        |
|                 DATE                 |    java.sql.Date     |
|                 TIME                 |    java.sql.Time     |
|              TIMESTAMP               |  java.sql.Timestamp  |
|                 BLOB                 |    java.sql.Blob     |
|                 CLOB                 |    java.sql.Clob     |
|                ARRAY                 |    java.sql.Array    |
|                ROWID                 |    java.sql.RowId    |
| NCHAR(n)，NVARCHAR(n)，LONG NVARCHAR |        String        |
|                NCLOB                 |    java.sql.NClob    |
|                SQLXML                |   java.sql.SQLXML    |

SQL ARRAY （SQL 数组）指的是值的序列。从数据库中获得一个 LOB 或数组并不等于获取了它的实际内容，只有在访问具体值时它们才会从数据库中被读出

#### JDBC 的典型用法

在 Java 程序中，通过 JDBC API 访问数据库包含以下步骤：

1. 获得要访问的数据库的 JDBC驱动器的类库，把它导入 classpath 中

2. 在程序中加载并注册 JDBC 驱动器。

3. 建立与数据库的连接

4. 创建 statement 对象

5. 执行 SQL 语句

6. 访问 ResultSet 中的记录集

    ResultSet 接口的迭代协议于 *java.util.Iterator* 接口不同。对于 ResultSet 接口，迭代器初始化时被设定在第一行之前的位置，必须调用 next() 方法将它移动到第一行。另外，它没有 hasNext 方法，需要不断地调用 next()，直至该方法返回 false

    **结果集中行地顺序是任意排列地**。除非使用 ORDER BY 子句指定行地顺序，否则不能为行序强加任何意义，当使用 get 方法获取 column 时，会自动转换成合适的对象

7. 依次关闭 ResultSet、Statement、Connection 对象

```java
public void accessDataDemo() {
    // 加载 JdbcDriver 类
    Class.forName("oracle.jdbc.driver.OracleDriver");
    // 注册 JDBC 驱动器，一般不需要手动注册
    java.sql.DriverManager.registerDriver(new oracle.jdbc.driver.OracleDriver());
    // 获取连接
    Connection conn = java.sql.DriverManager.getConnection(jdbc:oracle:thin:@localhost:1521:sid);
    // 创建 statement 对象
    Statement stmt = conn.createStatement();
    // 执行 SQL 语句
    String sql = "select id, name, price form goods where id = 1";
    ResultSet rs = stmt.executeQuery(sql);
    // 方法 ResultSet 结果集
    while (rs.next()) {
        int id = rs.getInt(1); 	// 列索引效率较高
        String name = rs.getString("name"); // 列名易于维护
        float price = rs.getFloat("price");
    }
    // 关闭
    rs.close();
    stmt.close();
    conn.close();
}
```

##### JDBC 配置

###### 数据库 URL

在连接数据库时，必须使用各种与数据库类型相关的参数，例如主机名，端口号和数据库名。JDBC 使用一种与普通 URL 相类似的语法来描述数据源。

```java
// jdbc URL 由 jdbc: + 数据库协议 + /数据库+其他参数
jdbc:subprotocol:other sutff
jdbc:mysql://localhost:3306/test?useSSL=false
```

###### 驱动程序 JAR 文件

在运行访问数据库的程序时，需要将数据库驱动程序的 JAR 文件包括到类路径中（编译时并不需要这个 JAR 文件）。在从命令行启动程序时，只需要使用下面的命令

```java
java -classpath driverPath:. ProgromName
```

###### 注册驱动器类

许多 JDBC 的 JAR 文件会自动注册驱动器类。*META-INF/services/java.sql.Driver* 文件的 JAR 文件可以自动注册驱动器类。手动注册驱动类：

* 一个方式是在 Java 程序中加载驱动器类，并手动注册驱动累

    ```
    Class.forName("org.postgresql.Driver");
    // 注册 JDBC 驱动器
    java.sql.DriverManager.registerDriver(new oracle.jdbc.driver.OracleDriver());
    ```

* 另一种方式可以用命令行参数来指定这个属性 `jdbc.drivers` 

    ```shell
    java -Djdbc.drivers=org.postgresql.Driver ProgramName
    ```

* 在应用中用下面这样的调用来设置系统属性

    ```java
    System.setProperty("jdbc.drivers", "org.postgresql.Driver");
    # 在这种方式中可以提供多个驱动器，用冒号将它们分隔开
    System.setProperty("jdbc.drivers", "org.postgresql.Driver:org.apache.derby.jdbc.ClientDriver");
    ```

##### 管理资源

如果所有连接都是短时的，那么无需考虑关闭语句和结果集。只需将 close 语句放在带资源的 try 语句（建议使用 带资源的 try 语句，并单独使用 try/catch 块处理异常）中，以便确保最终连接对象不可能继续保持打开状态

###### connection

每个 Connection 对象都可以创建一个或多个 Statement 对象。使用 DatebaseMetaData.getMaxStatements 方法可以获取 JDBC 驱动程序支持的同时活动的语句对象的总数

通常并不需要同时处理多个结果集。如果结果集相互关联，可以使用组合查询，这样只需要分析一个结果。对数据库进行组合查询比使用 Java 程序遍历多个结果集要高效的多

使用完 ResultSet，Statement 或 Connection 对象后，应立即调用 close 方法。这些对象都使用了规模较大的数据结果，它们会占用数据库存服务器上的有限资源

###### Statement

同一个 Statement 对象可以用于多个不相关的命令和查询。一个 Statement 对象最多只能有一个打开的结果集。如果需要执行多个查询操作，且需要同时分析查询结果，那么必须创建多个 Statement 对象

如果 Statement 对象上有一个打开的结果集，那么调用 close 方法将自动关闭该结果集。同样地，调用 Connection 类的 close 方法将关闭该连接上的所有语句

在 Statement 上调用 closeOnCompletion 方法，在其所有结果集都被关闭后，该语句会立即被自动关闭

##### SQL 异常

###### SQLException

每个 SQLException 都有一个由多个 SQLException 对象构成的链，这些对象可以通过 getNextException 方法获取。这个异常链式每个异常都具有的由  Throwable 对象构成的『成因』链之外的异常链。因此需要用两个嵌套的循环来完整枚举所有的异常。

Java 6 改进了 SQLException 类，让其实现了 `Iterable<Throwable>` 接口，其 iterator() 方法可以产生一个 `Iterator<Throwable>` ，这个迭代器可以迭代这两个链，首先迭代第一个 SQLException 的成因链，然后迭代下一个 `SQLException` ，以此类推。可以直接使用 for 循环

```java
for (Throwable t : sqlException) {
    // 产生符合X/Open或SQL:2003标准的字符串，调用DatabaseMetaData接口的getSQLStateType方法可以查出驱动程序所使用的标准
    System.out.print(t.getSQLState());
    // 错误代码是与具体的提供商相关的
    System.out.print(t.getErrorCode());
}
```

SQL 异常按照层次结构树的方式组织到一起。这使得可以按照与提供商无关的方式来捕获具体的错误类型。SQLException 异常是所有 SQL 异常类型的根类型

###### SQLWarning

数据库驱动程序可以将非致命问题作为警告报告，可以从连接，语句和结果集中获取这些警告。SQLWarning 类是 SQLException 的子类（SQLWarning 不会被当作异常抛出）。与 SQLException 类似，警告也是串成链的。要获得所有的警告，可以使用下面的循环

```java
SQLWarning w = stat.getWarning();
while (w != null) {
    w = w.nextWarning();
    // 获取警告信息
    System.out.print(w.getSQLState());
    // 获取警告代码.0
    System.out.print(w.getErrorCode());
}
```

当数据从数据库中读出并意外被截断时，SQLWarning 的 DataTruncation 子类就派上用户场了。如果数据截断发送在更新语句中，那么 DataTruncation 将会被当作异常抛出

##### 数据操作

###### 预处理语句

PreparedStatement，涉及用户输入的条件查询和多次执行同一语句时建议使用预处理语句

```java
// 在预备查询语句，每个变量都用 ? 来表示
String publishQuery=
	"Select Books.Price, Books.Title" +
	" FROM Books, Publishers" + 
	" WHERE Books.Publisher_ID = Publishers.Publisher_ID AND Publishers.Name = ?";
PreparedStatement stat = conn.prepareStatement(publisherQuery);
// 在预处理语句前，必须使用 set{type} 方法将变量绑定到实际的值上，？位置从 1 开始计数
stat.setString(1, publisher);
// 一旦为所有变量绑定了具体的值，就可以执行查询操作了
ResultSet rs = stat.executeQuery();
```

与 PreparedStatement 关联的 Connection 对象关闭之后，PreparedStatement 对象也就变得无效了

###### 读写 CLOB/BLOB 

在 SQL 中，二进制大对象称为 BLOB，字符型大对象称为 CLOB。

```java
// 获取一张图像
stat.set(1, isbn);
try (ResultSet result = stat.executeQuery()) {
    if (result.next()) {
        // 在 ResultSet 上调用 getBlob 或 getClob 方法，可以获得 Blob 或 Clob  类型的对象
        Blob coverBlob = result.getBlob(1);
        Clob clob = result.getClob(2);
        // 要从 Blob 中获取二进制数据，可以调用 getBytes 或 getBinaryStream
        Image coverImage = ImageIO.read(coverBlob.getBinaryStream());
        // 调用 getSubString 或 getCharacterStream 方法来获取其中的字符数据
        String clot2String = clob.getSubString(0);
    }
}
```

将对象插入数据库中

```java
// 需在Connection对象上 createBlob/createClob 获取一个用于该LOB的输出流或写出器，写出数据，并将该对象存储到数据库中
Blob coverBlob = connection.createBlob();
int offset = 0;
OutputStream out = coverBlob.setBinaryStream(offset);
ImageIO.write(coverImage, "PNG", out);
PreparedStatement stat = conn.prepareStatement("INSERT INTO Cover VALUES (?, ?)");
stat.set(1, isbn);
stat.set(2, coverBlob);
stat.executeUpdate();
```

##### 结果集

###### 多结果集

在执行存储过程，或者在使用允许在单个查询中提交多个 SELECT 语句的数据库时，一个查询有可能会返回多个结果集。获取所有结果集的步骤

1.使用 execute 方法来执行 SQL 语句

2.获取第一个结果集或更新计数

3.重复调用 getMoreResults 方法以移动到下一个结果集

4.当不存在更多的结果集或更新计数时，完成操作

如果由多结果集构成的链中的下一项是结果集，execute 和 getMoreResults 方法将返回 true ，而如果在链中的下一项不是更新计数，getUpdateCount 方法将返回 -1

```java
// 循环遍历所有的结果
boolean isResult = stat.execute(command);
boolean done = false;
while (!done) {
    if (isResult) {
        ResultSet result = stat.getResultSet();
    } else {
        int updateCount = stat.getUpdateCount();
        if (updateCount >= 0) {
            
        } else {
            done = true;
        }
    }
    if (!done) {
        isResult = stat.getMoreResults();
    }
}
```

###### 获取自动生成的键

当向数据表中插入一个新行，且其键自动生成时，可以用下面的代码来获取这个键

```java
// 如果 autogenerated 被设置为 Statement.RETURN_GENERATED_KEYS 并且该语句为 INSERT 语句，那么第一列就是自动生成的键
stat.executeUpdate(insertStatement, Statement.RETURN_GENERATED_KEYS);
ResultSet rs = stat.getGeneratedKeys();
if (rs.next()) {
    int key = rs.getInt(1);
}
```

###### 可滚动的结果集

默认情况下，结果集是不可滚动和不可更新的。为了从查询中获取可滚动的结果集（使用 DatabaseMetaData.supportsResultSetType 和 supportsResultSetConcurrency 方法，可以获知在使用特定的驱动程序时，某个数据库究竟支持哪些结果集类型以及哪些并发模式），必须使用下面的方法

```java
/**
 * 得到一个不同的 Statement 对象
 * @param ResultSet.Type 
 *        TYPE_FORWARD_ONLY           结果集不能滚动（默认值）
 *        TYPE_SCROLL_INSENSITIVE     结果集可以滚动，但对数据库变化不敏感 
 *        TYPE_SCROLL_SENSITIVE       结果集可以滚动，且对数据库变化敏感
 * @param ResultSet.Concurrency 
 *        CONCUR_READ_ONLY 结果集不能用于更新数据库（默认值）
 *        CONCUR_UPDATEBLE 结果集可以用于更新数据库
 */ 
Statement stat = conn.createStatement(ResultSet.Type, ResultSet.Concurrency);
PreparedStatement stat = conn.prepareStatement(command, type, concurrency);
// 只滚动遍历结果集，而不编辑它的数据
Statement stat = conn.createStatement(ResultSet.TYPE_SCROLL_INSENSITIVE, ResultSet.CONCUR_READ_ONLY);
// 获得的所有结果集都将是可滚动的
ResultSet rs = stat.executeQuery(query);
// 判断 JDBC 是否支持指定的结果集类型
boolean supportsResultSetType(int type) throws SQLException;
// 判断 JDBC 驱动是否支持于 type 所指定的结果集类型相结合的并发性类型
boolean supportsResultSetConcurrency(int type, int concurrency) throws SQLException;
// 游标是否位于第一行之前
boolean isBeforeFirst() throws SQLException;
// 游标是否位于最后一行之后
boolean isAfterLast() throws SQLException;
// 游标是否位于第一行
boolean isFirst() throws SQLException;
// 游标是否位于最后一行
boolean isLast() throws SQLException;
// 移动游标到结果集第一行之前
void beforeFirst() throws SQLException;
// 移动游标到结果集最后一行之后
void afterLast() throws SQLException;
// 移动游标到结果集第一行
boolean first() throws SQLException;
// 移动游标到结果集最后一行
boolean last() throws SQLException;
// 滚动结果集 n 为正，游标向前移动。如果 n 为负，游标将向后移。如果 n 为 0，那么调用该方法将不起任何作用。如果试图将游标移动到当前行集的范围之外，即根据 n 值的正负号，游标需要被设置在最后一行之后或第一行之前，那么，该方法将返回 false，且不移动游标，如果游标位于一个实际的行上，那么该方法将返回 true
boolean relative(int row) throws SQLException;
// 移动游标到结果集中指定的行。 row 可以是正（相对于结果集开始处移动 1 第一行，2第二行），也可以是负（结果集终点开始移动，-1 最后一行，2 倒数二行）。
boolean absolute(int row) throws SQLException;
// 返回当前行的行号
int currentRow = rs.getRow()
```

###### 可更新的结果集

可更新的结果集支持编辑结果集中的数据，并将结果集上的数据变更自动反映到数据库中。可更新的结果集并非必须是可滚动的，但如果将数据提供给用户去编辑，那么通常也会希望结果集是可滚动的。

```java
// 如果要获得可更新的结果集，应该使用以下方法创建一条语句，调用 executeQuery 方法返回的结果集就将是可更新的结果集
Statement stat = conn.createStatement(ResultSet.TYPE_SCROLL_INSENSITIVE, ResultSet.CONCUR_UPDATABLE);
```

并非所有的查询都会返回可更新的结果集：如果查询涉及多个表的连接操作，那么它所产生的结果集将是不可更新的；如果查询只涉及了一个表，或者在查询时是使用主键连接多个表的，那么它所产生的结果集将是可更新的结果集；查询操作的表中必须有主键，而且在查询的结果集中必须包含作为主键的字段。可调用 ResultSet.getConcurrency 方法来确定结果集是否是可更新的

* 更新一行

  所有对应于 SQL 类型的数据类型都配有 `updateXxx` 方法，比如 `updateDouble`、`updateString` 等。与 `getXxx` 方法相同，在使用 `updateXxx` 方法时必须指定列的名称或序号（列序号指的是该列在结果集中的序号，它的值可以与数据库中的列序号不同）。

  `updateXxx` 方法改变的只是结果集中的行值，而非数据库中的值。当更新完行的字段值后，必须调用 `updateRow` 方法，这个方法将当前行中的所有更新信息发送给数据库。如果没有调用 `updateRow` 方法就将游标移动到其他行上，那么对此行所做的所有更新都将被丢弃，而且永远不会被传递给数据库。还可以调用 `cancelRowUpdates` 方法来取消对当前行的更新（使该方法有效，必须在调用 updateRow 方法之前调用它）。可以调用 rowUpdate 方法判断当前行是否被更新

* 插入一行

  首先需要使用 `moveToInsertRow` 方法将游标移动到插入行（插入行是一个与可更新的结果集相联系的特殊的缓存行）。调用 `updateXxx` 方法设置行中的数据（必须指定所有不能为空的列和没有默认值的列），行中数据设置完毕后，调用 `insertRow` 方法将新建的行发送给数据库。完成插入操作后，再调用 `moveToCurrentRow` 方法将游标移回到调用 `moveToInsertRow` 方法之前的位置

  ```java
  rs.moveToInsertRow();
  rs.updateString("title", "title");
  rs.updateString("ISBN", isbn);
  rs.insertRow();
  rs.moveToCurrentRow();
  ```

  对于在插入行中没有指定值的列，将被设置为 SQL 的 NULL。但是，如果这个列有 `NOT NULL` 约束，那么将会抛出异常，而这一行也无法插入。当游标在插入行时，只有 updateXxx、getXxx、insertRow 方法可以被调用，在插入行上调用 getXxx 之前必须先调用 updateXxx，可以调用 rowInserted 方法来判断当前行是否是插入行

* 删除一行

  删除游标所指的行 `rs.deleteRow()` ，当游标指向插入行时，不能调用这个方法。一个被删除的行可能在结果集中留下一个空的位置，可以调用 rowDeleted 方法来判断一行是否被删除。

##### 行集

可滚动的结果集虽然功能强大，却有一个重要的缺陷：在用户的整个交互过程中，必须始终与数据库保持连接。这种方式存在很大的问题，因为数据库连接属于稀有资源。行集`RowSet` 接口扩展自  `ResultSet` 接口，却无需始终保持与数据库的连接。

javax.sql.RowSet 包提供的接口，都扩展了 RowSet 接口

* `CacheRowSet` 允许在断开连接的状态下执行相关操作。
* `WebRowSet` 对象代表了一个被缓存的行集，该行集可以保存为 XML 文件。该文件可以移动到 `web` 应用的其他层中，只要该该层中使用另一个 `WebRowSet` 对象重新打开该文件即可
* `FiltereRowSet` 和 `JoinRowSet` 接口支持对行集的轻量级操作，它们等同于 SQL 中的 SELECT 和 JOIN 操作。这两个接口的操作对象是存储在行集中的数据，因此运行时无需建立数据库连接
* `JdbcRowSet` 是  `ResultSet` 接口的一个包装器。在  `RowSet` 接口中添加了有用的方法

###### 被缓存的行集

一个被缓存的行集中包含了一个结果集中所有的数据。`CachedRowSet` 是 `ResultSet` 接口的子接口，所以完全可以像使用结果集一样来使用被缓存的行集。被缓存的行集有一个非常重要的优点：断开数据库连接后仍然可以使用行集。

```java
ResultSet result = ....;
RowSetFactory factory = RowSetProvider.newFactory();
CachedRowSet crs = factory.createCachedRowSet();
// 可以使用一个结果集来填充 CachedRowSet` 对象
crs.populate(result);
conn.close();
```

让 `CachedRowSet` 对象自动建立一个数据库连接

```java
// 设置数据库参数
crs.setURL("jdbc:derby://localhost:1527/COREJAVA");
crs.setUsername("dbuser");
crs.setPassword("secret");
// 设置查询语句和所有参数
crs.setCommand("SELECT * FROM Books WHERE Publisher_ID = ?");
crs.setString(1, publisherId);
// 将查询结果填充到行集中
crs.execute();
```

以上会建立数据库连接、执行查询操作、填充行集、最后断开连接

如果查询结果非常大，可以指定每一页的尺寸

```java
CachedRowSet crs = ...;
crs.setCommand(command);
crs.setPageSize(20);
crs.execute();
```

现在就只能获得 20 行了。要获取下一批数据，调用

```java
crs.nextPage();
```

可以使用与结果集中相同的方法来查看和修改集中的数据。如果修改了行集中的内容，那么必须调用以下方法将修改写回到数据库中：

```java
crs.acceptChanges(conn);
crs.acceptChanges();
```

只有在行集中设置了连接数据库所需的信息时，上述第二个方法调用才会有效。如果是使用结果集来填充行集，那么行集就无从获知需要更新数据的数据库表名。此时必须调用 `setTable` 方法来设置表名称

另一个导致问题复杂化的情况是：在填充了行集之后，数据库中的数据发生了改变，这显然容易造成数据不一致性。为了解决这个问题，参考实现会首先检查行集中的原始值（即，修改前的值）是否与数据库中的当前值一致。如果一致，那么修改后的值将覆盖数据库中的当前值。否则将抛出 `SyncProviderException` 异常，且不向数据库写回任何值。在实现行集接口时其他实现也可以采用不同的同步策略

##### 元数据

JDBC 还可以提供关于数据库及其表结构的详细信息。在 SQL 中，描述数据库或其组成部分的数据成为元数据。可以获得三类元数据：关于数据库的元数据、关于结果集的元数据以及关于预备语句参数的元数据

###### DatabaseMeta

可以从数据库连接中获取一个 `Connection.getMetatData` 对象。

```java
DatabaseMetaData meta = conn.getMetaData();
// 获取元数据,返回包含所有数据库表信息的结果集
DatabaseMetaData meta = conn.getMetaData();
```

该结果集中的每一行都包含了数据库中一张表的详细信息，其中，第三列是表的名称。下面的循环可以获取所有表名

```java
while (mrs.next()) {
    tableNames.addItem(mrs.getString(3));
}
```

###### ResultSetMetaData

用于提供结果集（`ResultSet.getMeataData`）的相关信息。每当通过查询得到一个结果集时，可以获得该结果集的行数以及每一列的名称、类型和字段宽度

```java
// 返回结果集中列的数量
int getColumnCount() throws SQLException;
// 返回列的名字
String getColumnName(int column) throws SQLException;
// 返回列的最大字符宽度
int getColumnDisplaySize(int column) throws SQLException;
// 返回列的 SQL 类型（JDBC 类型，在 java.sql.Types 类中定义）
int getColumnType(int column) throws SQLException;
// 返回列的数据库特定的类型名
String getColumnTypeName(int column) throws SQLException;
// 返回列所属的表名
String getTableName(int column) throws SQLException;
```

```java
ResultSet rs = stat.executeQuery("SELECT * FROM " + tablename);
ResultSetMetaData meta = rs.getMetaData();
for (int i = 1; i <= meta.getColumnCount(); i++) {
    String columnName = meta.getColumnLabel(i);
    int columnWidth = meta.getColumnDisplaySize(i);
}
```

###### ParameterMetaData

得到 PreparedStatement 对象中的参数的类型和属性信息，通过 `PreparedStatement.getParameterMetaData` 方法获取

##### 事务

###### 事务操作流程

在 Connection 接口中提供了控制事务的方法，在 JDBC API 中，默认情况下为自动提交事务。每个 SQL 语句一旦被执行便被提交给数据库。一旦命令被提交，就无法对它进行回滚操作。在使用事务时，需要关闭这个默认值

```java
// 关闭自动提交
conn.setAutoCommit(false);
// 设置事务的隔离级别
conn.setTransactionIsolation(conn.TRANSACTION_REPEATEBLE_READ); 
// 现在可以使用通常的方法创建一个语句对象
Statement stat = conn.createStatement();
// 然后多次调用 executeUpdate
try {
	stat.executeUpdate(command);
	stat.executeUpdate(command);
	stat.executeUpdate(command);
    // 如果执行了所有命令之后没有出错，则调用 commit 方法
	conn.commit();
} catch (SQLException e) {
    // 如果出现错误，则调用
	conn.rollback();
}
```

###### 保存点

在使用某些驱动程序时，使用保存点可以更细粒度的控制回滚操作。创建一个保存点意味着稍后只需返回这个点，而非事务的开头

```java
Statement stat = conn.createStatement();
stat.executeUpdate(command);
Savepoint svpt = conn.setSavepoint();
try {
	stat.executeUpdate(command);
} catch (SQLException e) {
    conn.rollback(svpt);
}
conn.commit();
// 当不再需要保存点时，必须释放它
conn.releaseSavepoint(svpt);
```

##### 批量更新

在使用批量更新时，一个语句序列作为一批操作将同时被收集和提交。使用 `DatabaseMetaData` 接口中的 `supportsBatchUpdates` 方法可以获知数据库是否支持这种特性

处于同一批中的语句可以是 `INSERT` 、`UPDATE`、`DELETE` 等操作，也可以是数据库定义语句，如 `CREATE TABLE` 和 `DROP TABLE`。但是，在批处理中添加 `SELECT` 语句会抛出异常

```java
// 执行批处理，首先创建一个 `Statment` 对象
Statement stat = conn.createStatement();
// 调用 addBatch 方法
String command = "CREAT TABLE ..."
stat.addBatch(command);
while(...) {
    command = "INSERT INTO ...VALUES ("+...+")";
    stat.addBatch(command);
}
// 提交整个批量更新语句
int[] counts = stat.executeBatch();
```

调用 `executeBatch` 方法将为所有已提交的语句返回一个记录数的数组。为了在批量模式下正确的处理错误，必须将批量执行的操作视为单个事务。如果批量更新在执行过程中失败，那么必须将它回滚到批量操作开始之前的状态

##### JDBC 数据源和连接池

在 javax.sql 中，定义了 DataSource 接口，由驱动供应商来实现，利用 DataSource 建立数据库连接，不需要在客户程序中加载 JDBC 驱动和使用 DriverManager 类。在程序中，通过向一个 JNDI 服务器查询得到 DataSource 对象，调用 DataSource.getConnection 方法来建立数据库连接，DataSource 对象可以看成是连接工厂，用于提供 DataSource 对象所表示的物理数据源的连接

```java
javax.naming.Context ctx = new javax.naming.InitialContext();
javax.sql.DataSource ds = (javax.sql.DataSource)ctx.lookup("java:comp/env/jdbc/bookstore");
java.sql.Connection conn = ds.getConnection();
```

###### JNDI 名称空间

JNDI 名称空间由一个初始的命名上下文及其下的任意数目的子上下文组成。JNDI 名称空间是分层次的类似文件系统的目录文件结构，初始上下文类似文件系统根，子上下文与子目录类似。jdbc 子上下文保留给 JDBC 数据源使用。逻辑数据源的名字可以在子上下文 jdbc 中，也可以在 jdbc 下的子上下文中，层次的最后一级元素是注册的对象（类似文件）

* `java:comp/env` 是环境命名上下文，解决 JNDI 命名冲突问题，将资源引用名和实际的 JNDI 名相分离，提供移植性

Tomcat 提供了数据源和连接池的实现（使用开源的DBCP连接池实现），在 Tomcat 中，可以在 <Context> 元素的内容中使用 <Resource> 元素来配置 JDBC 数据源。使用 Tomcat 提供的数据源实现来访问数据库，需要将 JDBC 驱动放到 tomcat 的 lib 目录下，是 Tomcat 需要 JDBC 驱动，而非应用程序需要 JDBC 驱动

###### DataSource

javax.sql.DataSource 接口有以下实现：

* 基本实现：产生一个标准的连接对象，与调用 DriverManager.getConnection 方法得到的连接一样，是一个到数据库的物理连接
* 连接池实现：产生一个自动参与到连接池中的连接对象，这种实现需要和一个中间层连接池管理器一起工作
* 分布式事务实现：产生一个用于分布式事务的连接对象，这种连接对象总是参与到连接池中，需要和中间层事务管理器和连接池一起工作

#### mysql 实现

##### 连接器

建议 MySQL Server 5.6 版本之后，客户端使用 Connector/J 8，Connector/J 不支持使用 Unix 域连接到 MySQL 服务器（可以使用实现 Connector/J 的 *com.mysql.cj.protocol.protocol.SocketFactory* 或 *com.mysql.jdbc.SocketFactory* 接口的第三方库）。

Connector/J 5 版本的驱动类为 *com.mysql.jdbc.Driver*，Connector/J 8 版本的驱动类为 *com.mysql.cj.jdbc.Driver*

###### 连接 URL

```
protocol//[hosts][/database][?properties]
```

对于 URL 的保留字必须转义

* protocol

    *支持的连接协议*

    |             协议             |                   描述                    |
    | :--------------------------: | :---------------------------------------: |
    |         `jdbc:mysql`         |          普通和基本的 jdbc 连接           |
    |   `jdbc:mysql:loadbalance`   |         用于负载均衡的 jdbc 连接          |
    |   `jdbc:mysql:replication`   |           用于 jdbc 的复制连接            |
    |           `mysqlx`           |            用于 X DevAPI 连接             |
    |       `jdbc:mysql+srv`       | 用于使用 DNS SRV 记录的负载均衡 jdbc 连接 |
    | `jdbc:mysql+srv:loadbalance` | 用于使用 DNS SRV 记录的负载均衡 jdbc 连接 |
    | `jdbc:mysql+srv:replication` |   用于使用 DNS SRV 记录的复制 jdbc 连接   |
    |         `mysqlx+srv`         |   用于使用 DNS SRV 记录的 X DevAPI 连接   |

* hosts

    hosts 部分可以仅由主机名，也可以是由各种元素组成的复杂结构（主机名、端口号、特定主机的属性和用户凭据），host 可以是 IPv4 或 IPv6（使用 IPv6 时，必须使用方括号包裹）地址或主机名字符串，默认 localhost，port 默认 3306，X 协议默认 33060，未指定时，使用默认值

    *单主机*

    ```
    # 单主机添加特定的属性
    address=(host=host_or_ip)(port=port)(key1=value1)(key2=value2)...(keyN=valueN)
    jdbc:mysql://address=(host=myhost)(port=1111)(key1=value1)/db
    (host=host,port=port,key1=value1,key2=value2,...,keyN=valueN)
    jdbc:mysql://(host=myhost,port=1111,key1=value1)/db
    # 单主机用户凭证
    user:password@host_or_host_sublist
    mysqlx://sandy:secret@[(address=host1:1111,priority=1,key1=value1),(address=host2:2222,priority=2,key2=value2))]/db
    # 使用密钥 user 并 password 为每个主机指定凭据，当指定多个用户凭据时，即在连接字符串中从左向右移动，发现适用于主机的第一个凭据时所使用的凭据
    (user=sandy)(password=mypass)
    jdbc:mysql://[(host=myhost1,port=1111,user=sandy,password=secret),(host=myhost2,port=2222,user=finn,password=secret)]/db
    jdbc:mysql://address=(host=myhost1)(port=1111)(user=sandy)(password=secret),address=(host=myhost2)(port=2222)(user=finn)(password=secret)/db
    ```

    *多主机*

    ```
    # 以逗号分隔列表列出主机
    host1,host2,...,hostN
    jdbc:mysql://myhost1:1111,myhost2:2222/db
    jdbc:mysql://address=(host=myhost1)(port=1111)(key1=value1),address=(host=myhost2)(port=2222)(key2=value2)/db
    jdbc:mysql://(host=myhost1,port=1111,key1=value1),(host=myhost2,port=2222,key2=value2)/db
    jdbc:mysql://myhost1:1111,(host=myhost2,port=2222,key2=value2)/db
    mysqlx://(address=host1:1111,priority=1,key1=value1),(address=host2:2222,priority=2,key2=value2)/db
    # 用逗号分隔列表列出主机，然后用方括号将列表括起来
    [host1,host2,...,hostN]
    jdbc:mysql://sandy:secret@[myhost1:1111,myhost2:2222]/db
    jdbc:mysql://sandy:secret@[address=(host=myhost1)(port=1111)(key1=value1),address=(host=myhost2)(port=2222)(key2=value2)]/db
    jdbc:mysql://sandy:secret@[myhost1:1111,address=(host=myhost2)(port=2222)(key2=value2)]/db
    ```

* database

    要打开的默认数据库或目录。如果未指定数据库，则在没有默认数据库的情况下进行连接。在这种情况下，应该连接实例上调用 setCatalog()，或者 SQL 语句中使用数据库名表名。

    ```
    # 始终使用方法来指定所需数据库，而不是使用语句 use database
    Connection.setCatalog()
    ```

* properties

    适用于所有主机的一系列全局属性，以符号 ？开头的 _key=value_ 以 & 分隔

    ```
    jdbc:mysql://(host=myhost1,port=1111),(host=myhost2,port=2222)/db?key1=value1&key2=value2&key3=value3
    ```

###### 配置属性

配置属性定义 Connector/J 如何与 MySQL 服务器建立连接。可以为一个 DataSource 对象或一个 Connection 对象设置属性，设置配置属性

* 在 MySQL 实现（*java.sql.DataSource*）调用 set*() 方法

    ```java
    com.mysql.cj.jdbc.MysqlDataSource
    com.mysql.cj.jdbc.MysqlConnectionPoolDataSource
    ```

* 作为 java.util.Properties 实例中的健值对传递给 DriverManager.getConnection 或 Driver.connect()

* 作为给定的 URL 的 JDBC URL 参数指定配置属性（如果在 URL 中指定属性而未提供值，则不会设置任何内容，如果用于配置 JDBC URL 的机制是基于 XML 的，须使用 XML 字符文字 &amp 来分隔配置参数，& 是 XML 的保留字符

##### JDBC API 实现

###### BLOB

通过将属性 `emulateLocators=true` 添加到 JDBC URL，可以使用定位器来模拟 BLOB。将延迟加载 BLOB 数据直至在 BLOB 数据流上使用检索方法（getInputStream()，getBytes() 等）

表必须有主键，还必须为 BLOB 列指定别名，且 select 必须覆盖主键与别名

```sql
SELECT id, 'data' as blob_data from blobtable
```

BLOB 实现不允许就地修改，须使用 PreparedStatement.setBlob() 或 ResultSet.updateBlob() 在（可更新的结果集的情况下）方法将更改保存回数据库

###### Connection

isClosed() 方法不对服务器执行 ping 操作以确定服务器是否可用。根据 JDBC 规范，只有 closed() 在连接上被调用时，它才返回 true。如果需要确定连接是否仍然有效，可以使用一个简单查询如 select 1，如果连接不再有效，驱动程序将引发异常

###### DatabaseMetaData

外键信息仅在 InnoDB 表中可用。驱动程序用于 `SHOW CREATE TABLE` 检索此信息

###### Statement

Connector/J 同时支持 Statement.cancel() 和 Statement.setQueryTimeout()，两者都需要单独的连接才能发出 `KILL QUERY` 语句。对于 `setQueryTimeout()` 实现将创建一个附加线程来处理超时功能。

未能取消 `setQueryTimeout()` 的语句的失败可能会显示为 RuntimeException 而不是默默地失败，因为当前无法取消阻塞正在执行的线程（由于超时到期而被取消）的执行，而是抛出异常。

MySQL 不支持 SQL 游标，并且 JDBC 驱动程序不模拟它们，`setCursorName()` 无效。

`setLocalInfileInputStream()` 设置一个 `InputStream` 实例，该实例将用于将数据发送到 MySQL 服务器以获取一条 `LOAD DATA LOCAL INFILE` 语句，它代表作为该语句的参数给出的路径。该流将在执行一条[`LOAD DATA LOCAL INFILE`](https://dev.mysql.com/doc/refman/8.0/en/load-data.html)语句后读取到完成状态，并且将被驱动程序自动关闭，因此需要在每次调用之前将其重置`execute*()`，以使MySQL 服务器请求数据来满足的要求 [`LOAD DATA LOCAL INFILE`](https://dev.mysql.com/doc/refman/8.0/en/load-data.html)。

如果将此值设置为`NULL`，则驱动程序将根据需要使用 `FileInputStream` 或还原为 `URLInputStream`。

`getLocalInfileInputStream()` 返回 `InputStream` 将用于发送数据以响应 `LOAD DATA LOCAL INFILE` 语句的实例。如果未设置此流，则返回 null

###### PreparedStatement

Connector/J 实现了预处理语句的两种变体：

* 默认使用客户端的预处理语句，
* 要启用服务器预处理语句，设置 `useServerPrepStmts=true` 属性

在服务器端/客户端准备好后，数据仅在 PreparedStatement.execute() 被调用时才交换。完成此操作后，将关闭用于读取客户端数据的流（根据 JDBC 规范），并且无法再次读取该流

###### ResultSet

默认情况下，完全检索结果集并将其存储在内存中，如果正在使用具有大量行或较大值的 ResultSet，并且无法在 JVM 中为所需的内存分配堆空间，则可以让驱动程序一次将结果流回一行，启用此功能：

```java
stmt = conn.createStatement(java.sql.ResultSet.TYPE_FORWARD_ONLY, java.sql.ResultSet.CONCUR_READ_ONLY);
stmt.setFetchSize(Integer.MIN_VALUE);
```

还可以使用基于游标的流每次检索固定数量的行。使用 JDBC 连接 URL 属性 `useCursorFetch=true`，然后调用 `setFetchSize(int)` ：

```java
conn = DriverManager.getConnection("jdbc:mysql://localhost/?useCursorFetch=true", "user", "secret");
stmt = conn.createStatement();
stmt.setFetchSize(100);
rs = stmt.executeQuery("SELECT * FROM table_name"); 
```

##### 数据类型

Connector/J 会发出 JDBC 规范要求的警告或抛出 DataTruncation 异常。除非设置连接属性 `jdbcCompliantTruncatio=false`

* 通常任何 MySQL 数据类型都可以转换为 *java.lang.String*

* 任何数值类型都可以转换为 java 数值类型中的任何一种，可能会发生舍入，溢出或精度损失

* 所有 TEXT 类型为 `Types.LONGVARCHAR`，对应 `getPrecision()` 值为 65535，255，16777215，2147483647，`getColumnType()` 返回 -1，即使 `getColumnType()` 返回 `Types.LONGVARCHAR`，`getColumnTypeName()` 也会返回 `VARCHAR`，`VARCHAR` 是此类型的指定列的数据库特定名称

###### mysql 类型始终可以转换的 Java 类型

如果要转换的数据类型容量不一致，可能导致溢出或精度损失

|                    These MySQL Data Types                    |         Can always be converted to these Java types          |
| :----------------------------------------------------------: | :----------------------------------------------------------: |
|          `CHAR, VARCHAR, BLOB, TEXT, ENUM, and SET`          | `java.lang.String, java.io.InputStream, java.io.Reader, java.sql.Blob, java.sql.Clob` |
| `FLOAT, REAL, DOUBLE PRECISION, NUMERIC, DECIMAL, TINYINT, SMALLINT, MEDIUMINT, INTEGER, BIGINT` | `java.lang.String, java.lang.Short, java.lang.Integer, java.lang.Long, java.lang.Double, java.math.BigDecimal` |
|              `DATE, TIME, DATETIME, TIMESTAMP`               |    `java.lang.String, java.sql.Date, java.sql.Timestamp`     |

###### mysql 类型与 jdbc 类型及 java 类型

*MySQL Types and Return Values for ResultSetMetaData.GetColumnTypeName()and ResultSetMetaData.GetColumnClassName()*

|        MySQL Type Name         | Return value of `GetColumnTypeName` |             Return value of `GetColumnClassName`             |
| :----------------------------: | :---------------------------------: | :----------------------------------------------------------: |
|  `BIT(1)` (new in MySQL-5.0)   |                `BIT`                |                     `java.lang.Boolean`                      |
| `BIT( > 1)` (new in MySQL-5.0) |                `BIT`                |                           `byte[]`                           |
|           `TINYINT`            |              `TINYINT`              | `java.lang.Boolean` if the configuration property `tinyInt1isBit` is set to `true` (the default) and the storage size is 1, or `java.lang.Integer` if not. |
|       `BOOL`, `BOOLEAN`        |              `TINYINT`              | See `TINYINT`, above as these are aliases for `TINYINT(1)`, currently. |
|   `SMALLINT[(M)] [UNSIGNED]`   |        `SMALLINT [UNSIGNED]`        | `java.lang.Integer` (regardless of whether it is `UNSIGNED` or not) |
|  `MEDIUMINT[(M)] [UNSIGNED]`   |       `MEDIUMINT [UNSIGNED]`        | `java.lang.Integer` (regardless of whether it is `UNSIGNED` or not) |
| `INT,INTEGER[(M)] [UNSIGNED]`  |        `INTEGER [UNSIGNED]`         |     `java.lang.Integer`, if `UNSIGNED` `java.lang.Long`      |
|    `BIGINT[(M)] [UNSIGNED]`    |         `BIGINT [UNSIGNED]`         |     `java.lang.Long`, if UNSIGNED `java.math.BigInteger`     |
|         `FLOAT[(M,D)]`         |               `FLOAT`               |                      `java.lang.Float`                       |
|        `DOUBLE[(M,B)]`         |              `DOUBLE`               |                      `java.lang.Double`                      |
|       `DECIMAL[(M[,D])]`       |              `DECIMAL`              |                    `java.math.BigDecimal`                    |
|             `DATE`             |               `DATE`                |                       `java.sql.Date`                        |
|           `DATETIME`           |             `DATETIME`              |                     `java.sql.Timestamp`                     |
|        `TIMESTAMP[(M)]`        |             `TIMESTAMP`             |                     `java.sql.Timestamp`                     |
|             `TIME`             |               `TIME`                |                       `java.sql.Time`                        |
|         `YEAR[(2|4)]`          |               `YEAR`                | If `yearIsDateType` configuration property is set to `false`, then the returned object type is `java.sql.Short`. If set to `true` (the default), then the returned object is of type `java.sql.Date` with the date set to January 1st, at midnight. |
|           `CHAR(M)`            |               `CHAR`                | `java.lang.String` (unless the character set for the column is `BINARY`, then `byte[]` is returned. |
|     `VARCHAR(M) [BINARY]`      |              `VARCHAR`              | `java.lang.String` (unless the character set for the column is `BINARY`, then `byte[]` is returned. |
|          `BINARY(M)`           |              `BINARY`               |                           `byte[]`                           |
|         `VARBINARY(M)`         |             `VARBINARY`             |                           `byte[]`                           |
|           `TINYBLOB`           |             `TINYBLOB`              |                           `byte[]`                           |
|           `TINYTEXT`           |              `VARCHAR`              |                      `java.lang.String`                      |
|             `BLOB`             |               `BLOB`                |                           `byte[]`                           |
|             `TEXT`             |              `VARCHAR`              |                      `java.lang.String`                      |
|          `MEDIUMBLOB`          |            `MEDIUMBLOB`             |                           `byte[]`                           |
|          `MEDIUMTEXT`          |              `VARCHAR`              |                      `java.lang.String`                      |
|           `LONGBLOB`           |             `LONGBLOB`              |                           `byte[]`                           |
|           `LONGTEXT`           |              `VARCHAR`              |                      `java.lang.String`                      |
| `ENUM('value1','value2',...)`  |               `CHAR`                |                      `java.lang.String`                      |
|  `SET('value1','value2',...)`  |               `CHAR`                |                      `java.lang.String`                      |

##### 字符集

从 JDBC 驱动程序发送到服务器的所有字符串都是从 java 本地的 Unicode 格式自动转换为客户端字符编码，包括 ：`Statement.execute()`、`Statement.executeUpdate()`、`Statement.executeQuery()`，以及所有的 `PreparedStatement` 和 `CallableStatement` 参数（但不包包括 `setBytes()`、`setBinaryStream()`、`setAsciiStream()`、`setUnicodeStream()`、`setBlob()`）

Connector/J 支持客户端和服务器之间的单一字符编码，以及在 ResultSets 中服务器返回给客户端的数据的任意数量字符编码

当未设置连接属性 `characterEncoding` 和 `connectionCollation` 时，会自动检测客户端和服务器之间的字符编码。驱动程序自动使用服务器指定的编码，如果要使用 4 字节的 UTF-8 字符集，只需在 MySQL 服务端设置系统变量 `character_set_server=utf8mb4`，且不设置 `characterEncoding` 和 `connectionCollation`，Connector/J 将自动检测并设置为 UTF-8

###### mysql 字符集对应 java 字符编码

|                   MySQL Character Set Name                   | Java-Style Character Encoding Name |
| :----------------------------------------------------------: | :--------------------------------: |
|                           `ascii`                            |             `US-ASCII`             |
|                            `big5`                            |               `Big5`               |
|                            `gbk`                             |               `GBK`                |
|                            `sjis`                            |          `SJIS or Cp932`           |
|                           `cp932`                            |          `Cp932 or MS932`          |
|                           `gb2312`                           |              `EUC_CN`              |
|                            `ujis`                            |              `EUC_JP`              |
|                           `euckr`                            |              `EUC_KR`              |
|                           `latin1`                           |              `Cp1252`              |
|                           `latin2`                           |            `ISO8859_2`             |
|                           `greek`                            |            `ISO8859_7`             |
|                           `hebrew`                           |            `ISO8859_8`             |
|                           `cp866`                            |              `Cp866`               |
|                           `tis620`                           |              `TIS620`              |
|                           `cp1250`                           |              `Cp1250`              |
|                           `cp1251`                           |              `Cp1251`              |
|                           `cp1257`                           |              `Cp1257`              |
|                          `macroman`                          |             `MacRoman`             |
|                           `macce`                            |         `MacCentralEurope`         |
| *For 8.0.12 and earlier*: `utf8`*For 8.0.13 and later*: `utf8mb4` |              `UTF-8`               |
|                            `ucs2`                            |            `UnicodeBig`            |

对于 Connector/J 8.0.12 及之前版本，为了使用 utf8mb4 字符集连接，服务器必须配置为 `character_set_server=utf8mb4`，如果未在服务器配置，仅在连接 URL 字符串中指定 `characterEncoding=UTF-8`，则将映射为 MySQL 字符集 `utf8`（它是`utf8mb3`的别名）

对于 Connector/J 8.0.13 及更高版本：当连接字符串配置 `characterEncoding=UTF-8` 时，映射到 MySQL 的字符集为 `utf8mb4`；如果连接选项 `connectionCollation` 也与 `characterEncoding` 一起设置并且与之不兼容，则 `characterEncoding` 将被与 `connectionCollation` 对应的字符编码覆盖（使用 `show collation` 查看对应编码）；没有 `utf8mb3` 的 Java 样式字符集名称可与连接选项 `charaterEncoding` 一起使用，所以设置 `utf8mb3` 的唯一方法是设置连接属性 `connectionCollation=utf8_general_ci`

不要使用 Connector/J 查询 `set names`，因为驱动程序将不会检测到查询已更改了字符集，并且将继续使用首次建立连接时配置的字符集

##### 使用 connector/J

###### 存储过程

1. 通过使用准备可调用语句 Connection.prepareCall()，必须使用 JDBC 转义语法，并且参数占位符周围的括号不是可选的

    ```java
    import java.sql.CallableStatement;
    CallableStatement cStmt = conn.prepareCall("{call demoSp(?, ?)}");
    cStmt.setString(1, "paramter")
    ```

2. 注册输出参数（如果存在）

    要检索输出参数的值（在创建存储过程时 out 或 inout 在创建存储过程时指定的参数），在执行语句之前注册参数 `registerOutputParameter()`

    ```java
    import java.sql.Types;
    // 第二个参数。类型为 INTEGER
    cStmt.registerOutParameter(2, Types.INTEGER);
    // 指定参数名为 inOutParam, 类型为 Types.INTEGER
    cStmt.registerOutParameter("inOutParam", Types.INTEGER);
    ```

3. 设置输入参数（如果存在）

    ```java
    // 根据索引设置参数
    cStmt.setString(1, "abcs");
    // 根据参数名称
    cStmt.setString("inputParam", "abcs");
    // 根据索引设置 INOUT 参数
    cStmt.setInt(2, 1);
    // 根据名称设置 INOUT 参数
    cStmt.setInt("inOutParam", 1);
    ```

4. 执行，检索结果集

    CallableStatement 支持任何 Statement 执行方法

    ```java
    boolean hadResults = cStmt.execute();
    while (hadResults) {
        ResultSet rs = cStmt.getResultSet();
        //process result set
        ...
        hadResults = cStmt.getMoreResults();
    }
    int outputValue = cStmt.getInt(2); // index-based
    outputValue = cStmt.getInt("inOutParam"); // name-based
    ```

###### 请求查询

```java
public void connection() {
    try {
    	Class.forName("com.mysql.cj.jdbc.Driver");
   		conn = DriverManager.getConnection("jdbc://mysql://localhost/test?", "user", "password");
	} catch (ClassNotFoundException | SQLException e) {
    
	}
}
public void update() {}
	preparedStatement = connection.prepareStatement("update books set sale_amount = sale_amount + ? where id = ?");
    preparedStatement.setInt(1, quantity);
	preparedStatement.setString(2, bookId);
    preparedStatement.executeUpdate();
}
// resultsets
public void select() {
 	PreparedStatement preparedStatement = connection.prepareStatement("select * from books");
    ResultSet resultSet = preparedStatement.executeQuery()) {
    while (resultSet.next()) {
        BookDetails bookDetails1 = new BookDetails.Builder(resultSet.getString("id"))
            .author(resultSet.getString("author"))
            .title(resultSet.getString("title"))
            .price(resultSet.getFloat("price"))
            .online(resultSet.getInt("online"))
            .description(resultSet.getString("description"))
            .saleAmount(resultSet.getInt("sale_amount"))
            .build();
        bookDetails.add(bookDetails1);
    }
}
```

