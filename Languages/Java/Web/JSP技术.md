## Java Server Pages

#### JSP 概述

JSP 页面本质上是一个 Servlet。用 JSP 页面开发比使用 Servlet 更容易，不必编译 JSP 页面；JSP 页面是以一个以 `.jsp` 为扩展名的文本文件，可以使用任何文本编辑器来编写它们。JSP 页面在 JSP 容器中运行，一个 Servlet 容器通常也是 JSP 容器。当一个 JSP 页面第一次被请求时，Servlet/JSP 容器主要做以下两件事：

* 转换 JSP 页面到 JSP 页面实现类，该实现类是一个实现 `javax.servlet.jsp.JspPage` 接口或子接口 `javax.servlet.jsp.HttpJspPage` 的 Java 类。`JspPage` 是 `javax.servlet.Servlet` 的子接口，这使得每一个 JSP 页面都是一个 Servlet。该实现类的类名由 `Servlet/JSP` 容器生成。如果出现转换错误，则相关错误信息将被发送到客户端
* 如果转换成功，`Servlet/JSP` 容器随后编译该 `Servlet` 类，并装载和实例化该类，像其他正常的 `Servlet` 一样执行生命周期操作

Tomcat 把 JSP 生成的 Servlet 源文件和类文件放在 `<CATALINA_HOME>/work/[engine]/[host]/[app]/org/apache/jsp`，虽然理论上 JSP 和 Servlet 能完成同样的功能，但由于它们形式不一样。（JSP 允许直接包含 HTML 标记，Servlet 是存储的 Java 程序）。JSP 技术的出现，使得把 Web 应用中 HTML 文档和业务逻辑代码有效分离成为可能。通常，JSP 负责生成 HTML 文档，业务逻辑由其他可重用的组件，如 JavaBean 或其他 Java 程序来实现。JSP 可通过 Java 程序片段来访问这些业务组件

对于同一个 JSP 页面的后续请求，Servlet/JSP 容器会先检查 JSP 页面是否被修改过。如果是，则该 JSP 页面会被重新翻译，编译并执行。如果不是，则执行已经在内存中的 JSP Servlet。一个 JSP 页面的第一次调用的实际花费总比后来的 花费多，因为它涉及翻译和编译。为了解决这个问题，可以执行下了动作之一：

* 配置应用程序，使所有的 JSP 页面在应用程序启动时被调用，而不是在第一次请求时调用
* 预编译 JSP 页面，并将其部署为 Servlet

JSP 自带的 API 包含 4 个包：

* `javax.servlet.jsp` 包含用于 `Servlet/JSP` 容器将 JSP 页面翻译为 `Servlet` 的核心类和接口。其中的两个重要成员为 `JspPage` 和 `HttpJspPage` 接口。所有的 JSP 页面实现类必须实现 `JspPage` 或 `HttpJspPage` 接口。
* `javax.servlet.jsp.tagext` 包括用于开发自定义标签的类型
* `javax.el` 提供统一表达式语言的 API
* `javax.servlet.jsp.el` 提供了一组必须由 `Servlet/JSP` 容器支持，以在 JSP 页面中使用表达式语言的类

#### JSP 语法

虽然 JSP 本质上就是 Servlet，但 JSP 有着不同于 Java 编程语言的专门的语法，该语法的特点是，尽可能地用标记来取代 Java 程序代码，使整个 JSP 文件在形式上不像 Java 程序，像标记文档：

JSP 文件中除了可直接包含 HTML 文本，还可以包含以下内容：

* JSP 指令（或称为指示语句）
* JSP 声明
* Java 程序片段（Scriptlet）
* Java 表达式
* JSP 隐含对象

##### JSP 指令

指令是 JSP 语法元素的第一种类型。它们指示 JSP 转换器如何翻译 JSP 页面为 Servlet。常用的三种指令 page，include，taglib。

```jsp
<%@ attribute="value" %>
```

###### page 指令

page 指令可以指定所使用的编程语言、与 JSP 对应的 Servlet 所实现的接口、所扩展的类以及导入的软件包等。page 指令的语法：

```
<%@ page attribute="value" attribute="value" %>
```

@ 和 page 间的空格不是必须的，attribute 是 page 指令的属性，page 指令属性列表：

