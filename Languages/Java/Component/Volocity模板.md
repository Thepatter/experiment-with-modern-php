### Velocity 模板

#### 简介

基于 Java 的模板引擎，可以通过 Velocity Template Language VTL 定义模板，在模板中不包含任何 Java 代码。Java 编写程序代码来设置上下文，它包含用于填充模板的数据。Velocity 引擎能够把模板和上下文合并生成动态网页

VTL 模板和 JSP 网页的区别在于：VTL 模板中不包含任何 Java 代码，并且 VTL 模板不用经过 JSP 编译器的编译，VTL模板的解析是由 Velocity 引擎来完成的

在 Velocity 模板语言中，`$` 符号后字符串为变量，以 `.vm` 作为模板文件结尾。在 Velocity API 中提供了 org.apache.velocity.tools.view.VelocityViewServlet 类，它是 HttpServlet 类的子类。它的 `handleRequest()` 方法类似 HttpServlet 类的 `doGet` 类型方法，只需继承 VelocityViewServlet 类并覆写 `handleRequest()` 方法

*add.vm*

```html
<p>
    $a + $b = $c
</p>
```

对应 servlet

```java
public class AddServlet extends VelocityViewServlet{
    // Context 类用来存放所有用于显示到 HTML 页面上的文件
  public Template handleRequest(HttpServletRequest request, HttpServletResponse response,Context context){
    int a=11;	
    int b=22;
    int c=a+b;
    context.put("a",Integer.valueOf(a));
    context.put("b",Integer.valueOf(b));
    context.put("c",Integer.valueOf(c));
    return getTemplate("add.vm");
  }
}
```

#### 配置

vm 模板对应 Servlet 作为 VelocityViewSerevlet 类的子类，有一个初始化参数 org.apache.velocity.properties，表示 velocity 属性文件的文件路径，默认值为 `/WEB-INF/velocity.properties`。

*web.xml中配置*

```xml
<servlet>
    <servlet-name>add</servlet-name>
    <servlet-class>mypack.AddServlet</servlet-class>
    <init-param>  
      <param-name>org.apache.velocity.properties</param-name>  
      <param-value>/WEB-INF/velocity.properties</param-value>  
    </init-param>  
</servlet>
```

*velocity.properties*

```properties
# 为资源加载器设定一个公共名字
resource.loader=webapp  
# 设定资源加载器类（加载模板文件）
webapp.resource.loader.class=org.apache.velocity.tools.view.WebappResourceLoader 
# 设定模板文件的根路径
webapp.resource.loader.path=/vm  
```

#### VTL 语法

##### 引用

###### 变量引用

变量引用类似 php 语法变定义。

```velocity
## 定义变量 
$foo
## 模板复制
#set($foo = "bar")
```

###### 属性引用

```velocity
## 属性
$cline.firstname
```

给引用属性赋值：

* 在 Java 程序代码中创建一个 Hashtable 对象，把所有属性保存在 keyValue 结构对象中（hashtable, Map)，再把 Hashtable 对象保存在 Context 对象中。

  ```java
  public Template handleRequest(HttpServletRequest req, HttpServletResponse res, Context context) {
      Hashtable<String, String> client = new Hashtable<>();
      client.put("firstname", "zhangsan");
      client.put("age", 19);
      context.put("client", client);
      return getTemplate("properties.vm");
  }
  ```

* 定义一个 JavaBean 类，在这个类中定义 Client 的各种属性，以及相应的 get 和 set 方法。然后在 Java 代码中创建一个 Client 对象，调用 set 设置属性，再把 Client 对象保存在 Context 对象中

  ```java
  public Template handleRequest(HttpServletRequest req, HttpServletResponse res, Context context) {
      Client client = new Clinet();
      client.setFirstname("zhangsan");
      client.setAge(20);
      context.put("client", client);
      return getTemplate("properties.vm");
  }
  ```

###### 方法引用

方法在 Java 程序代码中定义，VTL 中方法引用为 `$` 后跟一个 VTL 方法体。

