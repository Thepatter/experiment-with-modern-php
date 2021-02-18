### Java Server Pages

#### 执行

##### 请求流程

jsp 页面本质上是一个 Servlet。用 jsp 页面开发比使用 Servlet 更容易，不必编译 jsp 页面；jsp 页面是以一个以 .jsp 为扩展名的文本文件。jsp 页面在 jsp 容器中运行，一个 Servlet 容器通常也是 jsp 容器。当一个 jsp 页面第一次被请求时，Servlet/JSP 容器主要做以下两件事：

* 转换 jsp 页面到 jsp 页面实现类，该实现类是一个实现 javax.servlet.jsp.JspPage 接口或子接口 javax.servlet.jsp.HttpJspPage 的 java 类。

  javax.servlet.jsp.JspPage 是 javax.servlet.Servlet 的子接口，这使得每一个 JSP 页面都是一个 Servlet。该实现类的类名由 Servlet/JSP 容器生成。如果出现转换错误，则相关错误信息将被发送到客户端

* 如果转换成功，Servlet/JSP 容器随后编译该 Servlet 类，并装载和实例化该类，像其他正常的 Servlet 一样执行生命周期操作

Tomcat 将 jsp 转换的源文件和类文件存放在：`<CATALINA_HOME>/work/[engine]/[host]/[app]/org/apache/jsp`

虽然理论上 jsp 和 Servlet 能完成同样的功能，但由于它们形式不一样。（jsp 允许直接包含 HTML 标记，Servlet 是存储的 java 程序）。JSP 技术的出现，使得把 Web 应用中 HTML 文档和业务逻辑代码有效分离成为可能。通常，JSP 负责生成 HTML 文档，业务逻辑由其他可重用的组件，如 JavaBean 或其他 Java 程序来实现。JSP 可通过 Java 程序片段来访问这些业务组件

对于同一个 jsp 页面的后续请求，Servlet/JSP 容器会先检查 jsp 页面是否被修改过。如果是，则该 jsp 页面会被重新翻译，编译并执行。如果不是，则执行已经在内存中的 JSP Servlet。一个 jsp 页面的第一次调用的实际花费总比后来的花费多，因为它涉及翻译和编译。为了解决这个问题，可以执行下了动作之一：

* 配置应用程序，使所有的 jsp 页面在应用程序启动时被调用，而不是在第一次请求时调用
* 预编译 jsp 页面，并将其部署为 Servlet

JSP 自带的 API 包含 4 个包：

* `javax.servlet.jsp` 

  包含用于 Servlet/JSP 容器将 JSP 页面翻译为 Servlet 的核心类和接口。其中的两个重要成员为 JspPage 和 HttpJspPage 接口。所有的 JSP 页面实现类必须实现 JspPage 或 HttpJspPage 接口。

* `javax.servlet.jsp.tagext`

  包括用于开发自定义标签的类型

* `javax.el`

  提供统一表达式语言的 API

* `javax.servlet.jsp.el`

  提供了一组必须由 Servlet/JSP 容器支持，以在 JSP 页面中使用表达式语言的类

##### JSP 生命周期

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

##### 错误处理

JSP 提供良好的错误处理能力，除了在 Java 代码中使用 try 语句，还可以指定一个特殊页面。当页面遇到未捕获的异常时，将显示该页面。使用 page 指令的 isErrorPage 属性（属性值必须为 True）来标识一个 JSP 页面是错误页面。其他需要防止未捕获的异常的页面使用 page 指令的 errorPage 属性来指向错误处理页面

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

##### 配置

也可以在 web.xml 为 JSP 配置 <servlet> 和 <servlet-mapping> 元素

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

在 web.xml 中，可以用 <jsp-config> 元素来对一组 JSP 文件进行配置，包括 <taglib> 和 <jsp-property-group> 子元素

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
    	  <description>this is description</description>
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

##### JSP 会话

默认情况下，JSP 网页都是支持会话的，也可以通过以下语句显示声明支持会话

```java
<%@ page session="true" %>
```

在 JSP 文件中可以直接通过固定变量 session 来引用隐含的 HttpSession 对象

#### 语法

虽然 jsp 本质上就是 Servlet，但 jsp 有着不同于 Java 编程语言的专门的语法，该语法的特点是，尽可能地用标记来取代 java 程序代码，使整个 jsp 文件在形式上不像 java 程序，像标记文档：

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
|            method             | 指定 Java 程序片段所属的方法名称，Java 程序片段称为指定方法的主体。默认方法 service()。多次使用指令，只有第一次有效。有效值：service，doGet，doPost 等 |
|            import             | 定义导入的 Java 软件包名或类名列表。用 , 分割。默认导入：java.lang, javax.servlet, javax.servlet.http, javax.servlet.jsp |
|          contentType          | 指定响应 MIME 类型，默认 text/html，ISO 8859-1，多次指定时，仅第一次生效 |
|            session            | 指定 JSP 页是否使用 session，默认 true 访问该页面时，若当前不存在 javax.servlet.http.HttpSession 实例，则会创建一个 |
|            buffer             | 以 KB 为单位，定义隐式对象 out 的缓冲大小。必须以 KB 后缀结尾。默认大小为 8 KB 或更大（取决于 JSP 容器）。该值可为 none，即无缓冲，所有数据将直接写入 *PrintWriter* |
|           autoFlush           | 默认为 true。当输出缓冲满时会自动写入输出流。为 false，则仅当调用隐式对象的  flush   方法时，才会写入输出流。因此，若缓冲溢出，则会抛出异常 |
|           errorPage           |                       定义错误处理页面                       |
|          isErrorPage          |               此 JSP 页面是否为处理异常的页面                |
|         isThreadSafe          | 定义该页面的线程安全级别。不推荐使用，使用该参数，会生成一些 Servlet 容器已过期的代码 |
|             info              | 返回调用容器生成的 Servlet 类的 getServletInfo() 方法的结果  |
|         pageEncoding          |           定义本页面的字符编码，默认是 ISO-8859-1            |
|          isELIgnored          |           配置是否忽略 Expression Language 表达式            |
|            extends            |      定义 JSP 实现类要继承的父类。这个属性的一般不使用       |
| deferredSyntaxAllowdAsLiteral |          定义是否解析字符串中出现 #{，默认是 false           |
|   trimDirectiveWhitespaces    |         定义是否不输出多余的空格/空行，默认是 false          |

大部分 page 指令可以出现在页面的任何位置，但当 page 指令包含 contentType 或 pageEncoding 属性时，其必须出现在 java 代码发送任何内容之前。因为内容类型和字符编码必须在发送任何内容前设定。

page 指令也可以出现多次，但出现多次的指令属性必须具有相同的值（除 import 属性，多个包含 import 属性的 page 指令的结果是累加的）。

###### include 指令

可以使用 include 指令将其他文件中的内容包含到当前 jsp 页面。一个页面中可以有多个 include 指令。若存在一个内容会在多个不同页面中使用或一个页面不同位置使用的场景，则将该内容模块化到一个 include 文件非常有用

```jsp
<%@ include file="url" %>
```

URL 为被包含文件的相对路径，若 URL 以一个斜杠 / 开始，则该 URL 为文件在服务器上的绝对路径，否则为当前 JSP 页面的相对路径。<u>JSP 转换器处理 include 指令时，将指令替换为指令所包含文件的内容</u>

##### 脚本元素 scripting element

###### Java 程序片段 Scriptlet

在 JSP 文件中，可以在 <% %> 标记间直接嵌入任何有效的 java 程序代码。这种嵌入的程序片段为 Scriptlet。如果在 page 指令中没有指定 method 属性，那么这些程序片段默认是属于与 JSP 对应的 *javax.servlet.Servlet* 类的 service() 方法中的代码块

```jsp
<% if (gender.equals("female")) { %>
She is a girl
<% }else{ %>
He is body
<% } %>
```

###### Java 表达式

Java 表达式的标记为 <%= %> 。如果在 JSP中使用该标记，将使用隐式对象 out 的打印方法输出结果。在表达式中，int 或 float 类型的值都自动转换成字符串再进行输出。每个表达式都会被 JSP 容器执行，表达式无须分号结尾。

表达式可以直接插入到模板文件中，也可以作为 JSP 标签属性的值

```jsp
<!-- 输出 -->
<%=hitCount %>
Today is <%=java.util.Calendar.getInstance().getTime()%>
```

###### 声明

可以声明能在 jsp 页面中使用的变量和方法。声明以 <%! 开始，以 %> 结束。在 jsp 页面中，一个声明可以出现在任何地方，并且一个页面可以有多个声明。可以使用声明来重写 jsp 页面，实现类的 init 和 destroy 方法。通过声明 jspInit 方法，来重写 init 方法。通过声明 jspDestroy 方法，来重写 destory 方法。

```jsp
<!-- 实例变量 -->
<%! int hitCount %>
<!-- 局部变量 -->
<% int count=0 %>
```

* jspInit

  类似于 *javax.servlet.Servlet* 的 init 方法。JSP 页面在初始化时调用 jspInit。不同于 init() 方法，jspInit 没有参数。还可以通过隐式对象 config 访问 *ServletConfig* 对象

* jspDestroy

  类似 *javax.servlet.Servlet* 的 destroy 方法，在 JSP 页面将被销毁时调用

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

推荐的实践是：在 jsp 页面中用 EL 访问服务器端对象且不写 java 代码。从 JSP 2.0 起，可以通过在部署描述符禁用脚本元素

```xml
<jsp-property-group>
    <url-pattern>*.jsp</url-pattern>
    <scription-invalid>true</scripting-invalid>
</jsp-property-group>
```

##### 动作元素 action element

以 jsp 作为前缀的标签被转换成 java 代码来执行操作，如访问一个 java 对象或调用方法。除标准外，还可以创建自定义标签执行某些操作。

把 java 程序代码放到 JavaBean 中，然后在 jsp 文件中通过简洁的 JSP 标签来访问 JavaBean，能简化 JSP 代码：

* 使得 HTML 与 Java 程序分离，便于维护，如果把所有的代码都写道 JSP 网页中，会使得代码繁杂，难以维护
* 降低开发 JSP 网页的人员的 Java 编程能力
* jsp 侧重于生成动态网页，事务处理由 JavaBean 来完成，这样可以充分利用 JavaBean 组件的可重用性特点，提高网站的效率