|        page attribute         |                         description                          |
| :---------------------------: | :----------------------------------------------------------: |
|           language            |   定义本文件语言，仅支持(默认) Java，多次定义以第一次为准    |
|            method             | 指定 Java 程序片段所属的方法名称，Java 程序片段回称为指定方法的主体。默认方法 service()。多次使用指令，只有第一次有效。有效值：service, doGet,doPost 等 |
|            import             | 定义导入的 Java 软件包名或类名列表。用 `,` 分割。默认导入：java.lang, javax.servlet, javax.servlet.http, javax.servlet.jsp |
|          contentType          | 指定响应 MIME 类型，默认 text/html，ISO 8859-1，多次指定时，仅第一次生效 |
|            session            | 指定 JSP 页是否使用 session，默认 true 访问该页面时，若当前不存在 `javax.servlet.http.HttpSession` 实例，则会创建一个 |
|            buffer             | 以 KB 为单位，定义隐式对象 out 的缓冲大小。必须以 KB 后缀结尾。默认大小为 8 KB 或更大（取决于 JSP 容器）。该值可为 none，即无缓冲，所有数据将直接写入 `PrintWriter` |
|           autoFlush           | 默认为 true。当输出缓冲满时会自动写入输出流。为 false，则仅当调用隐式对象的  flush   方法时，才会写入输出流。因此，若缓冲溢出，则会抛出异常 |
|           errorPage           |                       定义错误处理页面                       |
|          isErrorPage          |               此 JSP 页面是否为处理异常的页面                |
|         isThreadSafe          | 定义该页面的线程安全级别。不推荐使用，使用该参数，会生成一些 Servlet 容器已过期的代码 |
|             info              |  返回调用容器生成的 Servlet 类的 getServletInfo 方法的结果   |
|         pageEncoding          |           定义本页面的字符编码，默认是 ISO-8859-1            |
|          isELIgnored          |           配置是否忽略 Expression Language 表达式            |
|            extends            |      定义 JSP 实现类要继承的父类。这个属性的一般不使用       |
| deferredSyntaxAllowdAsLiteral |         定义是否解析字符串中出现 `#{`，默认是 false          |
|   trimDirectiveWhitespaces    |         定义是否不输出多余的空格/空行，默认是 false          |

大部分 page 指令可以出现在页面的任何位置，但当 page 指令包含 `contentType` 或 `pageEncoding` 属性时，其必须出现在 Java 代码发送任何内容之前。因为内容类型和字符编码必须在发送任何内容前设定。page 指令也可以出现多次，但出现多次的指令属性必须具有相同的值（除 import 属性，多个包含 import 属性的 page 指令的结果是累加的）。

###### include 指令

可以使用 include 指令将其他文件中的内容包含到当前 JSP 页面。一个页面中可以有多个 include 指令。若存在一个内容会在多个不同页面中使用或一个页面不同位置使用的场景，则将该内容模块化到一个 include 文件非常有用

```jsp
<%@ include file="url" %>
```

URL 为被包含文件的相对路径，若 URL 以一个斜杠 `/` 开始，则该 URL 为文件在服务器上的绝对路径，否则为当前 JSP 页面的相对路径。JSP 转换器处理 `include` 指令时，将指令替换为指令所包含文件的内容。

##### 脚本元素 scripting element

###### Java 程序片段 Scriptlet

在 JSP 文件中，可以在 `<% %>` 标记间直接嵌入任何有效的 Java 程序代码。这种嵌入的程序片段为 `Scriptlet`。如果在 page 指令中没有指定 method 属性，那么这些程序片段默认是属于与 JSP 对应的 Servlet 类的 service() 方法中的代码块

```jsp
<% if (gender.equals("female")) { %>
She is a girl
<% }else{ %>
He is body
<% } %>
```

###### Java 表达式

Java 表达式的标记为 `<%= %>` 。如果在 JSP中使用该标记，将使用隐式对象 out 的打印方法输出结果。在表达式中，`int` 或 `float` 类型的值都自动转换成字符串再进行输出。每个表达式都会被 JSP 容器执行，表达式无须分号结尾。

表达式可以直接插入到模板文件中，也可以作为 JSP 标签属性的值

```jsp
<!-- 输出 -->
<%=hitCount %>
Today is <%=java.util.Calendar.getInstance().getTime()%>
```

###### 声明

可以声明能在 JSP 页面中使用的变量和方法。声明以 `<%!` 开始，以 `%>` 结束。在 JSP 页面中，一个声明可以出现在任何地方，并且一个页面可以有多个声明。可以使用声明来重写 JSP 页面，实现类的 init 和 destroy 方法。通过声明 jspInit 方法，来重写 init 方法。通过声明 jspDestroy 方法，来重写 destory 方法。

```jsp
<!-- 实例变量 -->
<%! int hitCount %>
<!-- 局部变量 -->
<% int count=0 %>
```

* jspInit

  类似于 `javax.servlet.Servlet` 的 `init` 方法。JSP 页面在初始化时调用 `jspInit`。不同于 `init` 方法，`jspInit` 没有参数。还可以通过隐式对象 `config` 访问 `ServletConfig` 对象

* jspDestroy

  类似 Servlet 的 `destroy` 方法，在 JSP 页面将被销毁时调用

