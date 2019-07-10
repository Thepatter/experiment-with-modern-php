### WebAnnotations 注解

Servlet3.0 在 `javax.servlet.annotation` 包中引入了一组注解类型，可以注解包括 `servlet`，`filter`，`listener` 等 Web 对象

#### HandlesTypes

这个注解用来声明 `ServletContainerInitializer` 可以处理的类。

* 这个注解只有一个属性 `value`，该值为其可以处理的类

```java
// 该 initializer 可以处理 UsefulServlet
@HandlesTypes({UsefulServlet.class})
public class MyInitializer implements ServletContainerInitializer {

}
```

#### HttpConstraint

表示施加到所有的 HTTP 协议方法的安全约束，且 HTTP 协议方法对应的 `@HttpMethodConstraint` 没有出现在 `@ServletSecurity` 注解中。此注解类型必须包含在 `ServletSecurity` 注解中

* `rolesAllowed` 

  包含授权角色的字符串数组

* `transportGuarantee` 

  连接请求所必须满足的数据保护需求。有效值为 `ServletSecurity.TransportGuarantee` 枚举成员 `CONFIDENTIAL or NONE`

* `value` 

  默认授权

#### HttpMethodConstraint

特定的 HTTP 方法的安全性约束。只能出现在 `ServletSecurity` 注解中

* `emptyRoleSemantic` 

  当 `rolesAllowed` 返回一个空数组，应用默认授权语义。有效值为 `ServletSecurity.EmptyRoleSemantic` 枚举成员 `DENY or PERMIT`

* `rolesAllowed` 

  包含授权角色的字符串数组

* `transportGuarantee` 

  连接请求所必须满足的数据保护需求。有效值为 `ServletSecurity.TransportGuarantee` 枚举成员

* `value` 

  HTTP 协议方法

```java
// 该 servlet 可以被任何用户通过 GET 方法访问，但其他的 HTTP 方法只能被授予经理角色的用户访问
@ServletSecurity(value = @HttpContraint(rolesAllowed = "manager"), httpMethodConstraints = {@HttpMethodConstraint("GET")})
// 该 Servlet 阻止所有通过 Get 方法的访问，但允许所有 member 角色的用户通过其他 HTTP 方法访问
@ServletSecurity(value = @HttpConstraint(rolesAllowed = "member"), httpMethodConstraints = {@HttpMethodConstraint(value = "GET", emptyRoleSemantic = EmptyRoleSemantic.DENY)})
```

#### MultipartConfig

标注一个 Servlet 来指示该 Servlet 实例能够处理的 `multipart/form-data` 的 MIME 类型，在上传文件时通常会用到

* `fileSizeThreshold`

  当文件大小超过指定的大小后将写入到硬盘上

* `location`

  文件保存在服务端的路径

* `maxFileSize`

  允许上传的文件最大值。默认值为 -1，表示没有限制

* `maxRequestSize`

  针对该 `multipart/form-data` 请求的最大数量，默认值为 -1，表示没有限制

#### ServletSecurity

标注一个 Servlet 类在 Servlet 的应用安全约束。出现在 `ServletSecurity` 注解的属性

* `httpMethodConstrains` 

  HTTP 方法的特定限制数组

* `value`

  `HttpConstraint` 定义了应用到没有在 `httpMethodConstraints` 返回的数组中表示的所有 HTTP 方法的保护

#### WebFilter

用于标注一个Filter

* `asyncSupported` 

  是否支持异步处理

* `description`

  描述信息

* `dispatcherTypes`

  指定过滤器的转发模式。具体取值包括：`ASYNC`、`ERROR`、`FORWARD`、`INCLUDE`、`REQUEST`

* `displayName`

  显示名

* `filterName`

  名称

* `initParams`

  初始化参数

* `largeIcon`

  大图

* `ServletNames`

  指定过滤器将应用于那些 Servlet 取值是 `@WebServlet` 中的 `name` 属性的取值或者是 `web.xml` 中 `<servlet-name>` 的取值

* `smallIcon`

  小图

* `urlPatterns`

  URL匹配模式

* `value`

  URL匹配模式，与 `urlPatterns` 不能同时使用

#### WebInitParam

用于传递初始化参数到一个 `Servlet` 或过滤器。

* description

  参数描述

* name

  初始化参数名

* value

  初始化参数值

#### WebListener

标注一个 Listener，唯一属性为 value 是可选的，包括该 Listener 的描述

#### WebServlet

标注一个 Servlet

* `asyncSupported`

  是否支持异步处理

* `descriptiion`

  描述信息

* `displayName`

  显示名

* `initParams`

  初始化参数组

* `largeIcon`

  大图

* `loadOnStartup`

  加载顺序

* `name`

  名称

* `smallIcon`

  小图

* `urlPatterns`

  URL 匹配模式

* `Value`

  URL匹配模式，与 `urlPatterns` 不能同时使用