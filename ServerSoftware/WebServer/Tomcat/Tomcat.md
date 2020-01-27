### Tomcat  Server

#### 安装及启动

##### 安装

* 安装 JDK，JDK 版本需满足 Tomcat 对应版本要求的最小 JDK 版本

* 下载解压 Tomcat 安装包

* 设置环境变量 

  ```shell
  export JAVA_HOME=/usr/local/tomcat
  export CATALINA_HOME=/usr/local/tomcat
  ```

##### 目录结构

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

* `work` Tomcat 的工作目录，在运行时把生成的一些工作文件放于此目录下。（默认存放 JSP 编译后产生的 Servlet 类文件）

* 将 `JAVA_HOME` 环境变量设为 JDK 安装目录

##### server.xml

Tomcat 本身由一系列可配置的组件构成，其中核心组件是 Servlet 容器组件，它是所有其他 Tomcat 组件的顶层容器。Tomcat 的各个组件可以在 `<CATALINA_HOME>/conf/server.xml` 文件进行配置

*各Tomcat组件之间关系*

```xml
<Server>
  <Service>
  	<Connector/>
    <Engine>
      <Host>
        <Context></Context>
      </Host>
    </Engine>
  </Service>
</Server>
```

*Tomcat组件之间嵌套关系*

![](./Images/Tomcat各个组件之间的嵌套关系.jpeg)

##### 组件

在 server.xml 文件中，每个元素都代表一种 tomcat 组件：

###### 顶层组件

包括 `<Server>` 元素和 `<Service>` 元素

* Server 元素

  代表整个 Servlet 容器组件，可以包含一个或多个 Service 组件。由 `org.apache.catalina.Server` 接口来定义。

  |   属性    |                             描述                             |
  | :-------: | :----------------------------------------------------------: |
  | className | 指定实现 `org.apache.catalina.Server` 接口的类，默认为 `org.apache.catalina.core.StandardServer` |
  |   port    |     指定 Tomcat 监听 shutdown 命令的短裤，必须设置该属性     |
  | shutdown  | 终止Tomcat服务器运行时，发送给监听端口的字符串，必须设置该属性 |

* Service 元素

  Service 元素中包含一个 Engine 元素，以及一个或多个 Connector 元素，这些 Connector 元素共享同一个 Engine 元素。由 `org.apache.catalina.Service` 接口定义

  |   属性    |                             描述                             |
  | :-------: | :----------------------------------------------------------: |
  | className | 指定实现接口类，默认 `org.apache.catalina.core.StandardService` |
  |   name    |                      定义 Service 名字                       |

###### 连接器类组件

为 `<Connector>` 元素，代表客户与服务器之间的通信接口，负责将客户的请求发送给服务器，并将服务器的响应结果发送给客户。

```xml
<!-- 通过 8080 接收 HTTP 请求 -->
<Connector port="8080" protocol="HTTP/1.1"
               connectionTimeout="20000"
               redirectPort="8443" />
<!-- 通过 8009 端口接收其他 HTTP 服务器转发的请求 -->
<Connector port="8009" protocol="AJP/1.3" redirectPort="8443" />
```

|     属性      |                             描述                             |
| :-----------: | :----------------------------------------------------------: |
| enableLookups | 为 true，支持域名解析，可以把 IP 地址解析为主机名，Web 应用中调用 `request.getRemostHost()` 方法将返回客户的主机名，默认为 false |
| redirectPort  | 指定转发端口，如果当前端口只支持 non-SSL 请求，在需要安全通信的场合，将把客户请求转发到基于 SSL 的 redirectPort 端口 |
|     port      |                       设定 TCP 端口号                        |
|   protocol    |                设定客户端的与服务端的通信协议                |

*HTTP/1.1 Connector 元素的属性*

