### DataSource

#### DataSource 概述

JDBC API 提供了 `javax.sql.DataSource` 接口，它负责建立与数据库的连接，在应用程序中访问数据库时不必编写连接数据库的代码，可以直接从数据源获得数据库连接

* 数据源和数据库连接池

  在数据源中事先建立多个数据库连接，这些数据库连接保存在连接池（Connection Pool）中。Java 程序访问数据库时，只需从连接池中取出空闲状态的数据库连接；当程序访问数据库结束，再将数据库连接访问连接池，这样可以提供访问数据库的效率

* 数据源和 JNDI 资源

  `DataSource` 对象通常是由 Servlet 容器提供的，因此 Java 程序无需自行创建 `DataSource` 对象，只要直接使用 Servlet 容器提供的 `DataSource` 对象即可。Java 程序依赖于 JNDI 从 Servlet 容器获取提供的 `DataSource` 对象。

  Java Naming and Directory Interface 可以简单立即为一种将对象和名字绑定的技术，对象工厂负责生产出对象，这些对象都和唯一的名字绑定。外部程序可以通过名字来获得某个对象的引用

  `javax.naming.Context` 接口提供了将对象和名字绑定（`bind(String name, Object object)`），以及通过名字来检索对象的方法（`lookup(String name)`）。

#### tomcat 配置数据源

为 web 应用配置数据源。在 context.xml 文件中加入定义数据源的 `<Resource>` 元素；在 `web.xml` 中加入 `<resource-ref>` 元素，该元素声明 web 应用引用了特定数据源

*context.xml*

```xml
<Context reloadable="true">
	<Resource name="jdbc/bookDB" auth="Container" type="javax.sql.DataSource"
              maxActive="100" maxIdle="30" maxWait="10000"
              username="root" password="secret"
              driverClassName="com.mysql.cj.jdbc.Driver"
              url="jdbc:mysql://mysql8:3306/web"/>
</Context>
```

*web.xml*

```xml
<?xml version="1.0" encoding="UTF-8"?>

<web-app xmlns="http://xmlns.jcp.org/xml/ns/javaee"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://xmlns.jcp.org/xml/ns/javaee
                      http://xmlns.jcp.org/xml/ns/javaee/web-app_4_0.xsd"
  version="4.0" >

  <resource-ref>
    <description>DB Connection</description>
    <res-ref-name>jdbc/bookDB</res-ref-name>
    <res-type>javax.sql.DataSource</res-type>
    <res-auth>Container</res-auth>
  </resource-ref>

</web-app>
```

#### 应用程序访问

`javax.naming.Context` 提供了查找 JNDI 资源的接口

```java
// 获取数据源引用
Context ctx = new InitialContext();
DataSource ds = (DataSource) ctx.lookup("java:comp/env/jdbc/bookDB");
// 获取连接
connection conn = ds.getConnection();
// 将 Connection 对象返回数据库连接池，使 Connection 对象恢复到空闲状态
conn.close();
```