###### JavaBean

JavaBean 是一种可重复使用，且跨平台的软件组件。JavaBean 可分为两种：一种是有用户界面的 JavaBean；一种是没有用户界面，主要负责表示业务数据或处理事务（如数据预算，操作数据库）的 JavaBean，JSP 通常访问的是后一种 JavaBean。标准的 JavaBean 有以下特性：

* JavaBean 是一个公共（public）类
* JavaBean 有一个不带参数的构造方法
* JavaBean 通过 set 方法设置属性，通过 get 方法获取属性
* 如果希望 JavaBean 能被持久化，那么可以使它实现 java.io.Serializable 接口。

###### JSP 访问 JavaBean 的语法

在 jsp 网页中，既可以通过程序代码来访问 JavaBean，也可以通过特定的 JSP 标签来访问 JavaBean。

* JavaBean 的 JSP 标签

  1. 导入 JavaBean 类，如果在 jsp 网页中访问 JavaBean，首先通过 <%@ page import> 指令引入 JavaBean 类

  2. 声明 JavaBean 对象，<jsp: useBean> 标签来声明 JavaBean 对象

     <jsp:useBean>标签属性

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

include 动作用来动态地引入另一个资源，可以引入另一个 JSP 页面，也可以引入一个 Servlet 或一个静态的 HTML 页面。<u>对于 include 指令，资源引入发生在页面转换时，即当 JSP 容器将页面转换为生成的 Servlet 时。而对于 include 动作，资源引入发生在请求页面时，include 动作可以传递参数，而 include 指令不能；include 指令对引入的文件扩展名不做特殊要求。但对于 include 动作，若引入的文件需以 JSP 页面处理，则其文件扩展名必须是 JSP。若使用 .jspf 为扩展名，则该页面被当作静态文件</u>

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

#### EL 表达式

JSP 2.0 支持表达式语言（EL），JSP 用户可以用它来访问应用程序数据。用来替代传统的基于 `<%=%>` 的表达式，以及部分 <%%> 形式程序片段

##### 表达式语法

###### 基本形式及操作符 

EL 表达式基本形式为：`${ var }` 。对于一系列的表达式，它们的取值将是从左到右进行，计算结果的类型为 String，并且连接在一起。如果在定制标签的属性值中使用 EL 表达式，那么该表达式的取值结果字符串将会强制变成该属性需要的类型。

* 表达式关键字

  `and`, `eq`, `gt`, `true`, `instanceof`, `or`, `ne`, `le`, `false`, `empty`, `not`, `lt`, `ge`, `null` `div`, `mod`

* `[]` 和 `.` 运算符

  EL 表达式可以返回任意类型的值。如果 EL 表达式的结果是一个带有属性的对象，则可以利用 `[]` 或者 `.` 运算符来访问该属性，如果 `propertyName` 不是有效的 Java 变量名，只能使用 `[]` 运算符。如果对象的属性是带有属性的另一个对象，则可用 `[]` 或 `.` 来访问对象属性对象的属性。

* 算术运算符

  加（`+`）减（`-`）、乘（`*`）、除（`/` 或 `div`）、取模（`%` 或 `mod`）

* 逻辑运算符

  `&&`、`and`、`||`、`or`、`!`、`not`

* 关系运算符

  （`==` 或 `eq`）、（`!=` 或 `ne`）、（`>` 或 `gt`)、（`>=` 或 `ge`）、（`<` 或 `lt`）、（`<=` 或 `le`）

* empty 运算符

  empty 运算符用来检查某一个值是否为 null 或者 empty：`${empty x}` 如果 X 为 null，或者 x 是一个长度为 0 的字符串，该表达式返回 true。x 是一个空 Map、空数组或者空集合、返回 true，否则返回 false

* 条件运算符

  `a ? b : c`

###### 表达式取值规则

  EL 表达式的取值是从左到右进行的。对于 `expr-a[expr-b]` 形式的表达式，其 EL 表达式的取值方法如下： 

  * 先计算 `expr-a` 得到 `value-a`

  * 如果 `value-a` 为 `null`，则返回 `null`，

  * 然后计算 `expr-b` 得到 `value-b`

  * 如果 `value-b` 为 `null`，则返回 `null`

  * 如果 `value-a` 为 `java.util.Map`，则会查看 `value-b` 是否为 `Map` 中的一个 `key`。若是，则返`value-a.get(value-b)`，若不是，则返回 `null`；

  * 如果 `value-a` 为 `java.util.List`，或者假如它是一个 `array`，则进行如下处理：

    强制 `value-b` 为 `int`，如果强制失败，则抛出异常

    如果 `value-a.get（value-b)` 抛出 `IndexOutOfBoundsException`，或者抛出 `ArrayIndexOutOfBoundsException`，则返回 null

    如 `value-a` 是一个 List，则返回 `value-a.get(value-b)`，若 `value-a` 是一个 `array`，则返回 `Array.get(value-a, value-b)`

  * 如果 `value-a` 不是一个 `Map`, `List` 或 `Array`，则 `value-a` 必须是一个 `JavaBean`。此时，必须强制 `value-b` 为 `String`。如果 `value-b` 是 `value-a` 的一个可读属性，则要调用该属性的 `getter` 方法，从中返回值。如果 `getter` 方法抛出异常，该表达式就是无效的，否则，该表达式有效

###### 访问 JavaBean

  使用 `.` 或 `[]` 来访问 bean 属性及属性对象的属性

###### EL 隐式对象

在 JSP 页面中，可以利用 JSP 脚本来访问 JSP 隐式对象。但是，在免脚本的 JSP 页面中，则不可能访问这些隐式对象。EL 允许通过提供一组它自己的隐式对象来访问不同的对象

* `pageContent`

`pageContext` 对象表示当前 JSP 页面的 `javax.servlet.jsp.PageContext`。包含所有其他 JSP 隐式对象，JSP 隐式对象对应 EL 中类型

`PageContext` 类提供了一组用于向各种范围内存取属性的方法，scope 值为以下四个常量：`PageContext.PAGE_SCOPE`，`PageContext.REQUEST_SCOPE`，`PageContext.SESSION_SCOPE`，`PageContext.APPLICATION_SCOPE`

* `getAttribute(String name)`：返回页面范围内的特定属性的值。
* `getAttribute(String name，int scope)`：返回参数 scope 指定的范围内的特定属性的值。
* `setAttribute(String name，Object value，int scope)`：向参数 scope 指定的范围内存放属性。
* `removeAttribute(String name，int scope)`：从参数 scope 指定的范围内删除特定属性。
* `findAttribute(String name)`：依次从页面范围、请求范围、会话范围和Web应用范围内寻找参数 name 指定的属性，如果找到，就立即返回该属性的值。如果所有的范围内都不存在该属性，就返回 null 。
* `int getAttributesScope(java.lang.String name)`：返回参数指定的属性所属的范围，如果所有的范围内都不存在该属性，就返回 0。

用于获得由 Servlet 容器提供的其他对象的引用的方法，`PageContext` 类的以下方法用于获得由 Servlet 容器提供的`ServletContext`、`HttpSession`、`ServletRequest` 和 `ServletResponse` 等对象：

* `getPage()`：返回与当前 JSP 对应的 Servlet 实例。
* `getRequest()`：返回 `ServletRequest` 对象。
* `getResponse()`：返回 `ServletResponse` 对象。
* `getServletConfig()` ：返回 `ServletConfig` 对象。
* `getServletContext()` ：返回 `ServletContext` 对象。
* `getSession()`：返回 `HttpSession` 对象。
* `getOut()`：返回一个用于输出响应正文的 `JspWriter` 对象。

* `initParam`

  获取上下文参数的值，包含所有环境初始化参数 Map 对象

* `param`

  获取请求参数 Map 每个 `key` 的值就是指定名称的第一个参数值。如果两个请求参数同名，则只有第一个能够利用 `param` 取值。用 `params()` 访问同名参数的所有参数值

* `paramValues`

  获取请求参数的所有值的 Map，参数名称为 key，值为字符串数组，包含对应 key 的所有值，如果该 key 对应只有一个 值，返回一个元素的数组

* `header`

  包含请求 header，并用 header 名作为 key 的 Map，每个 key 的值就是指定标题名称的第一个标题。如果一个标题的值不止一个，则只返回第一个值。获得多个值的标题，需用 `headerValues` 对象替代

* `headerValues`

  包含请求标题，并用标题名作为 key 的 Map。每个 key 的值就是一个字符串数组，其中包含了指定标题名称的所有参数值

* `cookie`

  包含当前 `HttpServletRequest` 中所有 Cookie 对象的 Map。Cookie 名称就是 key 名称，并且每个 key 都映射到一个 Cookie 对象。 `${cookie.jsessionid.value}`

* `applicationScope`

  包含web应用中所有属性的 Map，并用属性名称作为 key

* `sessionScope`

  包含 `HttpSession` 对象中所有属性的 Map，并用属性名称作为 key

* `requestScope`

  包含了当前 `HttpServletRequest` 对象中的所有属性，属性名为 key 的 Map

* `pageScope`

  包含全页面范围内的所有属性，属性名称为 key 的 Map

###### 命名变量

EL 表达式中的变量称为命名变量， 它不是 JSP 文件中的局部变量或实例变量，而是存放在特定范围内的属性，命名变量的名字和特定范围内的属性名对象

###### 定义和使用 EL 函数

EL 表达式语言可以访问 EL 函数。EL 函数实际上与 Java 类中的方法对应。这个 Java 类必须定义为 public 类型，并且作为函数的方法必须声明为 public static 类型。Java 类型定义好后，在标签库描述符 TLD 文件中，把 Java 类的方法映射为函数。使用 EL 表达式流程为：

1. 定义需要 Java 类及声明方法，将编译后的 class 文件放到 web 目录 classes 目录下

