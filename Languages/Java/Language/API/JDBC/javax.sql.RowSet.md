*javax.sql.RowSet*
```java
String getURL();

void  setURL(String url);

// 获取和设置数据库的 URL

String getUsername();

void setUsername(String username);

// 获取或设置连接数据库所需的密码

String getCommand();

void setCommand(String command);

// 获取或设置向行集中填充数据时需要执行的命令

void execute();

// 通过执行使用 `setCommand` 方法设置的语句集来填充行集。为了使驱动器可以获得连接，必须事先设定 `URL`，用户名和密码

```