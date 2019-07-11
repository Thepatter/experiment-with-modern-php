## Servlets

### Servlet API

Servlet API 有 4 个 Java 包：

* `javax.servlet` ，其中包含定义 `Servlet` 和 `Servlet` 容器之间契约的类和接口
* `javax.servlet.http`，其中包含定义 `HTTP Servlet` 和 `Servlet` 容器之间契约的类和接口
* `javax.servlet.annotation`，其中包含标注 `Servlet`，`Filter`，`Listener` 的标注。它还为被标注元件定义元数据
* `javax.servlet.descriptor`，其中包含提供程序化登录 web 应用程序的配置信息的类型

Servlet 技术的核心是 Servlet，它是所有 Servlet 类必须直接或间接实现的一个接口。在编写 `Servlet` 和 `Servlet` 类时，直接实现它。在扩展实现这个接口的类时，间接实现它。

Servlet 接口定义了 Servlet 与 Servlet 容器之间的契约：Servlet 容器将 Servlet 类载入内存，并在 Servlet 实例上调用具体的方法。在一个应用程序中，每种 Servlet 类型只能有一个实例。

* 用户请求致使 Servlet 容器调用 Servlet 的 service 方法，并传入一个 `ServletRequest` 实例和一个 `ServletResponse` 实例。
* `ServletRequest` 中封装了当前的 HTTP 请求，因此，Servlet 开发人员不必解析和操作原始的 HTTP 数据。
* `ServletResponse` 表示当前用户的 HTTP 响应。
* 对于每一个应用程序，`Servlet` 容器还会创建一个 `ServletContext` 实例。这个对象中封装了上下文（应用程序）的环境详情。每个上下文只有一个 `ServletContext` 。
* 每个 `Servlet` 实例也都有一个封装 Servlet 配置的 `ServletConfig`

### Servlet

Servlet 接口中定义了以下 5 个方法

```java
void init(ServletConfig config) throws ServletException
void service(ServletRequest request, ServletResponse response) throws ServletException, java.io.IOException
void destroy()
java.lang.String getServletInfo()
ServletConfig getServletConfig()
```

`init`、`service`、`destroy` 是生命周期方法。Servlet 容器根据以下规则调用：

* `init`，当该 `Servlet` 第一次被请求时，Servlet 容器会调用这个方法。这个在后续请求中不会再被调用。可以利用这个方法执行相应的初始化工作。调用这个方法时，Servlet 容器会传入一个 `ServletConfig`。
* `service` ，每当请求 `Servlet` 时，`Servlet` 容器就会调用这个方法。编写代码时，假设 `Servlet` 要在这里被请求。第一次请求 `Servlet` 时，`Servlet` 容器调用 `init` 方法和 `service` 方法。后续的请求将只调用 `service` 方法
* `destroy`，当要销毁 `Servlet` 时，Servlet 容器就会调用这个方法。当要卸载应用程序，或者要关闭 `Servlet` 容器时，就会发生这种情况。一般会在这个方法中编写清除代码

`getServletInfo` 、`getServletConfig` 为非生命周期方法

* `getServletInfo`，这个方法会返回 `Servlet` 的描述。可以返回任何有用的字符串或 null
* `getServletConfig`，这个方法会返回由 `Servlet` 容器传给 `init` 方法的 `ServletConfig`。但是，为了让 `getServletConfig` 返回一个非 null 值，必须将传给 `init` 方法的 `ServletConfig` 赋给一个类级变量，除非它们是只读的，或者是 `java.util.concurrent.atomic` 包的成员

Servlet 规范提供了 GenericServlet 抽象类，可以通过扩展它来实现 Servlet。虽然 Servlet 规范并不在乎通信协议，但大多数的 Servlet 都是在 HTTP 环境中处理的，因此 Servlet 规范还提供了 `HttpServlet ` 来继承 `GenericServlet` ，并且加入了 HTTP 特性。这样可以通过继承 `HTTPServlet` 类来实现自己的 Servlet，只需要重写两个方法：`doGet` 和 `doPost`

### Servlet 容器

为了解耦，HTTP 服务器不直接调用 Servlet，而是把请求交给 Servlet 容器来处理

#### 工作流程

