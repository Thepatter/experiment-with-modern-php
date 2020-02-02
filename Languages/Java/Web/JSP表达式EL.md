### EL  表达式

JSP 2.0 支持表达式语言（EL），JSP 用户可以用它来访问应用程序数据。用来替代传统的基于 `<%=%>` 的表达式，以及部分 `<%%>` 形式程序片段

#### 表达式语法

##### 基本形式及操作符 

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

##### 表达式取值规则

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

##### 访问 JavaBean

  使用 `.` 或 `[]` 来访问 bean 属性及属性对象的属性

##### EL 隐式对象

在 JSP 页面中，可以利用 JSP 脚本来访问 JSP 隐式对象。但是，在免脚本的 JSP 页面中，则不可能访问这些隐式对象。EL 允许通过提供一组它自己的隐式对象来访问不同的对象

###### `pageContent`

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

###### `initParam`

获取上下文参数的值，包含所有环境初始化参数 Map 对象

###### `param`

获取请求参数 Map 每个 `key` 的值就是指定名称的第一个参数值。如果两个请求参数同名，则只有第一个能够利用 `param` 取值。用 `params()` 访问同名参数的所有参数值

###### `paramValues`

获取请求参数的所有值的 Map，参数名称为 key，值为字符串数组，包含对应 key 的所有值，如果该 key 对应只有一个 值，返回一个元素的数组

###### `header`

包含请求 header，并用 header 名作为 key 的 Map，每个 key 的值就是指定标题名称的第一个标题。如果一个标题的值不止一个，则只返回第一个值。获得多个值的标题，需用 `headerValues` 对象替代

###### `headerValues`

包含请求标题，并用标题名作为 key 的 Map。每个 key 的值就是一个字符串数组，其中包含了指定标题名称的所有参数值

###### `cookie`

包含当前 `HttpServletRequest` 中所有 Cookie 对象的 Map。Cookie 名称就是 key 名称，并且每个 key 都映射到一个 Cookie 对象。 `${cookie.jsessionid.value}`

###### `applicationScope`

包含web应用中所有属性的 Map，并用属性名称作为 key

###### `sessionScope`

包含 `HttpSession` 对象中所有属性的 Map，并用属性名称作为 key

###### `requestScope`

包含了当前 `HttpServletRequest` 对象中的所有属性，属性名为 key 的 Map

###### `pageScope`

包含全页面范围内的所有属性，属性名称为 key 的 Map

##### 命名变量

EL 表达式中的变量称为命名变量， 它不是 JSP 文件中的局部变量或实例变量，而是存放在特定范围内的属性，命名变量的名字和特定范围内的属性名对象

#### 配置 EL

##### 免脚本的 JSP 页面

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

##### 禁用 EL 计算

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

#### 定义和使用 EL 函数

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