2. 定义标签库描述符 TLD 文件

   *`mytaglib.tld`*

   ```xml
   <?xml version="1.0" encoding="ISO-8859-1" ?>
   <taglib xmlns="http://java.sun.com/xml/ns/j2ee"
           xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
           xsi:schemaLocation="http://java.sun.com/xml/ns/j2ee web-jsptaglibrary_2_0.xsd"
           version="2.0">
       <tlib-version>1.1</tlib-version>
       <short-name>mytaglib</short-name>
       <uri>/mytaglib</uri>
   	<!-- 声明函数 -->
       <function>
           <name>add</name>
           <function-class>web.el.Tool</function-class>
           <function-signature>int add(java.lang.String,java.lang.String)</function-signature>
       </function>
       <function>
           <name>convert</name>
           <function-class>web.el.Tool</function-class>
           <function-signature>java.lang.String covert(java.lang.String)</function-signature>
       </function>
   </taglib>
   ```

   将 `mytaglib.tld` 文件放在 WEB-INF 目录下

3. 在部署描述符中加入 `<taglib>` 元素

   ```xml
   <?xml version="1.0" encoding="UTF-8"?>
   <web-app xmlns="http://xmlns.jcp.org/xml/ns/javaee"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://xmlns.jcp.org/xml/ns/javaee
                         http://xmlns.jcp.org/xml/ns/javaee/web-app_4_0.xsd"
            version="4.0" >
       <jsp-config>
           <taglib>
               <taglib-uri>/mytaglib</taglib-uri>
               <taglib-location>/WEB-INF/mytaglib.tld</taglib-location>
           </taglib>
       </jsp-config>
   </web-app>
   ```

4. 在 JSP 文件中声明并引用

   ```jsp
   // 声明
   <%@ taglib prefix="mf" uri="/mytaglib" %>
   // 使用函数
   <p>value: ${mf:convert(param.user)}</p>
   <p>add: ${mf:add(param.x, param.y)}</p>
   ```

##### 配置 EL

###### 免脚本的 JSP 页面

要关闭 JSP 页面中的脚本元素，要使用 `jsp-property-group` 元素以及 `url-pattern` 和 `scripting-invalid` 两哥子元素，`url-pattern` 元素定义禁用脚本要应用的 URL 样式

```xml
// 将应用程序中所有 JSP 页面的脚本关闭
<jsp-config>
    <jsp-property-group>
        <url-pattern>*.jsp</url-pattern>
        <scripting-invalid>true</scripting-invalid>
    </jsp-property-group>
</jsp-config>    
```

在部署描述符中只能有一个 `jsp-config` 元素。如果已经为禁用 EL 而定义了一个 `jsp-property-group`，就必须在同一个 `jsp-config` 元素下，为禁用脚本而编写 `jsp-property-group`

###### 禁用 EL 计算

当需要在 JSP 2.0 及其更高版本的容器中部署 JSP1.2 应用程序时，可能需要禁用 JSP 页面中的 EL 计算。此时，一旦出现 EL 架构，就不会作为一个 EL 表达式进行计算。目前有两种方式可以禁用 JSP 中的 EL 计算。

* 将 page 指令的 `isELlgnored` 属性设为 true

  ```jsp
  <%@ page isELIgnored="true" %>
  ```

  `isELIgnored` 属性的默认值为 false，如果想在一个或者几个 JSP 页面中关闭 EL 表达式计算，建议使用 `isELIgnored` 属性

* 在部署描述符中使用 `jsp-property-group` 元素，`jsp-property-group` 元素时 `jsp-config` 元素的子元素。利用 `jsp-property-group` 可以将某些设置应用到应用程序中的一组 JSP 页面中，为了利用 `jsp-property-group` 元素禁用 EL 计算，还必须有 `url-pattern` 和 `el-ignored` 两个子元素。`url-pattern` 元素用于定义 EL 禁用要应用的 URL 样式。`el-ignored` 元素必须设为 true

  ```xml
  <jsp-config>
      <jsp-property-group>
          <url-pattern>*.jsp</url-pattern>
          <el-ignored>true</el-ignored>
      </jsp-property-group>
  </jsp-config>
  ```

无论是将其 page 指令的 `isELIgnored` 属性设为 true，还是将其 URL 与子元素 `el-ignored` 设为 true 的 `jsp-property-group` 元素中的模式相匹配，都将禁用 JSP 页面中的 EL 计算。如果将一个 JSP 页面中的 page 指令的 `isELIgnored` 属性设为 false，但其 URL 与在部署描述符中禁用了 EL 计算的 JSP 页面的模式匹配，那么该页面的 EL 计算也将被禁用。如果使用的是与 Servlet2.3 及其更低版本兼容的部署描述符，那么 EL 计算已经默认关闭，即便使用的是 JSP 2.0 及其更高版本容器。

#### 标签

##### 自定义 JSP 标签

###### JSP 标签形式

```jsp
// 主体内容和属性都为空的标签
<mm:hello/>
// 包含属性的标签
<mm:message key="value" />
// 包含主体内容的标签
<mm:greeting>How are you.</mm:greeting>
// 包含属性和主体内容
<mm:greeting username="tom">how are youe</mm:greeting>
// 嵌套标签
<mm:greeting>
    <mm:user name="tome" age="18"/>
</mm:greeting>
```

可以把一组功能相关的标签放在同一个标签库中，开发包含自定义标签的标签库步骤：

1. 创建自定义标签的处理类 (Tag Handler Class)
2. 创建 TLD 标签库描述文件 (Tag Library Descriptor)

在 web 应用中使用自定义标签库步骤：

1. 把标签处理类及相关类的 .class 文件存放在 WEB-INF\classes 目录下
2. 把 TLD 标签库描述文件存放在 WEB-INF 目录或其自定义子目录下
3. 在 web.xml 文件中声明所引用的标签库
4. 在 JSP 文件中使用标签库中的标签

###### JSPTag API

 Servlet 容器运行  JSP 文件时，如果遇到自定义标签，就会调用这个标签的处理类(Tag Handler Class)的相关方法。标签处理类可以继承 JSP Tag API 中的 TagSupport 类或 BodyTagSupport 类

所有的标签处理类都要实现 JspTag 接口，在 JSP 2.0 之前，所有的标签处理类都要实现 tag 接口，实现该接口的标签称为传统标签，JSP 2.0 提供了 SimpleTag 接口，实现该接口的标签称为简单标签。

###### Tag 接口

Tag 接口定义了所有传统标签处理类都要实现的基本方法，包括：

```java
// 由 Servlet 容器调用该方法，向当前标签处理对象（即 Tag 对象）传递当前的 PageContext 对象
setPageContext(PageContext pc);
// 由 Servlet 容器调用该方法，向当前 Tag 对象传递父标签的 Tag 对象
setParent(Tag t);
// 返回 Tag 类型的父标签 Tag 对象
getParent();
// 当 Servlet 容器需要释放 Tag 对象占用的资源时，会调用此方法
release();
// 当 Servlet 容器遇到标签的起始标志时，会调用此方法。返回一个整数值，用来决定程序的后续流程，Tag.SKIP_BODY(主体内容被忽略) 和 Tag.EVAL_BODY_INCLUDE(标签之间的主体内容被正常执行)
doStartTag();
// 当 Servlet 容器遇到标签的结束标志，就会调用 doEndTag() 方法。返回一个整数值，用来决定后续流程，Tag.SKIP_PAGE 表示立刻停止执行标签后面的 JSP 代码，网页上未处理的静态内容和 Java 程序片段均被忽略，任何已有的输出内容立刻返回给客户端，Tag.EVAL_PAGE 表示按正常的流程继续执行 JSP 文件
doEndTag();
```

标签处理类的实例（Tag 对象）由 Servlet 容器负责创建。当 Servlet 容器在执行 JSP 文件时，如果遇到 JSP 文件中的自定义标签，就会寻找缓存中的相关的 Tag 对象，如果还不存在，就创建一个 Tag 对象，把它存放在缓存中，以便下次处理自定义标签时重复使用。Servlet 容器得到了 Tag 对象后的执行流程：

1. Servlet 容器调用 Tag 对象的 setPageContext() 和 setParent() 方法，把当前 JSP 页面的 PageContext 对象以及父标签处理对象传给当前 Tag 对象。如果不存在父标签，则把父标签处理对象设置为 null
2. Servlet 容器调用 Tag 对象的一系列 set 方法，设置  Tag 对象的属性。如果标签没有属性，则无须这个步骤
3. Servlet 容器调用 Tag 对象的 doStartTag() 方法
4. Servlet 容器调用 Tag 对象的 doEndTag() 方法
5. 如果 doEndTag() 方法返回 Tag.SKIP_PAGE，就不执行标签后续的 JSP 代码；如果 `doEndTag()` 方法返回 `Tag.EVAL_PAGE`，就执行标签后续的 JSP 代码

一旦 Tag 对象被创建后，就会一直存在，可以被 Servlet 容器重复调用，当 Web 应用终止时，Servlet 容器会先调用该 Web 应用中所有 Tag 对象的 `release()` 方法，然后销毁这些 Tag 对象

###### IterationTag 接口

IterationTag 接口继承自 Tag 接口，增加了重复执行标签主体内容的功能：

IterationTag 接口定义了 `doAfterBody()` 方法，Servlet 容器执行完标签主体内容后，调用此方法。如果 Servlet 容器未执行标签主体内容，那么不会调用此方法。`doAfterBody` 返回整型值来决定程序后续流程：`Tag.SKIP_BODY`（不再执行标签主体内容），`Tag.EVAL_BODY_AGAIN` 表示重复执行标签主体内容。Servlet 容器得到 ItrerationTag  对象执行流程：

1. Servlet 容器调用 `IterationTag` 对象的 `setPageContext()` 和 `setParent()` 方法，把当前 JSP 页面的 `PageContext`对象以及父标签处理对象传给当前 `IterationTag` 对象。如果不存在父标签，则把父标签处理对象设为 null。
2. Servlet 容器调用 `IterationTag` 对象的一系列 `set` 方法，设置 `IterationTag` 对象的属性。如果标签没有属性，则无须这个步骤
3. Servlet 容器调用 `IterationTag` 对象的 `doStartTag()` 方法。
4. 如果 `doStartTag()` 方法返回 `Tag.SKIP_BODY`，就不执行标签主体的内容；如果如果 `doStartTag()` 方法返回`Tag.EVAL_BODY_INCLUDE`，就执行标签主体的内容。
5. 如果上一步中 Servlet 容器执行了标签主体的内容，那么就调用 `doAfterBody()` 方法。
6. 如果 `doAfterBody()` 方法返回 `Tag.SKIP_BODY`，就不再执行标签主体内容；如果 `doAfterBody()` 方法返回`IterationTag. EVAL_BODY_AGAIN`，就继续重复执行标签主体内容。
7. Servlet 容器调用 `IterationTag` 对象的 `doEndTag()` 方法。
8. 如果 `doEndTag()` 方法返回 `Tag.SKIP_PAGE`，就不执行标签后续的JSP代码；如果 `doEndTag()` 方法返回`Tag.EVAL_PAGE`，就执行标签后续的 JSP 代码。

