## Tomcat 安装

### 配置 Tomcat

*目录结构*

![](./Images/tomcat目录结构.png)

* `bin` 目录，启动和终止 Tomcat 的二进制程序

* `webapps` Tomcat 的 Web 应用目录，默认情况下把 Web 应用放在这个目录

* `conf` 配置文件目录，包括 `server.xml` 和 `tomcat-users.xml`；

* `lib` 存放 Tomcat 及所有 Web 应用都可以访问的 JAR 文件

* `logs` 存放 Tomcat 执行时产生的日志文件

  `catalina.Y-m-d.log`：记录 Tomcat 启动过程的信息，可以看到启动的 JVM 参数及操作系统等日志

  `localhost.Y-m-d.log`：记录 Web 应用在初始化过程中遇到的未处理的异常，会被 Tomcat 捕获输出到这个日志文件

  `localhost_access_log`：Tomcat 的请求日志，包括 IP 地址，请求路径，事件，协议及状态码

  `manager/host-manager`：Tomcat 自带的 manager 项目日志信息

* `work` 存放 JSP 编译后产生的 Class 文件

* 将 `JAVA_HOME` 环境变量设为 JDK 安装目录

### 启动

* `startup.bat` 、`shutdown.bat`

### 定义上下文

要将 Servlet/JSP 应用程序部署到 Tomcat 时，需要显式或隐式定义一个 Tomcat 上下文。在 Tomcat 中，每一个 Tomcat 上下文都表示一个 Web 应用程序

显式定义 Tomcat 上下文：

* 在 Tomcat 的 `conf/Catalina/localhost` 目录下创建一个 XML 文件
* 在 Tomcat 的 `conf/server.xml` 文件中添加一个 `Context` 元素

如果决定给每一个上下文都创建一个 XML 文件，那么这个文件名就很重要，因为上下文路径是从文件名衍生得到的。如把 `commerce.xml` 文件放在 `conf/Catalina/localhost` 目录下，那么应用程序的上下文路径就是 `commmerce`，并且可以利用 URL 访问一个资源 `http://localhost:8080/commerce/resourceName`。上下文文件中必须包含一个 `Context` 元素，作为它的根元素。这个元素大多没有子元素，它是该文件中唯一的元素。

```xml
<Context docBase="C:/apps/commerce" reloadable="true"/>
```

唯一必须的属性是 `docBase` ，它用来定义应用程序的位置。`reloadable` 属性是可选的，但是如果存在，并且它的值为 `true`，那么一旦应用程序中 Java 类文件或其他资源有任何增加、减少或更新，Tomcat 都会侦测到，并且一旦侦测到这些变化，Tomcat 就会重新加载应用程序。在部署期间，建议将 `reoloadable` 值设为 `true`，生产期间不设置该属性。当把上下文文件添加到指定目录时，Tomcat 就会自动加载应用程序。当删除这个文件时，Tomcat 就会自动卸载应用程序。

定义上下文的另一种方法是在 `conf/server.xml` 文件中添加一个 `Context` 元素。在 `Host` 元素下创建一个 `Context` 元素。此处定义上下文需要给上下文路径定义 `path` 属性

```xml
<Host name="localhost" appBase="webapps" unpackWARs="true" autoDeploy="true">
		<Context path="/commerce" docBase="C:/apps/commerce" reloadable="true"/>
</Host>
```

一般不建议通过 `server.xml` 文件来管理上下文，只有重启 Tomcat 后，更新才生效

隐式定义：

* 通过将一个 WAR 文件或者整个应用程序复制到 `Tomcat` 的 `webapps` 目录下可以隐式部署

### 定义资源

定义一个 JNDI 资源，应用程序便可以在 Tomcat 上下文定义中使用。资源用 `Context` 元素目录下的 `Resource` 元素表示

```xml
<Context path="/appName" docBase="/your/app/path">
		<Resource name="jdbc/dataSourceName" auth="Container" type="javax.sql.DataSource"
				username="yourname"
				password="yourpasswd"
				driverClassName="com.mysql.jdbc.Driver"
				url="..."
				/>
</Context>
```

### SSL 证书

将证书导入 `keystore` 后，复制放在服务器某个位置下的 `keystore`，并对 `Tomcat` 进行配置即可。打开 `conf/server.xml` 文件，在 `<service>` 下添加 `Connector` 元素

```xml
<Connector port="443" 
    minSpareThreads="5" 
    maxSpareThreads="75" 
    enableLookups="true"
    disableUploadTimeout="true"
    acceptCount="100"
    maxThreads="200"
    # ssl 配置
    cheme="https"
    secure="true"
    SSLEnabled="true"
    keystoreFile="/path/to/keystore"
    keyAlias="example.com"
    keystorePass="password"
    clientAuth="false"
    sslProtocol="TLS"
/>
```

#### 配置用户和角色

编辑 `conf` 目录中的 `Tomcat-user.xml` 来创建用户和角色。role 元素定义角色，user 元素定义用户。

*tomcat-users.xml*

```xml
<?xml version='1.0' encoding='utf-8'>
<tomcat-users>
    <role rolename="manager"/>
    <role rolename="member"/>
    <user username="tom" password="secret" roles="manager,member"/>
    <user username="jerry" password="secret" roles="member"/>
</tomcat-users>
```