|       属性        |                             描述                             |
| :---------------: | :----------------------------------------------------------: |
|      address      | 如果服务器有两个以上 IP 地址，该属性可以设定端口监听的 IP 地址，默认情况下，端口会监听服务器上所有 IP 地址 |
|    maxThreads     | 设定处理客户请求的线程的最大数目，这个值苦厄定了服务器可以同时响应客户请求的最大数目，默认值为 200 |
|    acceptCount    | 客户请求队列中存放了等待被服务器处理的客户请求。该属性用于设定在客户请求队列中的最大客户请求数。默认 100.如果队列已满，新的客户请求将被拒绝 |
| connectionTimeout |   建立客户链接超时时间，单位毫秒， -1 为无限制。默认 20000   |
|  maxConnections   | 设定在任何时刻服务器会接受并处理的最大连接数。当服务器接受和处理的连接数达到这个上限时，新的连接将被阻塞。 |
|  maxCookieCount   | 指定对于一个客户请求所允许的最大 Cookie 数目。默认 200，如果设为一个负数，则无限制 |
| maxHttpHeaderSize | 指定 HTTP 请求头和响应头的最大长度，以字节为单位，默认 8192（8kb） |
|  maxSwallowSize   | 指定请求正文的最大长度，以字节为单位，默认 2097152（2mb）。为负，则无限制 |
|     executor      |                    指定所使用执行器的名字                    |

###### 执行器组件

执行器类元素 `<Executor>` 代表可以被 Tomcat 的其他组件共享的线程池。由于 `<Connector>` 元素可能会引用 `<Executor>` 元素配置的执行器，因此需放在需要引用元素前面。

|      属性       |                              --                              |
| :-------------: | :----------------------------------------------------------: |
|    className    | 指定实现类，默认 `org.apache.catalina.StandardThreadExecutor` |
|      name       |             执行器名字，其他配置元素会引用该名字             |
| threadPriority  | 设定线程池中线程的优先级别，默认值为 5（Thread.NORM_PRIORITY）取值 |
|     daemon      |        设置线程池中的线程是否为后台线程，默认为 true         |
|   namePrefix    | 设定线程池中的线程的名字的前缀，线程名字格式「前缀 + 线程序号」 |
|   maxThreads    |           设定线程池中线程的最大数目，默认值为 200           |
| minSpareThreads |  设定线程池中处于空闲或运行状态的线程的最小数目，默认为 25   |
|   maxIdleTime   | 设定一个线程允许处理闲置状态的最长时间，单位毫秒，默认 60000，当线程池中的线程数超过了属性值，会关闭限制时间超过该值线程 |
|  maxQueueSize   | 可运行任务队列中存放了等待运行的任务，此属性设定存放在该队列中任务的最大数目，默认值为 `Integer.MAX_VALUE` |

###### 容器类组件

代表处理客户请求并生成响应结果的组件，有四种容器类元素，分别为 ：`<Engine>`、`<Host>`、`<Context>`、`<Cluster>` 元素

* Engine

  为特定的 Service 组件处理所有客户请求，处理在同一个 Service 中的所有 Connector 元素接收到的客户请求。由 `org.apache.catalina.Engine` 接口定义

  |    描述     |                             属性                             |
  | :---------: | :----------------------------------------------------------: |
  |  className  |  指定实现类，默认 `org.apache.catalina.core.StandardEngine`  |
  | defaultHost | 指定处理客户请求的默认主机名，在 Engine 的 Host 子元素中必须定义该主机 |
  |    name     |                      定义 Engine 的名字                      |

  Engine 中可以包含 `<Realm>`、`<Value>`、`<Host>` 子元素