###### BodyTag 接口

BodyTag 接口继承自 `IterationTag` 接口，BodyTag 接口增加了直接访问和操纵标签主体内容的功能。BodyTag 接口定义了：

```java
// servlet 容器通过此方法向 BodyTag 对象传递一个用于缓存标签主体的执行结果的 BodyContent 对象
setBodyContent(BodyContent bc);
// Servlet 容器调用完 setBodyContent() 方法后，在第一次执行标签主体之前，先调用此方法，该方法用于为执行标签主体做初始化工作
doInitBody();
```

Servlet 容器在处理 JSP 文件中的这种标签时，会寻找缓存中的相关的 BodyTag 对象，如果还不存在，就创建一个 BodyTag 对象，把它存放在缓存中，以便下次处理自定义标签时重复使用。Servlet 容器得到 BodyTag 对象后处理流程：

1. Servlet 容器调用 `BodyTag` 对象的 `setPageContext()` 和 `setParent()` 方法，把当前 JSP 页面的 `PageContext` 对象以及父标签处理对象传给当前 `BodyTag` 对象。如果不存在父标签，则把父标签处理对象设为 null。
2. Servlet 容器调用 `BodyTag` 对象的一系列 `set` 方法，设置 `BodyTag` 对象的属性。如果标签没有属性，则无须这步
3. Servlet 容器调用 `BodyTag` 对象的 `doStartTag()` 方法。
4. 如果 `doStartTag()` 方法返回 `Tag.SKIP_BODY`，就不执行标签主体的内容；如果如果 `doStartTag()` 方法返回`Tag.EVAL_BODY_INCLUDE`，就执行标签主体的内容；如果 `doStartTag()` 方法返回`BodyTag.EVAL_BODY_BUFFERED`，就先调用 `setBodyContent()` 和 `initBody()` 方法，再执行标签主体的内容。
5. 如果上一步中 Servlet 容器执行了标签主体的内容，那么就调用 `doAfterBody()` 方法。
6. 如果 `doAfterBody()` 方法返回 `Tag.SKIP_BODY`，就不再执行标签主体内容；如果 `doAfterBody()` 方法返回`IterationTag. EVAL_BODY_AGAIN`，就继续重复执行标签主体内容。
7. Servlet 容器调用 `BodyTag` 对象的 `doEndTag()` 方法。
8. 如果 `doEndTag()`方法返回 `Tag.SKIP_PAGE`，就不执行标签后续的 JSP 代码；如果 `doEndTag()` 方法返回`Tag.EVAL_PAGE`，就执行标签后续的 JSP 代码。

###### Tag 接口实现类

TagSupport 类实现拿了 IterationTage 接口，BodyTagSupport 类继承 TagSupport 类，实现了 BodyTag 接口。用户自定义标签类可以继承这两个类，重写 TagSupport 类中的 `doXxxTag` 方法

##### 标签描述文件

标签库描述文件（Tag Library Descriptor），采用 XML 文件格式，对标签库以及库中的标签做了描述。TLD 文件中的元素可以分为：

###### `<taglib>`

标签库元素用来设定标签库的相关信息

*taglib子元素*

|    子元素    |             描述              |
| :----------: | :---------------------------: |
| tlib-version |       指定标签库的版本        |
| jsp-version  |        指定 JSP 的版本        |
|  short-name  | 指定标签库默认的前缀名 prefix |
|     uri      |  设定标签库的唯一访问标识符   |
|     info     |     设定标签库的说明信息      |

###### `<tag>`

标签元素用来定义一个标签

`tag子元素`

|    子元素    |                             描述                             |
| :----------: | :----------------------------------------------------------: |
|     Name     |                        设定标签的名字                        |
|  tag-class   |                      设定 tag 的处理类                       |
| body-content | 设定标签主体的类型，可选值：empty：标签主体为空；scriptless：标签主体不为空，并且包含 JSP 的 EL 表达式和动作元素，但不能包含 JSP 的脚本元素；jsp：标签主体不为空，并且包含 JSP 代码，JSP 代码中可以包含 EL 表达式、动作元素、脚本元素。tagdependant：标签主体不为空，并且标签主体内容由标签处理类来解析和处理。标签主体的所有代码会原样传给标签处理类，而不是把标签主体的执行结果传给标签处理类 |
|     info     |                      设定标签的说明信息                      |

###### `<attribute>`

标签属性元素 `<attribute>` 用来描述标签的属性

`attribute子元素`

|   子元素    |                            描述                             |
| :---------: | :---------------------------------------------------------: |
|    name     |                          属性名称                           |
|  required   |                属性是否是必须的，默认 false                 |
| rtexprvalue | 属性值是否可以为基于 `<%=%>` 形式的 Java 表达式或 EL 表达式 |

##### 简单标签

JSP 2 引入了一种新的标签扩展机制，简单标签扩展，这种机制有两种使用方式

* 定义实现 `javax.servlet.jsp.tagext.Simple` 接口的标签处理器
* 使用标签文件来定义标签，标签文件以 `.tag` 或 `.tagx` 作为扩展名

###### SimpleTag 接口

*javax.servlet.jsp.tagext.SimpleTag*

```java
// 由 Servlet 容器调用该方法，Servlet 容器通过此方法向 SimpleTag 对象传递当前的 JspContext 对象。JspContext 类是 PageContext 类的父类。JspContext 类中定义了用于存取各种范围内的共享数据的方法
void setJspContext(JspContext pc);
// 由 Servlet 容器调用该方法，向当前 SimpleTag 对象传递父标签的 JspTag 对象
void setParent(JspTag parent);
// 返回父标签的 JspTag 对象
JspTag getParent();
// 由 Servlet 容器调用，向当前 SimpleTag 对象传递标签主体，jspBody 表示当前标签的主体（封装了 JSP 代码）
void setJspBody(JspFragment jspBody);
// 负责具体的标签处理过程
void doTag();
```

`SampleTag` 对象由 Servlet 容器负责创建，当前 Servlet 容器在执行 JSP 文件时，每次遇到 JSP 文件中的自定义的简单标签，都会创建一个 `SimpleTag` 对象，标签处理完毕，就会销毁该对象。（自定义标签会缓存）

Servlet 容器得到了 SimpleTag 对象后执行流程：

1. Servlet 容器调用 `SimpleTag` 对象的 `setJspContext()` 和 `setParent()` 方法，把当前 JSP 页面的 `JspContext` 对象以及父标签处理对象传给当前 `SimpleTag` 对象。如果不存在父标签，则把父标签处理对象设为 null
2. Servlet 容器调用 SimpleTag 对象的 set 方法，设置 SimpleTag 对象的属性，如果标签没有属性，则跳过该步骤
3. 如果存在标签主体，Servlet 容器就调用 `SimpleTag` 对象的 `setJspBody()` 方法，设置标签主体
4. Servlet 容器调用 `SimpleTag` 对象的 `doTag()` 方法，在该方法中完成处理标签的具体逻辑

在开发简单标签时，只需创建 SimpleTagSupport 类的子类，然后覆盖 `doTag()` 方法，后续流程于自定义 JSP 标签流程一致

###### 使用标签文件

标签文件采用 JSP 语法编写，可以不包含 Java 程序片段，标签文件的扩展名通常为 `.tag`，如果标签文件使用 XML 语言，则扩展名为 `.tagx`

JSP 文件中的 page 指令在标签文件中不能使用，标签文件中增加了 tag 指令、attribute 指令、variable 指令，`<jsp:invoke>` 和 `<jsp:doBody>` 这两个标准动作元素只能在标签文件中使用。

*greentings.tag*

```
<%@ tag pageEncoding="GB2312" %>
你好，时间
```

标签文件的名字就是标签的名字，定义了标签文件，就可以在 JSP 网页中使用相应的标签

```jsp
<%@ page contentType="text/html;charset=GB2312" %>
<%@ taglib prefix="mm" tagdir="/WEB-INF/tags">
<mm:greetings>
```

使用流程：

1. 把标签文件 `greeentings.tags` 放到 `tagdir` 目录
2. 部署 jsp 文件

通过标签文件来创建标签时，不必再 TLD 文件中添加标签描述符，只需将标签文件放在 Web 应用的 `WEB-INF/tags` 目标或其子目标下，然后 JSP 网页中通过 `taglib` 指令导入并使用

如果将标签文件打包到 JAR 文件中，应该把标签文件复制到 JAR 文件的展开目标的 `META-INF/tags` 目录或其子目录下，并且应该再 JAR 文件的展开目录的 `MATA-INF` 子目录下提供 TLD 文件，用 `<tag-file>` 元素来配置标签文件，`<path>` 子元素的值必须以 `/META-INF/tags` 开始：

```xml
<taglib>
	<tlib-version>1.1</tlib-version>
    <short-name>tags</short-name>
    <tag-file>
    	<name>greetings</name>
        <path>/META-INF/tags/greetings.tag</path>
    </tag-file>
</taglib>
```

*标签文件隐含对象*

| 隐含对象的变量名 |          隐含对象的类型           |  存在范围   |
| :--------------: | :-------------------------------: | :---------: |
|     request      | javax.servlet.HttpServletRequest  |   request   |
|     response     | javax.servlet.HttpServletResponse |    page     |
|    jspContext    |   javax.servlet.jsp.JspContext    |    page     |
|   application    |   javax.servlet.ServletContext    | application |
|       out        |    javax.servlet.jsp.JspWriter    |    page     |
|      config      |   javax.servlet.SerrvletConfig    |    page     |
|     session      |  javax.servlet.http.HttpSession   |   session   |

标签文件中不存在 `page` 和 `exception` 隐含对象。标签文件中存在 `jspContext` 隐含对象，它是`javax.servlet.jsp.JspContext` 类型；JSP 文件中存在 `pageContext` 隐含对象，它是 `javax.servlet.jsp.PageContext` 类型。JspContext 类是 PageContext 类的父类