```jsp
<%!
    public void jspInit() {
        System.out.println("jspInit...");
    }
    public void jspDestroy() {
        System.out.println("jspDestroy...");
    }
%>
```

###### 禁用脚本元素

推荐的实践是：在 JSP 页面中用 EL 访问服务器端对象且不写 Java 代码。从 JSP 2.0 起，可以通过在部署描述符中的 `<jsp-property-group>` 定义一个 `scripting-invalid` 元素，来禁用脚本元素

```xml
<jsp-property-group>
    <url-pattern>*.jsp</url-pattern>
    <scription-invalid>true</scripting-invalid>
</jsp-property-group>
```

##### 动作元素 action element

以 `jsp` 作为前缀的标签被转换成 Java 代码来执行操作，如访问一个 Java 对象或调用方法。除标准外，还可以创建自定义标签执行某些操作。

把 Java 程序代码放到 JavaBean 中，然后再 JSP 文件中通过简洁的 JSP 标签来访问 JavaBean，能简化 JSP 代码：

* 使得 HTML 与 Java 程序分离，便于维护，如果把所有的代码都写道 JSP 网页中，会使得代码繁杂，难以维护
* 降低开发 JSP 网页的人员对 Java 编程能力的妖气
* JSP 侧重于生成动态网页，事务处理由 JavaBean 来完成，这样可以充分利用 JavaBean 组件的可重用性特点，提高网站的效率

###### JavaBean

JavaBean 是一种可重复使用，且跨平台的软件组件。JavaBean 可分为两种：一种是有用户界面的 JavaBean；一种是没有用户界面，主要负责表示业务数据或处理事务（如数据预算，操作数据库）的 JavaBean，JSP 通常访问的是后一种 JavaBean。标准的 JavaBean 有以下特性：

* JavaBean 是一个公共（public）类
* JavaBean 有一个不带参数的构造方法
* JavaBean 通过 set 方法设置属性，通过 get 方法获取属性
* 如果希望 JavaBean 能被持久化，那么可以使它实现 `java.io.Serializable` 接口。

###### JSP 访问 JavaBean 的语法

在 JSP 网页中，既可以通过程序代码来访问 JavaBean，也可以通过特定的 JSP 标签来访问 JavaBean。

* JavaBean 的 JSP 标签

  1. 导入 JavaBean 类，如果在 JSP 网页中访问 JavaBean，首先通过 `<%@ page import>` 指令引入 JavaBean 类

  2. 声明 JavaBean 对象，`<jsp: useBean>` 标签来声明 JavaBean 对象

     *<jsp:useBean>标签属性*

     | 属性  |                             描述                             |
     | :---: | :----------------------------------------------------------: |
     |  id   | 代表 JavaBean 对象的 ID，实际上表示引用 JavaBean 对象的局部变量名，以及存放在特定范围内的属性名。JSP 规范要求存放在所有范围内的每个 JavaBean 对象都有唯一的 ID。 |
     | class |                    用于指定 JavaBean 类名                    |
     | scope | 指定 JavaBean 对象的存放范围，可选值包括：page（页面）、request（请求）、session（会话）、application（web应用），默认 page |

  3. 访问 JavaBean 属性，将该属性输出到网页上 `<jsp:getProperty>`；赋值 `<jsp:setProperty>`

     ```java
     // 引入 JavaBean 类
     <%@ page import="page.name.JavaBeanClassName" %>
     // 声明 JavaBean 对象
     <jsp:useBean id="myBean" class="page.name.JavaBeanClassName" scope="session" />
     // 输出属性
     <jsp:getProperty name="myBean" property="count" />
     // 属性赋值
     <jsp:setProperty name="myBean" property="count" value="1" />
     ```

* Java 程序片段

  等价于上述 JSP 标签

  ```jsp
  <%
      page.name.JavaBeanClassName myBean = null;
      myBean = (page.name.JavaBeanClassName) session.getAttribute("myBean");
      if (myBean == null) {
          myBean = new page.name.JavaBeanClassName();
          session.setAttribute("myBean", myBean);
      }
  %>
  <%=myBean.getCount()%>
  <%
  	myBean.setCount(1);
  %>
  ```

###### include

include 动作用来动态地引入另一个资源，可以引入另一个 JSP 页面，也可以引入一个 Servlet 或一个静态的 HTML 页面。对于 include 指令，资源引入发生在页面转换时，即当 JSP 容器将页面转换为生成的 Servlet 时。而对于 include 动作，资源引入发生在请求页面时，include 动作可以传递参数，而 include 指令不能；include 指令对引入的文件扩展名不做特殊要求。但对于 include 动作，若引入的文件需以 JSP 页面处理，则其文件扩展名必须是 JSP。若使用 `.jspf` 为扩展名，则该页面被当作静态文件