* Host

  为特定的虚拟主机处理所有客户请求，一个 Engine 元素中可以包含多个 Host 元素，每个 host 元素定义一个虚拟主机，可以包含一个或多个 web 应用。由 `org.apache.catalina.Host` 接口定义。

  |      属性       |                             描述                             |
  | :-------------: | :----------------------------------------------------------: |
  |    className    |     指定实现类，默认 `org.apache.catalina.StandardHost`      |
  |     appBase     | 指定虚拟主机的目录，可以指定绝对目录，也可以指定相对于 `<CATALINA_HOME>` 的相对目录，如果未设定，默认为 `<CATALINA_HOME>/webapps` |
  |   unpackWARS    | 为 true，将把 Web 应用的 WAR 文件先展开为开发目录结构后再运行，如果设为 false，将直接运行 WAR 文件 |
  |   autoDeploy    | 为 true，当 Tomcat 服务器处于运行状态时，能够检测 appBase 下的文件，如果有新的 web 应用加入，会自动发布 |
  |      alias      |             指定虚拟主机的别名，可以指定多个别名             |
  | deployOnStartup | 为 true，当 Tomcat 启动时自动发布 appBase 目录下所有 web 应用，如果 Web 应用在 server.xml 中没有相应的 `<Context>` 元素，将采用默认 `<Context>` 元素。默认值为 true |
  |      name       |                       定义虚拟主机名字                       |
  |     workDir     | 指定虚拟主机的工作目录。运行时会把与这个虚拟主机的所有 Web 应用相关的临时文件放在此目录下。默认为 `<CATALINA_HOME>/work`。如果 `<Host>` 元素下的一个 `<Context>` 元素也设置了 workDir 属性，那么 `<Context>` 元素的 workDir 属性会覆盖该属性 |
  |    deployXML    | 如果设为 false，那么 Tomcat 不会解析 web 应用中用于设置 Context 元素的 META-INF/context.xml 文件。默认为 true |

  `<Host>` 元素可以有一个或多个 `<alias>` 元素，指定别名

* Context

  为特定的 Web 应用处理所有客户的请求，每个 Context 元素代表了运行在虚拟主机上的单个 Web 应用，一个 host 可以包含多个 Context 元素。由 `org.apache.catalina.Context` 接口定义。

  |    属性     |                              --                              |
  | :---------: | :----------------------------------------------------------: |
  |  className  |    指定实现类，默认 `org.apache.catalina.StandardContext`    |
  |    path     |                指定访问该 Web 应用的 URL 入口                |
  |   docBase   | 指定 Web 应用的文件路径，可以给定绝对路径，也可以给定相对于 Host 的 appBase 属性的相对路径。如果 Web 采用开放目录接口，则指定根目录，采用 WAR 则指定 WAR 文件路径 |
  | reloadable  | 为 true，在运行状态下会监视 WEB-INF/classes 和 WEB-INF/lib 目录下的 class 文件的改动，以及监视 WEB-INF/web.xml 文件的改动。如果有改动则自动刷新，默认为 false，（建议开放为 true，生产为 false） |
  |   cookies   |       指定是否通过 Cookie 来支持 Session，默认为 true        |
  | unloadDelay |   设定 Tomcat 等待 Servlet 卸载的毫秒数，该属性默认为 2000   |
  |   workDir   | 指定 web 应用的工作目录。Tomcat 运行时会把与这个 web 应用相关的临时文件放在此目录下 |
  |  uppackWar  | 为 true，将把 web 应用的 WAR 文件先展开为开放目录结构后再运行，false 则直接运行，默认为 true |

  Context 元素中可以包含 `<Realm>`、`<Value>`、`<Resource>` 、`<Manager>` 等子元素

* Cluster

  为 Tomcat 集群进行会话复制、Context 组件属性的复制，以及集群范围内 WAR 文件的发布

* 嵌套类元素

  代表可以嵌入到容器中的组件，如 `<Value>` 元素和 `<Realm>` 元素

##### 启动

* Windows

  `startup.bat` 、`shutdown.bat`

* macos

  ```shell
  brew services tomcat
  ```
  
* linux

  ```shell
  catalina.sh run
  ```

##### 加载模式

Tomcat 的类加载器负责为 Tomcat 本身以及 Java Web 应用加载相关的类。假如 Tomcat 的类加载器要为一个 Java Web 应用加载一个名为 `Sample` 的类，类加载器会安装以下顺序到各个目录中去查找 `Sample` 类的 `.class` 文件，直到找到为止，如果所有目录都不存在 `.class` 文件，则抛出异常。加载顺序（子女优先）：

