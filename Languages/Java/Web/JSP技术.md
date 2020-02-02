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

#### 自定义 JSP 标签

##### JSP 标签形式

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
2. 创建TLD标签库描述文件 (Tag Library Descriptor)

在 web 应用中使用自定义标签库步骤：

1. 把标签处理类及相关类的 `.class` 文件存放在 `WEB-INF\classes` 目录下
2. 把 TLD 标签库描述文件存放在 `WEB-INF` 目录或其自定义子目录下
3. 在 `web.xml` 文件中声明所引用的标签库
4. 在 JSP 文件中使用标签库中的标签

##### JSPTag API

 Servlet 容器允许 JSP 文件时，如果遇到自定义标签，就会调用这个标签的处理类(Tag Handler Class)的相关方法。标签处理类可以继承 JSP Tag API 中的 `TagSupport` 类或 `BodyTagSupport` 类

所有的标签处理类都要实现 `JspTag` 接口，在 JSP 2.0 之前，所有的标签处理类都要实现 tag 接口，实现该接口的标签称为传统标签，JSP 2.0 提供了 SimpleTag 接口，实现该接口的标签称为简单标签。

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

1. Servlet 容器调用 Tag 对象的 `setPageContext()` 和 `setParent()` 方法，把当前 JSP 页面的 `PageContext` 对象以及父标签处理对象传给当前 Tag 对象。如果不存在父标签，则把父标签处理对象设置为 null
2. Servlet 容器调用 Tag 对象的一系列 set 方法，设置  Tag 对象的属性。如果标签没有属性，则无须这个步骤
3. Servlet 容器调用 Tag 对象的 `doStartTag()` 方法
4. Servlet 容器调用 Tag 对象的 `doEndTag()` 方法
5. 如果 `doEndTag` 方法返回 `Tag.SKIP_PAGE`，就不执行标签后续的 JSP 代码；如果 `doEndTag()` 方法返回 `Tag.EVAL_PAGE`，就执行标签后续的 JSP 代码

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

#### 简单标签

JSP 2 引入了一种新的标签扩展机制，简单标签扩展，这种机制有两种使用方式

* 定义实现 `javax.servlet.jsp.tagext.Simple` 接口的标签处理器
* 使用标签文件来定义标签，标签文件以 `.tag` 或 `.tagx` 作为扩展名

##### 实现 SimpleTag 接口

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

##### 使用标签文件

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