当客户端请求某个资源时，HTTP 服务器会用一个 `ServletRequest` 对象把客户的请求信息封装起来，然后调用 `Servlet` 容器的 `service` 方法，`Servlet` 容器拿到请求后，根据请求的 URL 和 Servlet 的映射关心，找到相应的 Servlet，如果 Servlet 还没有被加载，就用反射机制创建这个 Servlet，并调用 Servlet 的 `init` 方法来完成初始化，接着调用 Servlet 的`service` 方法来处理请求，把 `ServletResponse` 对象返回给 HTTP 服务器，HTTP 服务器会把响应发送给客户端。

*servlet工作流程*

![](./Images/servlet工作流程.jpg)

#### Web应用

Servlet 容器会实例化和调用 Servlet，一般采用 Web 应用程序的方式来部署 Servlet 的，而根据 Servlet 规范，Web 应用程序有一定的目录结构，在这个目录下分别放置了 Servlet 的类文件、配置文件以及静态资源，Servlet容器通过读取配置文件，就能找到并加载 Servlet

*Web应用目录结构*

![](./Images/Web应用目录结构.png)

Servlet 规范里定义了 ServletContext 接口来对应一个 Web 应用。Web 应用部署好后，Servlet 容器在启动时会加载 Web 应用，并为每个 Web 应用创建唯一的 ServletContext 对象。可以将 ServletContext 看成一个全局对象，一个 Web 应用可能有多个 Servlet，这些 Servlet 可以通过全局的 ServletContext 来共享数据，这些数据包括 Web 应用的初始化参数、Web 应用目录下的文件资源等。由于 ServletContext 持有所有的 Servlet 实例，还可以通过它实现 Servlet 请求的转发

### 扩展机制

引入了 `Servlet` 规范后，不需要关心 `Socket` 网络通信，不需要关心 HTTP 协议，也不需要关心业务类如如何被实例化和调用的，因为这些被 Servlet 规范标准化，**Servlet 规范提供了两种扩展机制 `Filter` 和 `Listener`。Filter 是干预过程的，它是过程的一部分，是基于过程行为的；Listener 是基于状态的，任何行为改变同一个状态，触发的事件是一致的**

##### Filter 过滤器

这个接口允许对请求和响应做一些统一的定制化处理（根据请求的频率来限制访问）。Web 应用部署完成之后，Servlet 容器需要实例化 Filter 并不 Filter 链接成一个 FilterChain，当请求进来时，获取第一个 Filter 并调用 `doFilter` 方法，`doFilter` 方法负责调用这个 `FilterChain` 中的下一个 `Filter`

##### Listener 监听器

当 Web 应用在 `Servlet` 容器中运行时，Servlet 容器内部会不断的发生各种事件，如 `Web` 应用的启动和停止、用户请求到达等。当事件发生时，`Servlet` 容器会负责调用监听器的方法。

### ServletRequest

对于每个 HTTP 请求，Servlet 容器都会创建一个 ServletRequest 实例，并将它传给 Servlet 的 Service 方法。ServletRequest 封装了关于这个请求的信息

```java
# 返回请求主体的字节数。失败 -1
public int getContentLength()
# 返回请求主题的 MIME 类型，失败 null
public String getConteneType()
# 返回指定请求参数的值，失败 null
public String getParameter(String name)()
# 返回 HTTP 协议名称和版本
public String getProtocol()
```

### ServletResponse

`javax.servlet.ServletResponse` 接口表示一个 Servlet 响应，在调用 Servlet 的 Service 方法前，Servlet 容器首先创建一个 ServletResponse，并将它作为第二个参数传给 Service 方法。ServletResponse 隐藏了向浏览器发送响应的复杂过程。

在 ServletResponse 中的 `getWriter` 方法，返回了一个可以向客户端发送文本的 `java.io.PrintWriter`，默认情况下，`PrintWriter` 对象使用 ISO-8859-1 编码；`getOutputStream` ，但这个方法是用于发送二进制数据的，因此，大多数情况使用的是 `getWriter` ，而不是 `getOutputStream`；

在发送任何 HTML 标签前，应该先调用 `setContentType` 方法，设置响应的内容类型。

### ServletConfig

当 Servlet 容器初始化 Servlet 时，Servlet 容器会给 Servlet 的 `init` 方法传入一个 `ServletConfig` 。`ServletConfig` 封装可以通过 `@WebServlet` 或者部署描述符传给 `Servlet` 的配置信息。这样传入的每一条信息就叫一个初始参数，一个初参数有 Key 和 value

为了从 Servlet 内部获取到初始参数的值，要在 Servlet 容器传给 Servlet 的 init 方法的 ServletConfig 中调用 `getInitParameter` 方法。