1.在 Java Web 应用的 `WEB-INF/classes` 目录下查找

2.在 Java Web 应用的 `WEB-INF/lib` 目录下的 JAR 文件中查找

3.在 Tomcat 的 lib 子目录下直接查找

4.在 Tomcat 的 lib 子目录下的 Jar 文件中查找

##### 管理界面配置

* 配置用户角色

  编辑 `conf` 目录中的 `Tomcat-user.xml` 来创建用户和角色。role 元素定义角色，user 元素定义用户。

  *tomcat-users.xml*

  ```xml
  <?xml version='1.0' encoding='utf-8'>
  <tomcat-users>
      <role rolename="manager-gui"/>
      <role rolename="admin-gui"/>
      <user username="tom" password="secret" roles="manager-gui,admin-guir"/>
      <user username="jerry" password="secret" roles="admin-gui"/>
  </tomcat-users>
  ```

* 允许远程访问

   `webapps` 下的 `host-manager` 和 `manager` 都有一个共同的文件夹 `META-INF`，里面都有 `context.xml`，这个文件夹的内容是

  ```xml
  <Context antiResourceLocking="false" privileged="true" >
    <Valve className="org.apache.catalina.valves.RemoteAddrValve"
           allow="127.d+.d+.d+|::1|0:0:0:0:0:0:0:1" />
  </Context>
  ```

  修改为无限制

  ```xml
  <Context antiResourceLocking="false" privileged="true" >
    <Valve className="org.apache.catalina.valves.RemoteAddrValve"
           allow=".*" />
  </Context>
  ```

#### 工作模式

Tomcat 有三种工作模式

* Tomcat 在一个 Java 虚拟机进程中独立运行，可看作是能运行 Servlet 的独立 Web 服务器
* Tomcat 运行在其他 Web 服务器的进程中，Tomcat 不直接和客户端通信，仅仅为其他 Web 服务器处理客户端访问 Servlet 的请求
* 尽管Tomcat在一个Java虚拟机进程中独立运行，但是它不直接和客户端通信，仅仅为与它集成的其他Web服务器处理客户端访问Servlet的请

#### Context 元素

##### 加载顺序

要将 Servlet/JSP 应用程序部署到 Tomcat 时，需要显式或隐式定义一个 Tomcat 上下文。在 Tomcat 中，每一个 Tomcat 上下文都表示一个 Web 应用程序。它代表运行在虚拟主机 `<Host>` 上的单个 web 应用。

在低版本 Tomcat 中，允许直接在 `<CATALINA_HOME>/conf/server.xml` 文件中配置 `<Context>` 元素（在运行时修改 server.xml 文件，重启生效）6.x 开始的高版本尽管允许直接在 server.xml 文件中配置 `<Context>` 元素，但不提倡采用这种方式。Tomcat 提供了多种配置 `<Context>` 元素的途径。当 Tomcat 加载一个 web 应用时，会按照以下顺序查找 web 应用的 `<Context>` 元素：

1. 到 `<CATALINA_HOME>/conf/context.xml` 文件中查找 `<Context>` 元素。这个文件中的 `<Context>` 元素信息适用于所有 web 应用

2. 到 `<CATALINA_HOME>/conf/[enginename]/[hostname]/context.xml.default` 文件中查找 `<Context>` 元素，该元素信息适用于当前虚拟主机中的所有 web 应用。

   ```
   # 以下文件中的 Context 元素适用于名为 Catalina 的 Engine 下的 localhost 主机的所有 web 应用
   <CATALINA_HOME>/conf/Catalina/localhost/context.xml.default
   ```

3. 到 `<CATALINA_HOME>/conf/[enginename]/[hostname]/[contextpath].xml` 文件中查找 `<Context>` 元素。`[contextpath]` 表示单个 web 应用的 URI 入口。在 `[contextpath].xml` 文件中的 `<Context>` 元素只适用于单个 web 应用。

   ```
   # 以下文件中的 <Context> 元素适用于名为 Catalina 的 Engine 下的 localhost 主机中的 helloapp 应用
   <CATALINA_HOME>/conf/Catalina/localhost/helloapp.xml
   ```