```velocity
$customer.getAddress();
$purchase.setAttributes("value");
$client.getFirstname();
## 或
${customer.phone}
${purchase.getTotal()}
```

###### 安静引用符

当 Velocity 遇到一个未赋值的引用时，会直接输出这个引用的名字，使用安静引用符（Quiet Reference Notation）可以绕过 Velocity 的常规行为，显示空白字符

```velocity
<input type="text" name="email" value="$!email" />
```

###### 范围

范围可以包含 Integer 对象的数组，形式为 [n...m]，n 和 m 都必须是整数，m 小于 n，数组下标从大到小计数，范围只有和 #set，#foreach 指令使用时才代表 Integer 数组，其余时候为普通字符串

##### 指令

###### #set

为引用变量或引用属性赋值

```velocity
#set($primate = "monkey")
#set($customer.Behavior = $primate)
```

如果赋值表达式的右边是一个属性或方法引用，并且取值为 null，在这种机制下，给一个已经赋值的引用变量重新赋值可能会失败

```velocity
name = #set($result = $query.criteria("name"))  # name = Linda
address = #set($result = $query.criteria("address")) # address = null
## 输出
name = Linda
address = Linda
## 预设 result 为 false 解决 null 问题
#set($criteria = ["name", "address"])
#foreach($criterion in $criteria)
	#set($result = false)
	#set($result = $query.criteria($riterion))
	#if($result)
		Query was successful.
		$criterion is $result
	#else
		Query was unsuccessful.
		$criterion is unknown.
	#end
#end
```

###### 字面字符串

使用 #set 指令时，在双引号中的字面字符串将被解析

```velocity
#set($directoryRoot = "www")
#set($templateName = "index.vm")
#set($template = "$directoryRoot/$templateName")
$template # www/index.vm
```

###### #if

当 #if 指令中的 if 条件为真时(逻辑类型变量，并且值为 true，或值非空)，Velocity 将输出 #if 代码块包含的文本

```velocity
#if ($foo > 100)
	<strong>Velocity!</strong>
#elseif ($foo = 10)
	<p>Go path</p>
#else
	<p>Go else<p>
#end
```

###### 比较运算

在 if 条件表达式中，Velocity 支持 3 种变量类型的比较运算：字符串比较、对象比较和数字比较

* 字符串比较

  字符串比较使用 `==` 操作符

* 对象比较

  对象比较使用 `==` ，只有当两边的引用变量引用同一个对象时，才为 true

* 数字比较

  支持 `=, >, <` 比较

###### #foreach

可以遍历数组，collection 等

```velocity
#foreach($client in $clientlist)
	<p>$client.firstname</p>
#end
```

###### #include

导入本地文件，这些文件将插入到模板中 #include 指令被定义的地方，指令引用的文件名放在双引号内，如果引用多个用逗号隔开

```velocity
#include("one.gif", "two.txt", "three.htm")
```

###### #macro

在 VTL 模板中定义重复的段，即 Velocity 宏。把模板中重复的代码定义在一个 Velocity 宏中，在模板中所有出现重复代码的地方都可以用宏来代替，所有合法的 VTL 模板的内容都可以作为 Velocity 宏的主体部分，支持参数（引用、字符串、数字、范围、对象数组、布尔）

```velocity
## 无参
#macro(mymacro)
<tr><td></td></tr>
#end
## 有参
#macro(tablerows $color $somelist)
	#foreach($something in $somelist)
		<tr><td bgcolor=$color>$something</td></tr>
	#end
#end
## 调用宏
#set($greatlaks = ["superior", "michigan", "daron"]))
#set($color = "blue")
<table>
	$tablerows($color $greatlakes)
</table>
```

###### 算术运算

支持整型和浮点型的算法运算符 `+ - * / %`

###### 字符串

* 字符串连接

  直接将字符串放在一起

  ```velocity
  #set($size = "big")
  #set($name = "xiaozhang")
  The clock is $size$name
  ```

  