```jsp
<jsp:include page="/url" />
```

###### forward

forward 标签实现请求转发，转发的目标可以为 HTML，JSP，Servlet。

```jsp
<jsp:forward page="/url" />
```

JSP 源组件中 forward 标签后的代码不会执行。规则与 Servlet 的 

##### 隐含对象

*JSP中的隐含对象*

|  隐含对象   |              隐含对象类型              |
| :---------: | :------------------------------------: |
|   request   |  javax.servlet.http.HttpServletRequst  |
|  response   | javax.servlet.http.HttpServletResponse |
| pageContext |     javax.servlet.jsp.PageContext      |
| application |      javax.servlet.ServletContext      |
|     out     |      javax.servlet.jsp.JspWriter       |
|   config    |      javax.servlet.ServletConfig       |
|    page     |            java.lang.Object            |
|   session   |     javax.servlet.http.HttpSession     |
|  exception  |          java.lang.Exception           |

#### JSP 生命周期

JSP 的生命周期包含以下阶段：

* 解析阶段：Servlet 容器解析 JSP 文件的代码，如果有语法错误，就会向客户端返回错误信息
* 翻译阶段：Servlet 容器把 JSP 文件翻译成 Servlet 源文件
* 编译阶段：Servlet 容器编译 Servlet 源文件，生成 Servlet 类
* 初始化阶段：加载与 JSP 对应的 Servlet 类，创建其实例，并调用它的初始化方法
* 运行时阶段：调用与 JSP 对应的 Servlet 实例的服务方法
* 销毁阶段：调用与 JSP 对应的 Servlet 实例的销毁方法，然后摧毁 Servlet 实例

解析、翻译、编译阶段仅发生在：

* JSP 文件被客户端首次请求访问
* JSP 文件被更新
* 与 JSP 文件对应的 Servlet 类的类文件被手动删除

#### 错误处理

JSP 提供良好的错误处理能力，除了在 Java 代码中使用 try 语句，还可以指定一个特殊页面。当页面遇到未捕获的异常时，将显示该页面。使用 page 指令的 `isErrorPage` 属性（属性值必须为 True）来标识一个 JSP 页面是错误页面。其他需要防止未捕获的异常的页面使用 page 指令的 errorPage 属性来指向错误处理页面

```jsp
<!-- 转发错误 -->
<%@ page errorPage="errorpage.jsp" %>
<!-- 声明异常处理 -->
<%@ page isErrorPage="true" %>
<!-- 处理异常的网页可以直接访问 exception 隐含对象，获取当前异常的详细信息 -->
<p>
    错误原因：<% exception.printStackTrace(new PrintWriter(out)); %>
</p>
```

#### 配置

也可以在 `web.xml` 为 JSP 配置 `<servlet>` 和 `<servlet-mapping>` 元素

```xml
<servlet>
	<servlet-name>hi</servlet-name>
    <jsp-file>/hello.jsp</jsp-file>
</servlet>
<servlet-mapping>
	<servlet-name>hi</servlet-name>
    <url-pattern>/hi</url-pattern>
</servlet-mapping>
```

在 web.xml 中，可以用 `<jsp-config>` 元素来对一组 JSP 文件进行配置，包括 `<taglib>` 和 `<jsp-property-group>` 子元素

*jsp-property-group子元素*

|       子元素        |               描述               |
| :-----------------: | :------------------------------: |
|    <url-pattern>    |      设置该配置所影响的 JSP      |
|    <description>    |          对 JSP 的描述           |
|   <display-name>    |          JSP 的显示名字          |
|    <el-ignored>     |     为 true，不支持 EL 语法      |
| <scripting-invalid> | true，不支持 <% %> Java 程序片段 |
|   <page-encoding>   |     设定 JSP  文件的字符编码     |
|  <include-prelude>  | 设置自动包含 JSP 页面的头部文件  |
|   <include-coda>    | 设置自动包含 JSP 页面的结尾文件  |

```xml
<jsp-config>
	<jsp-property-group>
    	<description>
        	this is description
        </description>
        <display-name>someePage</display-name>
        <url-pattern>*.jsp</url-pattern>
        <el-ignored>true</el-ignored>
        <page-encoding>UTF-8</page-encoding>
        <scripting-invalid>true</scripting-invalid>
        <include-prelude>/include/head.jsp</include-prelude>
        <include-coda>/include/foot.jsp</include-coda>
    </jsp-property-group>
</jsp-config>
```

#### JSP 会话

默认情况下，JSP 网页都是支持会话的，也可以通过以下语句显示声明支持会话

```java
<%@ page session="true" %>
```

在 JSP 文件中可以直接通过固定变量 `session` 来引用隐含的 `HttpSession` 对象