###### 标签文件的指令

标签文件中的使用的指令包括：`taglib`、`include`、`tag`、`attribute`、`variable`。`taglib` 和 `include` 指令与 JSP 文件中的 `taglib` 和 `include` 指令的用法相同，`tag`、`attribute`、`variable` 指令只能在标签文件中使用

* tag 指令

  与 JSP 文件中的 page 指令的作用相似，tag 指令用于设置整个标签文件的一些属性：

  * display-name 属性：为标签指定一个简短的名字，这个名字可以被一些工具软件显示。默认值为标签文件的名字（不包含扩展名）。
  * body-content 属性：指定标签主体的格式，可选值包括 empty、scriptless 和 tagdependent 。默认值为 scriptless
  * dynamic-attributes 属性：指定动态属性的名字。Servlet 容器把标签文件翻译成简单标签处理类时，会在类中创建一个 Map 对象，用来存放动态属性的名字和值，其中属性的名字作为 Map Key，属性的值作为 Map value
  * small-icon 属性：为标签指定小图标文件（gif 或 jpeg 格式）的路径，大小为 16×16，该图标可以在具有图形用户界面的工具软件中显示。
  * large-icon 属性：为标签指定大图标文件（gif 或 jpeg格式）的路径，大小为 32×32，该图标可以在具有图形用户界面的工具软件中显示。
  * description 属性：为标签提供文本描述信息。
  * example 属性：提供使用这个标签的例子的信息描述。
  * language 属性：与 JSP 文件中 page 指令的 language 属性相同，用于设定编程语言，默认值为 “java”。
  * import 属性：与 JSP 文件中 page 指令的 import 属性相同，用于引入 Java 类。
  * pageEncoding 属性：与 JSP 文件中 page 指令的 pageEncoding 属性相同，设定标签文件的字符编码
  * isELIgnored 属性：与 JSP 文件中 page 指令的 isELIgnored 属性相同，用于指定是否忽略 EL 表达式。如果取值为 false，则会解析 EL 表达式；如果为 true，则把 EL 表达式按照普通的文本处理。默认值为 false。

* attribute 指令

  类似于 TLD  中的 `<attribute>` 元素，用于声明自定义标签的属性

  ```jsp
  // 声明标签有一个 username 属性
  <%@
  	attribute name="username" required="true"
  	fragment="false" rtexprvalue="true" type="java.lang.String"
  	description="name for user"
  %>
  ```

  * name 属性：指定属性的名字（必须）
  * required 属性：指定属性是否是必须的。默认值为 false。
  * fragment 属性：指定属性是否是 JspFragment 对象。默认值是 false。如果 fragment 属性为 true，那么无须设置rtexprvalue 和 type 属性，此时 rtexprvalue 属性被自动设置为 true，type 属性被自动设置`javax.servlet.jsp.tagext.JspFragment。`
  * rtexprvalue 属性：指定属性是否可以是一个运行时表达式。默认值是 true。
  * type 属性：指定属性的类型，不能指定为 Java 基本类型，默认值是 `java.lang.String`
  * description 属性：为属性提供文本描述信息

* variable 指令

  类似于 TLD 中的 `<variable>` 元素，用于设置标签为 JSP 页面提供的变量

  ```jsp
  // 定义一个 sum 变量
  <%@ variable name-given="sum" variable-class="java.lang.Integer" scope="NESTED"
      description="The sum of the two operands" %>
  ```

  * name-given 属性：指定变量的名字。在 variable 指令中，要么设置 name-given 属性，要么设置 name-from-attribute 属性。
  * name-from-attribute 属性：表示用标签的某个属性的值作为变量的名称。
  * alias 属性：定义一个本地范围的属性来保存这个变量的值。当指定了 name-from-attribute 属性时，必须设置alias 属性。
  * variable-class 属性：指定变量的 Java 类型，默认值为 `java.lang.String`
  * declare 属性：指定变量是否引用新的对象，默认值为 true。
  * scope 属性：指定变量的范围。可选值包括：AT_BEGIN(从标签起始标记开始到 JSP 页面结束构成的范围)、NESTED(标签主体构成的范围) 和 AT_END(从标签结束标记开始到JSP页面结束构成的范围)。默认：NESTED
  * description 属性：为变量提供文本描述信息。

###### 标签文件的动作元素

标签文件中可以包含 `<jsp:invoke>` 和 `<jsp:doBody>` 动作元素。

* `<jsp:invoke>`

  用于执行标签的 JspFragment 类型的属性所包含的 JSP 代码，并把只需结果输出到当前 JspWriter 对象中，或者保存到指定的命名变量中

  * fragment 属性：这是必须的属性。指定类型为 JspFragment 的属性的名称。在 JSP 文件中必须使用 `<jsp:attribute>` 元素来设置该属性，
  * var 属性：这是可选的属性。指定一个命名变量的名字。该命名变量保存了JspFragment 对象的执行结果。var 属性和 varReader 属性只能指定其一，如果两者都没有指定，则 JspFragment 对象的执行结果被输出到当前的JspWriter 对象中。
  * varReader 属性：这是可选的属性。指定一个 `java.io.Reader` 类型的命名变量，该变量保存了JspFragment 对象的执行结果。
  * scope 属性：这是可选的属性。为 var 属性或 varReader 属性指定的命名变量指定存放范围，默认值为 page

* `<jsp:doBody>`

  用于执行标签主体，并把执行结果输出到当前 JspWriter 对象中，或保存到指定的命名变量中。
  
  * var 属性：同 `<jsp:invoke>`
  * varReader 属性：同 `<jsp:invoke>`
  * scope 属性：同 `<jsp:invoke>`
  
  如果标签文件未指定标签主体内容，或使用 `<jsp:body>` 指定主体内容，在 JSP 文件用 `<jsp:body>` 子元素设置标签主体内容

#### 标准标签

