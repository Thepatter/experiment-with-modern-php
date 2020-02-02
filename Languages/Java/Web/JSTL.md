### JSTL

Oracle 公司指定了一组标准标签库的最新规范，由[apache 实现](http://tomcat.apache.org/taglibs/standard/)，这组标准标签库简称 JavaServer Pages Standard Tag Library JSTL。项目要引入第三方开发的标签库（第三方标签库打包为一个 Jar 文件，这个 Jar 文件包含：所有标签处理类以及相关类的 `.class` 文件，META-INF 目录，该目录下有一个描述标签库的 TLD 文件）流程：

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