4. 到 Web 应用的 `META-INF/context.xml` 文件中查找 `<Context>` 元素。这个文件中的 `<Context>` 元素的信息适用于当前的 Web 应用

5. 到 `<CATALINA_HOME>/conf/server.xml` 文件中的 `<Host>` 元素中查找 `<Context>` 子元素。该 `<Context>` 元素的信息只适用于单个 web 应用

如果仅仅为单个 web 应用配置 `<Context>` 元素，可以优先选择第三种或第四种方式。第三种方式要求在 Tomcat 的相关目录下增加一个包含 `<Context>` 元素的配置文件，而第四种方式则要求在 web 应用的相关目录下增加一个包含 `<Context>` 元素的配置文件。对于这两种方式，Tomcat 在运行时会检测包含 `<Context>` 元素的配置文件是否被更新，如果被更新，Tomcat  会自动刷新

##### web 应用的工作目录

每个 web 应用都有一个工作目录，Servlet 容器会把这个 web 应用相关的临时文件存放在这个目录下。Tomcat 为 web 应用提供的默认工作目录为：

```shell
<CATALINA_HOME>/work/[enginename]/[hostname]/[contextpath]
```

Tomcat 还允许配置 web 应用的 `<context>` 元素时，用 `workDir` 属性来显式地指定 web 应用的工作目录，Web 应用的工作目录不仅可以被 Servlet 容器访问，还可以被 Web 应用的 Servlet 访问。Servlet 规范规定，当 Servlet 容器在初始化一个 web 应用时，应该向刚创建的 `ServletContext` 对象中设置一个名为 `javax.servlet.context.tempdir` 的属性，它的属性值为一个 `java.io.File` 对象，它代表当前 web 应用的工作目录

```java
File workDir = (File) context.getAttribute("javax.servlet.context.tempdir");
```

##### 显式定义 Tomcat 上下文

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

##### 定义资源

定义一个 JNDI 资源，应用程序便可以在 Tomcat 上下文定义中使用。资源用 `Context` 元素目录下的 `Resource` 元素表示

*Resource属性*

|      属性       |                             描述                             |
| :-------------: | :----------------------------------------------------------: |
|      name       |                  指定 Resource 的 JNDI 名字                  |
|      auth       | 指定管理 Resource 的 Manager，有两个可选值：Container（由容器来创建和管理） 和 Application（由 web 应用俩创建和管理） |
|      type       |                   指定 Resource 所属的类名                   |
|    maxActive    |   指定数据库连接池中处于活跃状态的最大连接数，0 即不受限制   |
|     maxIdle     | 指定数据库连接池中处于空闲状态的数据库连接的最大数目，0 即不受限制 |
|     maxWait     | 指定数据库连接池中数据库连接处于空闲状态的最大时间（毫秒），超过该时间将抛出异常，-1 即无限等待 |
|    username     |                       连接数据库用户名                       |
|    password     |                      指定连接数据库密码                      |
| driverClassName |             指定连接数据库的 JDBC 驱动器实现类名             |
|       url       |                     指定 JDBC 数据库 url                     |

在XML 中 & 有着特殊的含义，需要转义为： `&amp;`

```xml
<Context path="/appName" docBase="/your/app/path">
		<Resource name="jdbc/dataSourceName" auth="Container" type="javax.sql.DataSource"
				username="root"
				password="secret"
				driverClassName="com.mysql.cj.jdbc.Driver"
				url="jdbc:mysql://localhost:3306/web?characterEncoding=UTF-8&amp;userSSL=false"/>
</Context>
```

如果希望数据源被 Servlet 容器内一个虚拟主机的多个 web 应用访问，可以在 `<CATALINA_HOME>/conf/server.xml` 文件中相应 `<Host>` 元素中配置 `<Resource>` 子元素

##### 管理会话