Oracle 公司指定了一组标准标签库的最新规范，由 [apache 实现](http://tomcat.apache.org/taglibs/standard/)，这组标准标签库简称 JavaServer Pages Standard Tag Library JSTL。项目要引入第三方开发的标签库（第三方标签库打包为一个 Jar 文件，这个 Jar 文件包含：所有标签处理类以及相关类的 `.class` 文件，META-INF 目录，该目录下有一个描述标签库的 TLD 文件）流程：

1. 将 Jar 文件导入到 lib 目录下
2. 在 JSP 文件中通过 taglib 指令声明标签库（taglib 指令中 uri 属性和 TLD 文件 uri 一致）

#### JavaServer Pages Standard Tag Library)

JSTL 是一个定制标签库的集合，用来解决类似遍历 Map 或集合、条件测试、XML 处理，数据库访问和数据操作等。JSTL 是通过多个标签库来暴露其行为的。

*JSTL标签库*

| 标签库名  | 前缀 |                  URI                   |                            描述                            |
| :-------: | :--: | :------------------------------------: | :--------------------------------------------------------: |
|   Core    |  c   |   http://java.sun.com/jsp/jstl/core    |        核心标签库，条件标签、迭代标签、URL 相关标签        |
|   I18N    | fmt  |    http://java.sun.com/jsp/jstl/fmt    |         国际化web应用标签，日期、时间、数字格式化          |
|    Sql    | sql  |    http://java.sun.com/jsp/jstl/sql    |                     包含访问数据库标签                     |
|    Xml    |  x   |    http://java.sun.com/jsp/jstl/xml    |               包含对 XML 文档进行操作的标签                |
| Functions |  fn  | http://java.sun.com/jsp/jstl/functions | 包含一组通用的 EL 函数，在 EL 表达式中可以使用这些 EL 函数 |

在 JSP 页面中使用 JSTL 库，格式：

```jsp
<% taglib uri="uri" prefix="prefix" %>
// 使用 core 库
<% taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
```

JSTL 标签的 body content 可以为 empty、JSP、tagdependent

#### Core 标签库

##### 一般用途的标签

###### `<c:out>` 

out 标签在运算表达式（该表达式可以为 `<%=%>`或 `${}`）时，是将结果输出到当前的 `JspWriter`，out 的语法有两种形式，即有 `body content` 和没有 `body content`

```jsp
<% tablib uri="http://java.sun.com/jsp/jstl/core", prefix="c" %>
<c:out value="value" [escapeXml="{true|false}"] [default="defaultValue"]/>
<c:out value="value" [escapeXml="{true|false}"]>
    default value
</c:out>
```

在标签语法中，`[]` 表示可选的属性。如果值带下划线，则表示为默认值。`out` 的 `body content` 为 JSP。out 标签属性：

* value*+

  对象，要计算的表达式

* escapeXml+

  布尔，表示结果中的字符 `<`、`>`、`'`、`"`、`&` 将被转化成相应的实体码，`&lt;`、`&gt;`、`&#039;`、`&#034`、`&amp;`

* default+

  对象，默认值

如果包含一个或多个特殊字符的字符串没有进行 XML 转义，它的值就无法在浏览器中正常显示。没有通过转义的特殊字符，会使网站易于遭受交叉网站的脚本攻击；

out 中的 default 属性可以赋一个默认值，当赋予其 value 属性的 EL 表达式返回 null 时，就会显示默认值。default 属性可以赋动态值，如果这个动态值返回 null，out 就会显示一个空的字符串

###### `<c:set>`

可以使用 value 和 body Content 为以下几类 var 赋值：

* 为 String 类型的命名变量设定值

  ```jsp
  <c:set var="命名变量名" value="表达式" scope="{page|request|session|application}" />
  // 使用 value 属性设定会话属性 user 值为 Tom
  <c:set var="user" value="Tom" scope="session"/>
  // 使用 body content 设置会话属性 user 为 Tom
  <c:set var="user" scope="session">Tom</c:set>
  // 等价于
  <% pageContext.setAttribute("user", "Tom", PageContext.SESSION_SCOPE); %>
  ```

* 如果命名变量为 JavaBean，那么为这个 JavaBean 对象的特定属性设定值

  ```jsp
  <c:set target="JavaBean的命名变量" property="JavaBean的属性名" value="表达式" />
  <%@ page import="package.CounterBean" %>
  <jsp:useBean id="counterBean" scope="application" class="package.CounterBean" />
  <c:set target="${counterBean}" property="counter" value="2"/>
  // 等价于
  <%
  	CounterBean counterBean = pageContext.findAttribute("counterBean");
  	counterBean.setCount(2);
  %>
  ```

* 如果命名变量为 Map 类型，那么为这个 Map 对象中的特定 Key 设定值

  ```jsp
  <c:set target="代表 Map 对象的命名变量" property="key的名字" value="表达式" />
  <%@ page import="java.util.HashMap" %>
  <jsp:useBean id="weeks" scope="request" class="java.util.HashMap" />
  <c:set target="${weeks}" property="1" value="Monday" />
  // 等价于
  <%
  	Map weeks = (HashMap) pageContext.findAttribute("weeks");
  	weeks.put("1", "Monday");
  %>
  ```

set 标签的属性

* value

  对象：要创建的字符串，或者要引用的有界对象，或者新的属性值

* var

  字符串：要创建的有界变量

* scope

  字符串：新创建的有界变量的范围，默认为 page

* target

  对象：其属性要被赋新值的有界对象；这必须时一个 JavaBeans 实例或 java.util.Map 对象

* property

  字符串：要被赋新值的属性名称

###### `<c:remove>` 

`<c:remove>` 标签用于删除范围命名变量，变量引用的对象不能删除，如果另一个变量也引用同一个对象，仍然可以通过另一个变量访问该对象，如果未指定 scope 属性，会从所有范围内删除 var 指定的命名变量

```jsp
<c:remove var="命名变量的名字" [scope="{page|request|session|application}"]>
// 删除会话范围的 user 命名变量
<c:remove var="user" scope="session" />
// 等价
<%
    pageContext.removeAttribute("user", PageContext.SESSION_SCOPE);
%>
```

###### `<c:catch>`

用于捕获标签主体中可能出现的异常，并且把异常对象作为命名变量保存在页面范围内

```jsp
<c:catch var="代表异常对象的命名变量的名字">
<c:catch var="ex">
<%
    int a = 11;
    int b = 0;
    int c = a/b;
%>
</c:catch>
// 等价于
<%
    try {
        int a = 11;
        int b = 0;
        int c = a/b;
    } catch (Exception e) {
        pageContext.setAttribute("ex", e, PageContext.PAGE_SCOPE);
    }
%>
```

##### 条件行为

###### `<c:if>`

if 标签是对某一个条件进行测试，假如结果为 true，就处理它的 body content，测试结果保存在 Boolean 对象中，并创建有界变量来引用这个 boolean 对象。利用 var 属性和 scope 属性分别定义有界变量的名称和范围。if 的语法有两种形式：没有 body content，这种情况下，var定义的有界对象一般是通过其他标签在同一个 JSP 的后续阶段再进行测试；使用 body content，body content 是 JSP，当测试条件的结果为 True 时，就会得到处理

```jsp
<c:if test="逻辑表达式" var="逻辑表达式值的命名变量名字" scope="{page|request|session|application}" />
// 判断 username 的参数值是否为 Tom，将判断结果赋值为 request 范围的 result 变量
<c:if test="${param.username=="Tom"}" var="resutl" scope="request" />
// 等价
<%
	String username = request.getParameter("username");
	if ("Tom".equals(username)) {
        request.setAttribute("result", true);
    } else {
        request.setAttribute("result", false);
    }
%>
// 包含主体
<c:if test="${param.save='user'}">
	Saving user <c:set var="user" value="Tom" />
</c:if>
// 等价
<%
	String save = request.getParamter("save");
	if ("user".equals(save)) {
        out.print("Saving user");
        pageContext.setAttribute("user", "Tom");
    }
%>
```

if 标签的属性

* test

  布尔：决定是否处理任何现有 body content 的测试条件

* var

  字符串：引用测试条件值的有界变量名称：var 的类型为 Boolean

* scope

  字符串：var 定义的有界变量的范围，默认是 page

###### `<c:choose>`、`<c:when>`、`<c:otherwise>`

* `<c:when>` 和 `<c:otherwise>` 不能单独使用，必须位于 `<c:choose>` 父标签中
* `<:choose>` 标签中可以包含一个或多个 `<c:when>` 标签
* `<c:choose>` 标签中可以不包含 `<c:otherwise>` 标签
* `<c:choose>` 标签中如果同时包含 `<c:when>` 和 `<c:otherwise>` 标签，那么 `<c:otherwise>` 标签必须位于 `<c:when>` 标签之后

```jsp
<c:choose>
	<c:when test="${empty param.username}">
    	Nnknown user
    </c:when>
    <c:when test="${param.username=="Tom"}">
    	${param.username} is manager
    </c:when>
    <c:otherwise>
    	${param.username} is employee
    </c:otherwise>
</c:choose>
// 等价于
<%
	String username = request.getParameter("username");
	if (username == null) {
        out.print("Nnknown user");
    } else if (username.equals("Tom")) {
        out.print(username+ " is manager")
    } else {
        out.print(username + " is employee")
    }
%>
```

##### 遍历行为

###### `<c:forEach>`

每次从集合中取出一个元素，把它存放在命名变量中，在标签主体中可以访问这个命名变量

```jsp
<c:forEach var="代表集合中的一个元素的命名变量的名字" items="集合">
	body content
</c:forEach>
<%@ page import = "java.util.HashSet" %>
<%
	HashSet names = new HashSet();
	names.add("Tom");
	names.add("Mike");
%>
<c:forEach var="name" items="<%=names%>" >
	${name}
</c:forEach>
// 等价
<%
	Iterator it = names.iterator();
	while (it.hasNext()) {
        String name = (String) it.next();
        pageContext.setAttribute("name", name);
%>
<%
	name = (String) pageContext.getAttribute("name");
	out.print("name");
%>
<%
	pageContext.removeAttribute('name');
}
%>
```

固定次数地重复 body content

```jsp
<c:forEach [var="varName"] begin="begin" end="end" step="step">
    body content
</c:forEach>
```

遍历（`java.util.Set`，`java.util.List`，`java.util.Map`，`java.util.Iterator`，`java.util.Enumeration`）接口实现类；Java 数组，以逗号 `,` 分割的字符串

```jsp
<c:forEach items="collection" [var="varName"] [varStatus="varStatusName"] [begin="begin"] [end="end"] [step="step"]>
    body content
</c:forEach>
```

body content 是 JSP，forEach 标签属性：

* var

  字符串：引用遍历的当前项目的有界变量名称

* items

  支持的任意类型：遍历的对象集合

* varStatus

  字符串：保存遍历状态的有界变量名称。类型值为 `javax.servlet.jsp.jstl.core.LoopTagStatus`，这个命名变量包含了从集合中取出的当前元素的状态信息：count（当前元素在集合中的序号，从 1 开始计数），index（当前元素在集合中的索引，从 0 开始计数），first（当前元素是否是集合中的第一个元素），last（当前元素是否是集合中的最后一个元素）

* begin

  整数：如果指定 items，遍历将从指定索引处的项目开始。如果没有指定 items，遍历将从设定的索引值开始。如果指定，begin 的值必须大于等于 0

* end

  整数：如果指定 items，遍历将在包含指定索引处的项目结束。如果没有指定items，遍历将在索引到达指定值时结束

* step

  整数：遍历将只处理间隔指定 step 的项目，从第一个项目开始，在这种情况下 step 的值必须大于或等于 1

对于每一次遍历，`forEach` 标签都将创建一个有界变量，变量名称通过 var 属性定义，这个有界变量只存在于开始和关闭的 `forEach` 标签之间，一到关闭的 `forEach` 标签钱，它就会被删除。forEach 标签有一个类型为 `javax.servlet.jsp.jstl.core.LoopTagStatus` 的变量 `varStatus`。`LoopTagStatus` 接口带有 `count` 属性，它返回当前遍历的次数。第一次遍历时，`status.count` 值为 1；依次累加

###### `<c:forTokens>`

用于遍历以特定分隔符分割的子字符串，并且能重复执行标签主体

```jsp
<c:forTokens items="stringOfTokens" delims="delimiters" [var="varName"] [varStatus="varStatusName"] [begin="begin"] [end="end"] [step="step"]>
    body content
</c:forTokens>
<c:forTokens var="name" items="Tom:Mike:Linda" delims=":">
	${name}
</c:forTokens>
```

body content 是 JSP，forTokens 标签的属性：

* var

  字符串：引用遍历的当前项目的有界变量名称

* items

  支持的任意类型：要遍历的 token 字符集

* varStatus

  字符串：保存遍历状态的有界变量名称。类型值为 javax.servlet.jsp.jstl.core.LoopTagStatus

* begin

  整数：遍历的起始索引，此处索引是从 0 开始的。如有指定，begin 的值必须大于或等于 0

* end

  整数：遍历的终止索引，此处索引是从 0 开始的

* step

  整数：遍历将只处理间隔指定 step 的 token，从第一个 token 开始。如有指定，step 的值必须大于或等于 1

* delims

  字符串：一组分隔符

##### URL 相关的标签

###### `<c:import>`

用于包含其他 web 资源，与 `<jsp:include>` 指令类似，区别在于，`<c:import>` 标签可以包含其他 Web 应用或其他网站中的资源

```jsp
<c:import url="web 资源的 URL"/>
```

包含以下属性：

* var

  String 类型，如果设定了 var 属性，不会把 url 属性设定的目标文件的内容直接包含到当前文件中，而是把目标文件中的文本内容保存在 var 属性设定的命名变量中

* context

  设定应用的根路径，url 属性设定的文件在应用中的绝对路径

###### `<c:url>`

按特定的重写规则重新构造 URL，把重新生成的 URL 存在到 var 属性指定的命名变量中，scope 默认 page。

```jsp
<c:url value="原始url" var="存放新的URL的命名变量" scope="{page|request|session|application}"/>
// 在页面范围内创建一个 myurl 命名变量
<c:url value="/dir/origin.jsp" var="newUrl" />
<a href="${newUrl}">target.jsp</a>
<c:url value="/dir/origin.jsp" var="newUrl">
    // 可以包含 param 子标签，用于设定请求参数, param 标签会对特殊符号进行编码
	<c:param name="username" value="Tom"/>
</c:url>
```

###### `<c:redirect>`

把请求重定向到其他 web 资源

```jsp
<c:redirect url="目标 web 资源的 URL" />
// 可以设置 context 属性，还可以加入 param 子标签
<c:redirect url="/dir/target.jsp" context="/dir">
	<c:param name="num" value="10" />
    <c:param name="num1" value="20" />
</c:redirect>
```

#### I18N 标签库

主要用于编写国际化的 web 应用。一部分用于国际化，另一部分用于对时间、日期和数字进行格式化。如果一个应用支持国际化，应该具有以下特征：

* 当应用需要支持一种新的语言时，无须修改应用程序代码
* 文本、消息和图片从源程序代码中抽取出来，存储在外部
* 应该根据用户的语言和地理位置，对和特定文化相关的数据（如日期、时间、货币）进行正确格式化
* 支持非标准的字符集
* 可以方便快捷地对应用做出调制，使它适应新的语言和地区

##### 国际化标签

###### `<fmt:setLocale>`

设置 locale，把 locale 保存到特定范围（默认 page）内

```jsp
<fmt:setLocale value="locale" scope="{page|request|session|application}"/>
// 存放一个表示中文地 locale
<fmt:setLocale value="zh_CN" scope="session"/>
// 等价
<%
	Locale locale = new Locale("zh", "CN");
	session.setAttribute("javax.servlet.jsp.jstl.fmt.locale.session", locale);
%>
// 等价
<%
	Locale locale = new Locale("zh", "CN");
	session.setAttribute(Config.FMT_LOCALE + ".session", locale;
%>
```

###### `<fmt:setBundle>`

设置 ResourceBundle，把 ResourceBundle 保存到特定范围内（默认 page）

```jsp
<fmt:setBundle basename="资源文件地名字" var="命名变量的名字" scope="{page|request|session|application}" />
// 在会话范围存放一个 ResourceBundle
<fmt:setBundle basename="messages" var="myres" scope="session" />
// 等价
<%
	Locale locale = Config.find(pageContext.Config.FMT_LOCALE);
	if (locale == null) {
        locale = request.getLocale();
    }
	ResourceBundle bundle = ResourceBundle.getBundle("messages", locale);
	javax.servlet.jsp.jstl.fmt.LocalizationContext context = new LocalizationContext(bundle);
	session.setAttribute("myres", bundle);
%>
```

如果没有设置 var 属性，那么命名变量将采用 `javax.servlet.jsp.jstl.core.COnfig` 类的静态字符串常量 `FMT_LOCALIZATION_CONTEXT` 的值(`javax.servlet.jsp.jstl.fmt.localizationContext`)，该标签设置的 `ResourceBundle` 将作为特定范围内的默认 `ResourceBundle`

###### `<fmt:bundle>`

设置标签主体使用的 `ResourceBundle`

```jsp
<fmt:bundle basename="资源文件的名字" 前缀="消息key的前缀">
	标签主体
</fmt:bundle>
// 假定 messages.properties 文件 app.login.user=tom app.login.password=secret
<fmt:bundle basename="messages" prefix="app.login.">
	<fmt:message key="user"/>
    <fmt:message key="password"/>
</fmt:bundle>
```

###### `<fmt:message>`

根据属性 key 返回 ResourceBundle 中匹配的消息文本。如果指定了 var 属性和 scope 属性（默认 page），则把消息文本作为命名变量存放在特定范围内，否则直接输出

```jsp
<fmt:message key="myword" var="msg"/>
```

###### `<fmt:param>`

嵌套在 `<fmt:message>` 父标签中，用于为消息文本中的消息参数设置值

```jsp
// 假定 message.properties 文件中包含 ：hello.hi = Nice to meet you,{0}.The current time is {1}.
// 为 {0} {1} 消息参数设置值
<fmt:formatDate value="<%=new Date() %>" type="both" var="now" />
<fmt:message key="hello.hi">
	<fmt:param value="Tom" />
    <fmt:param value="${now}" />
</fmt:message>
```

###### `<fmt:requestEncoding>`

设置 HTTP 请求正文使用的字符编码

```jsp
<fmt:requestEncoding value="GB2312"/>
// 等价
<% request.setCharacterEncoding("GB2312") %>
```

##### 格式化标签

###### `<c:setTimeZone>`

设置标签时区，把时区保存到特定范围内，如果没有设置 var 属性，命名变量将采用 `javax.servlet.jsp.jstl.core.Config` 类的静态字符串常量 `FMT_TIME_ZONE` 的值（`javax.servlet.jstl.fmt.timeZone`），该标签设置的时区将作为特定范围内的默认时区

```jsp
// value 字符串或 java.util.TimeZone 时区，scope 默认 page
<fmt:setTimeZone value="timeZone" [var="varName"][scope="{page|request|session|application}"]>
// 在会话范围内存放一个表示 GMT 的时区
<fmt:setTimeZone value="GMT" var="myzone" scope="session" />
```

###### `<c:formatDate>`

formatDate 标签用于格式化日期，语法：

```jsp
<fmt:formatDate value="date" 
                [type="{time|date|both}"] 
                [dateStyle="{default|short|medium|long|full}"]
                [timeStyle="{default|short|medium|long|full|}"]
                [pattern="customPattern"]
                [timeZone="timeZone"]
                [var="varName"]
                [scope="{page|request|session|application}"]/>
<c:set value="now" value="<%=new Date() %>" />
default <fmt:formatDate value="${now}" type="both"/>
```

formatDate标签的属性:

* value+

  java.util.Date：要格式化的日期或时间

* type+

  字符串：指定格式化日期(date默认)，还是时间(time)，还是日期时间(both)

* dataStyle+

  字符串：预定义时间的格式化样式（default，short，medium，long，full 默认 default），遵循 `java.text.DateFormat` 中定义的语义

* timeStyle+

  字符串：预定义时间的格式化样式（default，short，medium，long，full 默认 default），遵循 `java.text.DateFormat` 中定义的语义

* pattern+

  字符串：定制格式化样式，遵循 `java.text.SimpleDateFormat`

* timezone+

  字符串或 java.util.TimeZone：定义用于显示时间的时区

* var

  字符串：将输出结果存为字符串的有界变量名称，未设定该属性，将直接输出格式化时间

* scope

  字符串：var 的范围，默认 page

###### `<c:parseDate>`

将已经格式化后的字符串形式的日期或时间转换为 `java.util.Date` 日期类型

```jsp
<fmt:parseDate value="dateString"
               [type="{time|date|both}"]
               [dateStyle="{default|short|medium|long|full}"]
               [timeStyle="{default|short|medium|long|full}"]
               [pattern="customPattern"]
               [timeZone="timeZone"]
               [parseLocale="parseLocale"]
               [var="varName"]
               [scope="{page|request|session|application}"]/>
<fmt:parseDate value="2020-01-09 11:02:03"
               pattern="yyyy-MM-dd" 
               parseLocal="zh_CN" 
               type="both" 
               var="dd"
               scope="request"/>
<fmt:formatDate value="${dd}" type="both" dateStyle="long" timeStyle="long"/>
```

parseDate 标签属性：

* value

  字符串：要解析的字符串

* type

  字符串：指定解析类型：日期（date，默认）、时间（time）、时间日期（both）

* dateStyle

  字符串：日期的格式化样式，默认 default

* timeStyle

  字符串：时间的格式化样式，默认 default

* pattern

  字符串：定制格式化样式，决定要如何解析该字符串

* timeZone

  字符串或 java.util.TimeZone：定义时区，使日期字符串中的时间信息均根据它来解析

* parseLocale

  字符串或 java.util.Locale：定义 locale，在解析操作期间用其默认为格式化样式，或将 pattern 属性定义的样式应用其中

* var

  字符串：保存输出结果的有界变量名称，未指定则直接输出

* scope

  字符串：var 的范围，默认 page

###### `<c:formatNumber>`

formatNumber 用于格式化数字，可以根据需要利用它的各种属性来获取自己想要的格式。`formatNumber` 的语法有两种形式：

* 没有 body content

```jsp
<fmt:formatNumber value="numericValue" 
      [type="{number|currency|percent}"]
      [pattern="customPattern"] 
      [currencySymbol="currencySymbol"]
      [groupingUsed="{true|false}"]
      [maxIntegerDigits="maxIntegerDigits"]
      [minIntegerDigits="minIntegerDigits"]
      [maxFractionDigits="maxFractionDigits"]
      [minFractionDigits="minFractionDigits"]
      [var="varName"]
      [scope="{page|request|session|application}"]
      />
<fmt:formatNumber value="123455" type="currency"/>
```

* 有 body content，body content 是 JSP

formatNumber 属性：

* value+

  字符串或数字：要格式化的数字化值

* type+

  字符串：说明该值是要被格式化成数字(number默认)、货币(currency)、还是百分比(percent)

* pattern+

  字符串：格式化样式

* currencyCode+

  字符串：ISO 4217 码，货币代码

  加拿大元 CAD，人民币 CNY，欧元 EUR，日元 JPY，英镑 GBP，美元 USD

* CurrencySymbol+

  字符串：货币符号，只适用于格式化货币类型的数字，如果没有设置该属性，会根据当前的 Locale 来使用对应的货币符号

* groupingUsed+

  布尔：是否对数字进行分组显示的分隔符，默认 true，自动对数字分组（千位逗号分割）

* maxIntegerDigits+

  整数：规定输出结果的整数部分最多几位数字

* minIntegerDigits+

  整数：规定输出结果的整数部分最少几位数字

* maxFractionDigits+

  整数：规定输出结果的小数部分最多几位数字

* minFractionDigits+

  整数：规定输出结果的小数部分最少几位数字

* var

  字符串：将输出结果存为字符串的有界变量名称

* scope

  字符串：var 的范围，如果有 scope 属性，则必须指定 var 属性

###### `<c:parseNumber>`

将格式化字符串表示的数字、货币或者百分比解析成数字

```jsp
<fmt:parseNumber value="numericValue" 
                  [type="{number|currentcy|percent}"]
                  [pattern="customPattern"]
                  [parsetLocale="parsetLocale"]
                  [integerOnly="{true|false}"]
                  [var="varName"]
                  [scope="{page|request|session|application}"]/>
<fmt:parseNumber value="$123,456.78" type="currency" parsetLocal="en_US"/>
```

支持有 body content 和没有 body content 格式，body content 是 JSP，标签属性:

* value+

  字符串或数字：要解析的字符串

* type+

  字符串：说明该字符串是要被解析成数字（number，默认）、货币（currency）、还是百分比（percent）

* pattern+

  字符串：格式化样式，决定 value 属性中的字符串要如何解析

* parseLocale+

  字符串或者 java.util.Locale： 定义locale，在解析操作期间将其默认为格式化样式，或将 pattern 属性定义的样式应用其中

* integerOnly+

  布尔：说明是否只解析指定值的整数部分

* var

  字符串：保存输出结果的有界变量名称，未指定直接输出结果

* scope

  字符串：var 的范围，默认为 page

#### SQL 标签库

###### `<sql:setDataSource>`

设置数据源，SQL 标签中的其他标签从数据源中得到数据库连接，为 `<sql:setDataSource>` 标签设置的数据源来源：

* 由 Servlet 容器提供的数据源（JNDI）

  ```jsp
  <sql:setDataSource dataSource="jdbc/webDB" var="conn" scope="application"/>
  ```

* 由 `<sql:setDataSource>` 标签自身创建数据源

  ```jsp
  <sql:setDataSource url="jdbc:mysql://localhost:3306/webDB"
                     driver="com.mysql.cj.jdbc.Driver"
                     user="root"
                     password="secret" 
                     var="conn"
                     scope="application"/>
  ```

如果未设置 var 属性，命名变量名将采用 `javax.servlet.jsp.jstl.core.Config` 类的静态字符串常量 `SQL_DATA_SOURCE` 的值（`javax.servlet.jsp.jstl.dataSource`），该标签设置的数据源作为特定范围内的默认数据源

###### `<sql:query>`

用于执行 SQL 查询语句，属性：

* sql 属性：指定 select 查询语句
* dataSource 属性：指定数据源。如果没有设定该属性，将使用由 `<sql:setDataSource>` 标签设置默认数据源
* maxRows 属性：指定从原始查询结果中取出的最大记录数目
* startRow 属性：指定从原始查询结果中第几条记录开始取出记录。原始查询结果中第一条记录的索引为 0
* var 属性：指定查询结果的命名变量名
* scope 属性：指定查询结果的存放范围，默认值 page

```jsp
// 默认数据源
<sql:setDataSource url="jdbc:mysql://localhost/web"
                   driver="com.mysql.cj.jdbc.Driver"
                   user="root"
                   password="secret"/>
<sql:setDataSource dataSource="jdbc/webDB" var="conn" />
// 使用默认数据源
<sql:query sql="select id,name,title,price from books" var="books" />
// 指定数据源
<sql:query sql="select id,name,title,price from books" var="books" dataSource="${myRes}" />
// 使用 body content
<sql:query var="books" startRow="1" maxRows="10">
	select id, name, title, price from books order by id
</sql:query>
// 使用结果
<c:forEach var="book" items="${books.rows}">
	<tr>
        <td>${book.id}</td>
        <td>${book.name}</td>
        <td>${book.title}</td>
        <td>${book.price}</td>
    </tr>
</c:forEach>
// 等价
<c:forEach var="book" items=${books.rowsByIndex}>
   <tr>
        <td>${bookp[0]}</td>
        <td>${book[1]}</td>
        <td>${book[2]}</td>
        <td>${book[3]}</td>
    </tr>
</c:forEach> 
```

查询返回 `javax.servlet.jsp.jstl.sql.Result` 接口类型

```java
// 返回查询结果中所有行，每个 SortedMap 表示一行，以字段名为 key，以相应的字段值为 value
SortedMap[] getRows();
// 以二维数组形式返回查询结果，第一维表示查询结果的记录，第二维表示查询结果的字段
Object[] getRowsByIndex();
// 返回查询结果中所有字段名
String[] getColumnNames();
// 返回查询结果中所有记录数
int getRowCount();
// 判断查询结果的记录数目是否受 query 标签的 maxRows 属性限制，当原始查询结果的记录数目大于 maxRows,返回 true，当原始查询结果记录数小于或等于 maxRows，返回 false
```

###### `<sql:param>`

为 SQL 语句中的参数设置值，和 `PreparedStatement` 类的 `setXxx()` 方法作用相似，可以嵌套在 `<sql:query>` 和 `<sql:update>` 标签中

```jsp
<sql:query var="books">
    select id, name, title, price form books where id > ?
 	<sql:param>1</sql:param>
</sql:query>
```

###### `<sql:dateParam>`

类似 `<sql:param>`，区别在于 `<sql:dateParam>` 标签用来为 SQL 语句中时间或日期类型的参数赋值，value 属性设置 SQL 语句中相应参数的值，`java.util.Date` 类型；type 属性，String 类型，指定参数的类型，可选：date、time、timestamp（默认）

```jsp
<fmt:parseDate value="2019-12-04" type="date" var="updated_at"/>
<sql:query var="books">
	select id, name, title, price from books where updated_at = ?
    <sql:dateParam value="${updated_at}" type="date"/>
</sql:query>
```

###### `<sql:update>`

用于执行 INSERT、UPDATE、DELETE、DDL 语句，具有以下属性：

* sql：指定待执行的 SQL 语句
* dataSource：指定数据源，如果没有设定该属性，将使用 `<sql:setDataSource>` 标签设置的默认数据源
* var：指定执行结果的命名变量名。执行结果表示数据库中受影响的记录的数目
* scope：指定执行结果的存放范围，默认为 page

```jsp
<sql:update var="result">
	insert into books(id, name, title, price) values (?,?,?,?)
    <sql:param>1000</sql:param>
    <sql:param>索尼</sql:param>
    <sql:param>oop</sql:param>
    <sql:param>20.3</sql:param>
</sql:update>
```

###### `<sql:transaction>`

用于为嵌套在其中的 `<sql:query>` 和 `<sql:update>` 标签声明数据库事务。位于同一个 `<sql:transaction>` 标签中的所有 `<sql:query>` 和 `<sql:update>` 标签所执行的 SQL 操作将作为一个数据库事务，具有以下属性：

* dataSource：设置数据源
* isolation：设置事务隔离级别，未设置将使用数据库默认隔离级别

```jsp
<sql:setDataSource dataSource="jdbc/webDB" var="dbRes"/>
<sql:transaction dataSource="${dbRes}">
	<sql:update var="result">
    	update books set price = price - 10 where id = ?
        <sql:param>200</sql:param>
    </sql:update>
    <sql:update var="result1">
    	delete from books where id = ?
        <sql:param>200</sql:param>
    </sql:update>
</sql:transaction>
```

#### Functions 标签库

提供了一组常用的 EL 函数，主要用于处理字符串，在 JSP 中可以直接使用这些函数

###### fn:contains

判断源字符串中是否包含目标字符串

```jsp
// source 指定源字符串，target 指定目标字符串，返回 bool
fn:contains(String source, String target)
${fn:contains("Tomcat", "cal")} // true
${fn:contains{"Tomcat", "CAT"}} // false
```

###### fn:containsIgnoreCase

判断源字符串中是否包含目标字符串，判断时忽略大小写

```jsp
fn:containsIgnoreCase(String source, String target);
```

###### fn:startsWith

判断源字符串是否以指定的目标字符串开头

```jsp
fn:startsWith(String source, String target); // bool
```

###### fn:endsWith

判断源字符串是否以目标字符串结尾

```jsp
fn:endsWith(String source, String target); // bool
```

###### fn:indexOf

在源字符串中查找目标字符串，并返回源字符串（第一个字符索引未 0）中最先与目标字符串匹配的第一个字符的索引，如果源字符串中不包含目标字符串，返回 -1

```jsp
fn:indexOf(String source, String target); //int
```

###### fn:replace

把源字符串中的一部分替换为另外的字符串，并返回替换后的字符串

```jsp
// source 参数指定源字符串，before 参数指定源字符串中被替换的子字符串，after 指定用于替换的子字符串
fn:replace(String source, String before, String after); // String
${fn:replace("TomcAT", "cAt", "cat")} // Tomcat
```

###### fn:substring

用于获取源字符串中的特定子字符串

```jsp
// source 源字符串，beginIndex 表示子字符串中第一个字符在源字符串中的索引（以0开始），endIndex 参数表示子字符串的最后一个字符在源串中的索引加 1，返回 string
fn:substring(String source, int beginIndex, int endIndex);
${fn:substring("Tomcat", "3", "6")} // Tom
```

###### fn:subStringBefore

获取源字符串中指定子字符串之前的子字符串

```jsp
// source 指定源字符串，target 指定子字符串，返回 String，如果不包含特定字串，返回空字符串
fn:subStringBefore(String source, String target)
${fn:subStringBefore("Tomcat", "cat")} // Tom
```

###### fn:subStringAfter

获取源字符串中指定字符串之后的子字符串

```jsp
fn:subStringAfter(String source, String target) // String
${fn:subStringAfter("Tomcat", "Tom")} // cat
```

###### fn:split

用于将源字符串拆分为一个字符串数组

```jsp
// source 源字符串，delimiter 分隔符
fn:split(String source, String delimiter) // String[]
<c:set value='${fn:split("www.javathinker.net", ".")}' var="strs"/>
<:forEach var="token" items="${strs}">
	${token}
</:forEach>
```

###### fn:join

用于将源字符串数组中的所有字符串连接为一个字符串

```jsp
fn:join(String source[], String separator) // String
<%
	String strs[] = {"www", "java", "com"};
%>
<c:set value="<%=strs%>" var="strs" />
${fn:join(strs, ".")} // www.java.com
```

###### fn:toLowerCase

将源字符串转换为小写字符串

```jsp
fn:toLowerCase(String source); // String
${fn:toLowerCase("TOMCAT")} // tomcat
```

###### fn:toUpperCase

用于将源字符串中的所有字符改为大写

```jsp
fn:toUpperCase(String source) // String
${fn:toUpperCase(Tomcat)} // TOMCAT
```

###### fn:trim

将源字符串中开头和末尾的空格删除

```jsp
fn:trim(String source) // String
${fn:trim(" Tom ")} // Tom
```

###### fn:escapeXml

用于将源字符串中的字符 `<`、`>`、`"`、`&` 转义

```jsp
fn:escapeXml(String source) // String
```

###### fn:length

用于返回字符串中字符的个数，或者集合、数组中元素的个数

```fn
fn:length(source) // int
```