Manager 元素可以嵌套在 Context 元素内，如果未包括在内，则将自动创建默认的 Manager 配置，该配置足以满足大多数要求。

*Manager的所有属性均支持以下属性*

|                  属性                   |                             描述                             |
| :-------------------------------------: | :----------------------------------------------------------: |
|                className                | 使用 Java 类名称，此类必须实现 `org.apache.catlina.Manager` 接口，未指定则使用标准值 |
|            maxActiveSessions            | 此管理器将创建的活动会话的最大数目，默认 -1 无限制。达到限制后，任何创建新会话的尝试都将失败，IllegalStateException |
| notifyAttributeListenerOnUnchangedvalue | 如果将属性添加到会话中，并且该属性已经以相同的名字存在于会话中，则将通知所有 HttpSessionAttributeeListener 该属性已被替换。未指定则使用默认值 true |
|  notifyBindingListenerOnUnchangedValue  | 如果将属性添加到会话中，该属性已经以相同的名字存在于会话中，且该属性实现 HttpSessionBindingListener，将向侦听器通知该属性已取消绑定并再次绑定，未指定则使用默认值 false |

###### 标准实现

Manager 的标准实现是 `org.apache.catalina.session.StandardManger`，它还支持以下其他属性

*StandardManger支持的标准属性*

|                 属性                 |                             描述                             |
| :----------------------------------: | :----------------------------------------------------------: |
|               pathname               | 如果可能在应用程序重新启动后将保留会话状态的文件的绝对路径或相对路径（相对于此上下文的工作目录）默认 `SESSIONS.ser`，通过将此属性设置为空字符串，可以禁用此持久性 |
|       processExpiresFrequency        | 会话到期的频率以及相关的管理操作。对于指定数量的 backgroundProcess 调用，管理器操作将执行一次（即，数量月底，检查将越频繁进行）最小为 1，默认为 6 |
|          secureRandomClass           | 扩展 `java.security.SecureRandom` 用于生成会话 ID 的 Java 类，默认 `java.security.SecureeRandom` |
|         secureRandomProvider         | 提供者程序，用于创建 `java.security.SecureRandom` 生成会话 ID 的实例，如果指定了无效的算法或提供者，则 Manager 将使用平台默认提供程序和默认算法。 |
|        secureRandomAlgorithm         | 用于创建 `java.security.SecureRandom` 生成会话 ID 的实例的算法的名称。如果指定了无效的算法和提供者将使用平台默认提供程序和算法。未指定，默认使用 SHA1PRNG 算法，如果不支持默认算法，将使用平台默认值，要指定应用平台默认值，不要设置secureRandomProvider 属性且该属性为空字符串 |
|      sessionAttributeNameFilter      | 用于过滤将分配哪些会话属性的正则，仅当属性名称于该模式匹配时，该属性才会被分发。如果模式的长度为零或null，则所有属性都可分配。 |
| sessionAttributeValueClassNameFilter | 用于过滤将分配哪些会话属性的正则，仅当值的实现类名称与此模式匹配时，才会分配属性。如果模式的长度为 0 或空，则所有属性都可以分配。默认 null |
| warnOnSessionAttributeFilterFailure  | 如果 sessionAttributeNameFilter 或 sessionAttributeValueClassNameFilter 阻止了一个属性，是否应在 WARN 级别记录该属性。如果禁用了WARN级别的日志记录，则它将记录在DEBUG。 除非启用了SecurityManager，否则此属性的默认值为false，在这种情况下，默认值为true。 |

###### 持久实现

必须将 `org.apache.catalina.session.StandardSession.ACTIVITY_CHECK` 或 `org.apache.catalina.STRICT_SERVLET_COMPLIANCR` 系统属性设置为 true，才能使持久管理器工作。

Manager 的持久实现是 `org.apache.catalina.session.PersistentManager`，除了创建和删除会话的常规操作外，PersistentManager 还具有将会话换出到持久性存储机制的功能，并且可以在 Tomcat 的正常重启过程中保存所有会话。通过选择嵌套在 Manager 元素内的 Store 元素（必须指定一个），可以选择使用的实际持久存储机制

*Manager除了支持上述属性外，还支持以下属性*

|                 属性                 |                             描述                             |
| :----------------------------------: | :----------------------------------------------------------: |
|              className               | 与通用属性含义一致，必须指定 `org.apache.catalina.session.PesistentManager` 使用此管理器 |
|            maxIdleBackup             | 自上一次访问会话开始到可以保留到会话存储的时间间隔（秒）默认 -1 禁用此功能 |
|             maxIdleSwap              | 不活动会话有可能被交换到磁盘之前可能处于空闲状态的最长时间（秒），-1即不应仅不活动而交换。如果启用此功能应该等于或大于 maxIdleBackup 的值，默认 -1，禁用此功能 |
|             minIdleSwap              | 最短时间，如果指定该值应小于所指定 maxIdleSwap 的值。默认 -1，不交换 |
|       processExpiresFrequency        |                        与标准实现相同                        |
|            saveOnRestart             | 在 Tomcat 重启（或重载）时是否应保留并重载会话，默认为 true  |
|          secureRandomClass           |                        与标准实现相同                        |
|        secureRandomAlgorithm         |                        与标准实现相同                        |
|         secureRandomProvider         |                        与标准实现相同                        |
|      sessionAttributeNameFilter      | 与标准实现相同，会话属性名称必须与该模式完全匹配，如 `(userName|sessionHistory)`将仅分发名为 `userName` 和 `sessionHistory` 属性，未指定将使用默认值 null |
| sessionAttributeValueClassNameFilter | 与标准实现相同，如果启用了 SecurityManger ，默认值 `ava\\.lang\\.(?:Boolean` |
| warnOnSessionAttributeFilterFailure  |                        与标准实现相同                        |

所有 Manager 实现均允许嵌套 `<SessionldGenerator>` 元素，它定义了会话 ID 的生成行为，`SessionldGenerator` 所有实现均具有以下属性：

*SessionIdGenerator属性元素*

|      属性       |     描述     |
| :-------------: | :----------: |
| sessionIdLength | 会话ID的长度 |

`<Store>` 元素定义了持久数据存储的特征，`<Stroe>` 元素的有两种属性：

* 基于文件的存储

  在基于文件的存储的实现中，将换出的会话保存在配置目录中的单个文件中（基于会话标识符命名）

  `<CATALINA_HOME>/work/Catalina/[hostname]/[applicationname]`。每个 `HttpSession` 对象都会对应一个文件，它以 Session ID 作为文件名，扩展名为 `.session`

  *基于文件的 Store元素属性配置*

  |   属性    |                             描述                             |
  | :-------: | :----------------------------------------------------------: |
  | className | 使用的 Java 类名称，此类必须实现 `org.apache.catalina.Store` 接口，必须指定为`org.apache.catalina.session.FileStore` |
  | directory | 会话文件写的绝对路径或相对路径（相对于此 web 应用的临时工作目录），如未指定，则使用容器分配的临时工作目录 |

  ```xml
  <Context  reloadable="true" >
    <Manager className="org.apache.catalina.session.PersistentManager" 
      saveOnRestart="true"
      maxActiveSessions="1200"
      minIdleSwap="1800"
      maxIdleSwap="3600"
      maxIdleBackup="3600">
      <Store className="org.apache.catalina.session.FileStore" directory="sessions" />
    </Manager>
  </Context>
  ```

* 基于 JDBC 的实现

  在大量换出的会话情况下，此实现比文件存储更好的性能。

  *基于JDBC的 Store 元素属性*

  |          属性          |                             描述                             |
  | :--------------------: | :----------------------------------------------------------: |
  |       className        | 使用类名，此类必须实现 `org.apache.catalina.Store` 接口，必须指定为`org.apache.catalina.session.JDBCStore` |
  |     connectionName     |            将传递给已配置的 JDBC 驱动程序的用户名            |
  |   connectionPassword   |             将传递给已配置的 JDBC 驱动程序的密码             |
  |     connectionURL      |            传递给已配置的 JDBC 驱动程序的连接 URL            |
  |     dataSourceName     | JDBC DataSource 工厂的 JNDI 资源名称，如果指定了此选项，并且可以找到有效的 JDBC 资源，则将使用它，并且连接设置将忽略。由于此代码使用预处理语句，需要配置池化的预处理语句。 |
  |       driverName       |             要使用的 JDBC驱动程序的 Java 类名称              |
  |    localDataSource     | 允许 store 使用上下文定义的数据源，而不是全局数据源，如果未指定，则默认为 false，使用全局数据源 |
  |     sessionAppCol      | 指定会话表中web应用的列名，格式为 `/Engine/Host/Context`，默认为 app |
  |     sessionDataCol     | 指定会话表中会话属性的序列化的列名，必须为 BLOB 类型，默认为 data |
  |      sessionIdCol      |  指定会话表中会话的标识符的列名，必须至少 32 位，默认位 id   |
  | sessionLastAccessedCol | 指定会话表中lastAccessedTime会话属性的列名，必须接受 java long (64位)类型，默认 maxinactive |
  | sessionMaxInactiveCol  | 指定会话表中maxInactiveInterval会话属性的列名，必须接受 java integer(32位)，默认 maxinactive |
  |      sessionTable      | 指定存储会话表名，该表需包含以上数据库列，默认 tomcat$sessions |
  |    sessionValidCol     |           指定会话表中会话是否有效列名，默认 valid           |

  ```sql
  create table tomcat_sessions (
    session_id     varchar(100) not null primary key,
    valid_session  char(1) not null,
    max_inactive   int not null,
    last_access    bigint not null,
    app_name       varchar(255),
    session_data   mediumblob,
    KEY kapp_name(app_name)
  );
  ```

  ```xml
  <Context  reloadable="true">
    <Manager className="org.apache.catalina.session.PersistentManager" 
      saveOnRestart="true"
      maxActiveSessions="1200"
      minIdleSwap="1800"
      maxIdleSwap="3600"
      maxIdleBackup="3600">
     <Store className="org.apache.catalina.session.JDBCStore"
            driverName="com.mysql.cj.jdbc.Driver"
            connectionName="root"
            connectionPassword="secret"
            connectionURL="jdbc:mysql://mysql8/web"
            sessionTable="tomcat_sessions"
            sessionIdCol="session_id"
            sessionDataCol="session_data"
            sessionValidCol="valid_session"
            sessionMaxInactiveCol="max_inactive"
            sessionLastAccessedCol="last_access"
            sessionAppCol="app_name"/>
    </Manager>
  </Context>
  ```

  为了使基于 JDCB 的存储成功连接到数据库，JDBC 驱动程序必须对于 Tomcat 的内部类加载器科技，即必须将驱动 jar 文件放入 `CATALINA_HOME/lib` 目录中

为了成功恢复会话属性的状态，所有这些属性必须实现 `java.io.Serializable` 接口，每个 web 应用程序默认配置了标准管理器实现，要禁用此持久性功能，只需在上下文配置文件中指定

```xml
<Manager pathname="" />
```

#### SSL 证书

#### Tomcat 虚拟主机

在 Tomcat 的配置文件 server.xml 中，`<Host>` 元素代表虚拟主机，在同一个 `<Engine>` 元素下可以配置多个虚拟主机。

```xml
<!-- <CATALINA_HOME>/conf/server.xml -->
<host name="www.javathink.com" appBase="/usr/local/tomcat/javathink"
      unpackWARS="true" autoDeploy="true">
		<alias>javathink</alias>
</host>
```

每个虚拟主机都可以有一个默认 Web 应用，它的默认目录为 ROOT。（如果要设置虚拟主机的默认 Web 应用的 `<Context>` 元素，那么它的 path 属性的值应该为一个空字符串）

#### 定义资源

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

#### SSL 证书

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

