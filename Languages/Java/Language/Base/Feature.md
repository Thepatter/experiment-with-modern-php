### 语言特性

#### Reflect

##### 反射机制

能够分析类能力的程序成为反射，反射机制可以用来：

* 在运行时分析类的能力
* 在运行时查看对象
* 实现通用的数组操作代码
* 利用 *Method* 对象

使用反射的主要人员是工具构造者，而不是应用程序员。

反射机制的默认行为受限于 java 的访问控制。如果一个 java 程序没有受到安全管理器的控制，就可以覆盖访问控制。

##### 反射包

java.lang.reflect 包中主要类是 

* *Class*

  类信息

  在程序运行期间，java 运行时系统始终为所有的对象维护一个被称为运行时的类型标识。这个信息跟踪着每个对象所属的类。一个 Class 对象实际上表示的是一个类型，而这个类型未必一定是一种类，int.class 是一个 *Class* 对象，但 int 不是类。

  获取 *Class* 对象：*Object*.getClass()、T.class、*Class*.forName(String className)、*ClassLoader*.loadClass(String className)

* *Method*

  类方法

* *Field*

  类的域

* *Constructor*

  类构造器

*Method*、*Construct* 继承了 *java.lang.reflect.Executable*，*Field*、*Executable* 继承了 *java.lang.reflect.AccessibleObject*

#### 异常

##### 异常处理机制

在程序运行过程中，如果 jvm 检测出一个不可能执行的操作，就会出现运行时错误 (runtime error)，在 java 中运行时错误会作为异常抛出，异常是一种对象，表示阻止正常进行程序执行的错误或情况，如果异常没有被处理，那么程序就会非正常终止

异常从方法抛出，方法的调用者可以捕获以及处理该异常，使用 throw 语句抛出一个异常。

##### 异常类型

异常对象派生于 *java.lang.Throwable*，所有的异常都直接或间接继承该类

###### Error

*Error* 类层次结构描述了 java 运行时系统的内部错误和资源耗尽错误。应用程序不应该抛出这种类型的对象。如果发生，除了通知用户以及尽量稳妥的终止程序外，几乎什么都不能做

###### Exception

*Exception* 层次结构分为：

* *RuntimeException*

  由程序错误导致的异常

* other

  非程序本身，如网络

java 语言规范将派生于 *Error* 或 *RuntimeException* 的所有异常称为非受检异常，其他异常为受检异常。

##### 声明受检异常

方法应该在其首部声明所有可能抛出的异常，使用 throws 声明所有异常类，用逗号分隔。如果方法没有声明所有可能发生的受检异常，编译无法通过。

如果在子类中覆盖了超类的一个方法，子类方法中声明的受检异常不能比超类方法中声明的异常更通用。如果超类方法没有抛出任何受检异常，子类也不能抛出任何异常。

##### 抛出异常

1. 找到一个合适的异常类
2. 创建这个类的一个对象
3. 使用 throw 关键字抛出异常

一旦方法抛出了异常，这个方法就不能返回到调用者

##### 自定义异常类

要自定义异常，需要继承 *Exception* 或其子类，应该包含默认构造器，和带有描述信息的构造器。

##### 捕获异常

如果某个异常发生的时候没有在任何地方进行捕获，程序便会终止执行，并在控制台上打印出异常信息，其中包括异常的类型和堆栈。

使用 try/catch 语句块捕获异常：

* 如果在 try 语句块中的任何代码抛出了一个在 catch 子句中说明的异常类，程序将跳过 try 语句块的其余代码，程序将执行 catch 子句找你的处理代码。
* 如果在 try 语句块中的代码没有抛出任何异常，程序将跳过 catch子句

一个 try 语句块中可以捕获多个异常类型，SE 7 开始，同一个 catch 子句中可以捕获多个异常类型（当捕获的异常类彼此之间不存在子类关系时才需要这个特性），捕获多个异常时，细化异常类型在前

如果覆写超类的方法，而这个方法又没有抛出异常，那么这个方法就必须捕获方法中的每一个受检异常

在 catch 子句中可以抛出异常，这样可以改变异常的类型

```java 
catch(SQLException e) {
  	Throwable se = new ServletException("database error");
  	se.initCause(e);
  	throw se;
}
// 捕获异常时，获取原始异常
Throwable e = se.getCause();
```

##### finally 子句

在任何情况下，finally 块中的代码都会执行，不论 try 块中是否出现异常或者是否被捕获

* 如果 try 块中没有出现异常，执行 finally 块然后执行 try 语句的下一条语句
* 如果 try 块中有一条语句引起异常，并被 catch 块捕获，然后跳过 try 块的其他语句，执行 catch 块和 finally 子句。执行 try 语句之后的下一条语句
* 如果 try 块中有一条语句引起异常，但是没有被任何 catch 块捕获，就会跳过 try 块中的其他语句，执行 finally 子句，并且将异常传递给这个方法的调用者

不要在 finally 里 return，且不要出现无法访问语句

##### 异常机制良好实践

* 异常处理不能代替测试即只在异常情况下使用异常机制，因为捕获异常时间更久
* 不过分细化异常导致代码量膨胀
* 利用异常层次结构，不要只抛出 *RuntimeException* 异常，不要只捕获 *Thowable*
* 不要压制异常
* 早抛出，晚捕获（抛出异常到上层方法及应用）

##### 异常执行顺序

```java
try{} catch() {} finally{} return;
```

顺序执行

```java
try {return;} catch() {} finally{} return;
```

程序执行 `try` 里 `return` 代码块前的代码，出现异常执行 `catch` 中的代码，然后执行最后的 `return` 代码；程序执行 `try` 里 `return` 代码块前的代码，不出现异常则将先执行 `finally` 里代码，然后执行 `try` 里的 `return` 代码，最后的 `return` 语句不会执行

```java
try {} catch() {return;} finally {} return;
```

程序执行 `try` 出现异常则执行 `catch` 代码，然后执行 `finally` 代码，返回 `catch` 中的 `return` 返回值，最后的 `return` 不会执行；程序执行 `try` 不出现异常，然后执行 `finally` 语句，执行最后的 `return` 语句

```java
try {return;} catch() {return;} finally {return;}
```

`finally` 中不要包含 `return` 语句，否则就始终返回 `finally` 里 `return`  返回值

```java
try {return;} catch() {return;} finally {} return;
```

编译错误，不可访问错误，最后的 `return` 语句不会被访问

#### 断言

##### 断言机制

断言机制允许在测试期间向代码中插入一些检查语句。当代码发布时，这些插入的检测语句将会自动地移走。关键字 assert ：

```java
assert Condition;
// 或
assert Condition : expression;
```

这两种形式都会对条件进行检测，如果结果为 false，则抛出一个 *AssertionError* 异常。在第二种形式中，表达式将被传入 *AssertionError* 的构造器，并转换成一个消息字符串。（表达式部分的唯一目的是产生一个消息字符串。*AssertionError* 对象并不存储表达式的值，因此，不可能在以后得到它）

##### 启用和禁用断言

在默认情况下，断言被禁用

* 启用

  在运行程序时用 -enableassertions 或 -ea 选项启用，在启用或禁用断言时不必要重新编译程序。

* 禁用

  运行时使用 -da 禁用，类加载器将跳过断言代码，因此，不会降低程序运行的速度

由于可以使用断言，当方法被非法调用时，将会出现难以预料的结果，有时抛出断言错误，有时产生 null 指针异常，完全取决于类加载器的配置

#### 日志

##### 基本日志

要生成简单的日志记录，可以使用全局日志记录器并调用其 info 方法

```java
// 记录日志
Logger.getGlobal().info("File->Open menu item selected");
// 取消日志
Logger.getGlobal().setLevel(Level.OFF);
```

##### 日志配置

可以通过修改配置文件来修改日志系统的属性，jdk11 配置文件位于 `$JAVA_HOME/conf/logging.properties`，

```shell
# 要使用另一个配置文件，将 java.util.logging.config.file 特性设置为配置文件存储位置
java -Djava.util.logging.config.file=configFile MainClass
```

日志管理器在 VM 启动过程中初始化，这在 main 执行之前完成，如果在 main 中调用 *System*.setProperty("java.util.logging.config.file", file)，也会调用 *LogManager*.readConfiguration() 来重新初始化日志管理器

*my_log.properties*

```properties
# 日志记录级别 
my_log.properties.level = WARNING
# 设置 handler，控制台输出
handlers= java.util.logging.ConsoleHandler
```

#### 泛型

##### 泛型机制

普通的类和方法，只能使用具体的类型；要们是基本类型，要么是自定义的类。在面向对象中，多态算是一种泛化机制。SE 5 引入泛型实现了参数化类型的概念。使代码能够应用于某种不具体的类型，而不是一个具体的接口或类。创建参数化类型的一个实例时，编译器会负责转型操作，并且保证类型的正确性。

泛型使用擦除来实现，当使用泛型时，任何具体的类型信息都被擦出了。

###### 泛型类

泛型类作用：

* 创造容器类

* 元祖类

  在需要返回多个对象的情况下，可以创建一个对象，用它来持有想要返回的多个对象，使用泛型，在编译期就能确保类型安全。（元祖（信使）：将一组对象打包存储于其中一个单一对象，这个容器对象允许读取其中元素，但是不允许向其中存放新的对象）

一个泛型类就是具有一个或多个类型变量的类。类型变量使用尖括号 <> 括起来，放在类名的后面。使用泛型类时，必须在创建对象时指定类型参数的值。

类型变量使用大写形式：

* E 表示集合的元素类型
* K 和 V 分别表示表的关键字与值的类型
* T、U、S 表示任意类型

泛型类可看作普通类的工厂，SE 7 及以后的版本中，表达式右边的类型变量可以省略。

###### 泛型接口

泛型可以应用于接口，如生成器，实际上，这是工厂方法设计模式的一种应用。

###### 泛型方法

泛型方法可以定义在普通类中，也可以定义在泛型类中，用尖括号 <> 修饰类型变量。类型变量放在修饰符的后面，返回类型的前面。调用一个泛型方法时，在方法名前的尖括号中放入具体的类型。

泛型方法使得该方法能够独立于类而产生变化，<u>无论何时，只要能做到，就应该尽量使用泛型方法，如果使用泛型方法可以取代整个类泛型化，就应该只使用泛型化方法。对于 static 方法而言，无法访问泛型类的类型参数，所以如果 static 方法需要使用泛型能力，就必须使其成为泛型方法</u>

大多数情况下，调用泛型方法可以省略尖括号中类型参数。编译器有足够的信息能够推断出所调用的方法。

##### 泛型特性

###### 边界

边界使得可以在用于泛型的参数类型上设置限制条件。尽管可以强制规定泛型可以应用的类型，但是其潜在的一个更重要的效果是可以按照自己的边界类型来调用方法。

因为擦除移除了类型信息，所以，可以用无界泛型参数调用的方法只是那些可以用 Object 调用的方法。但是，如果能够将这个参数限制为某个类型子集，就可以用这些类型子集来调用方法。为了这些这种限制，java 泛型重用了 extends 关键字。extends关键字在泛型边界上下文环境中和在普通情况下所具有的意义是完全不同的

* extends

  ```java
  // 具有从 Dimension 和 HasColor 和 Weight 继承或实现的类型
  <T extends Dimension & HasColor & Weight>
  // 具有任何从 Fruit 继承的类型的列表
  List<? extends Fruit>
  ```

* super

  ```java
  // 声明通配符是由某个特定类的任何基类来界定
  <? super MyClass>
  <? super T>
  ```

* ?

  无界通配符，声明：用 java 的泛型来编写这段代码，并不是要用原生类型，在当前这种情况下，泛型参数可以持有任何类型

* &

  指定必须实现多个类型

###### 类型擦除

无论何时定义一个泛型类型，都自动提供了一个相应的原始类型。原始类型的名字就是删去类型参数后的泛型类型名。

原始类型用第一个限定的类型变量来替换，如果没有给定限定就用 Object 替换。

* 使用强制转换一个对象为泛型会得到警告，使用 instanceof 测试对象是否为泛型类时会发生编译错误
* 不能创建参数化类型的数组
* 不能在静态域和方法中引用类型变量
* 不能抛出或捕获泛型类的实例，泛型类也不能直接或间接继承自 Throwable 
* 任何基本类型都不能作为类型参数。
* 一个类不能实现同一个泛型接口的两种变体，由于擦除的原因，这两个变体会成为相同的接口

<u>在泛型代码内部，无法获得任何有关泛型参数类型的信息</u>，如果打印泛型类的参数只能得到参数占位符的标识

###### 自限定的类型

```java
// SelfDounded 类接受泛型参数 T，而 T 由一个边界类限定，这个边界就是拥有 T 作为其参数的 SelfBounded
class SelfBounded<T extends SelfBounded<T>> {}
```

自限定将采取额外的步骤，强制泛型当作其自己的边界参数来使用。强制要求将正在定义的类当作参数传递给基类，它可以保证类型参数必须与正在被定义的类型相同

###### 参数协变

方法参数类型会随着子类而变化。还可以产生于子类类型相同的返回类型（SE 5 中引入）

```java
// 返回子类型
class Base {}
class Derived extends Base{}
interface OrdinaryGetter { Base get(); }
interface DerivedGetter extends OrdinaryGetter { @Override Derived get(); }
```

#### 注解

##### 使用注解

注解是那些插入到源代码中使用其他工具可以对其进行处理的标签。这些工具可以在源码层次上进行操作，或者可以处理编译器在其中放置了注解的类文件。

注解不会改变程序的编译方式。Java 编译器对于包含注解和不包含注解的代码会生成相同的虚拟机指令                                                                                  

为了能够受益于注解，需要选择一个处理工具，然后向处理工具可以理解的代码中插入注解，之后运用该处理工具处理代码。注解的一些可能的用法

* 附属文件的自动生成，例如部署描述符或 `bean` 信息类
* 测试、日志、事务语义等代码的自动生成

在 Java 中，注解是当作一个修饰符来使用的，它被置于被注解项之前，中间没有分号，每一个注解的名称前都加了 `@` 符号，类似于 `Javadoc` 的注释，`Javadoc` 注释出现在注释符内部，而注解是代码的一部分

除了方法外，还可以注解类、成员以及局部变量，这些注解可以存在于任何可以放置一个像 `public` 或者 `static` 这样的修饰符的地方。还可以注解包、参数变量、类型参数和类型用法。每个注解都必须通过一个注解接口进行定义，这些接口中的方法与注解中的元素相对应。

`JUnit` 的注解 `Test` 可以用下面的接口进行定义：

```java
@Target(ElementType.METHOD)
@Retention(RetentionPolicy.RUNTIME)
public @interface Test {
    long timeout() default 0L;
}
```

`@interface` 声明创建了一个真正的 Java 接口。处理注解的工具将接收那些实现了这个注解接口的对象。这类工具可以调用 `timeout` 方法来检索某个特定 `Test` 注解的 `timeout` 元素。注解 `Target` 和 `Retention` 是元注解。它们注解了 `Test` 注解，即将 `Test` 注解标识成一个只能运用到方法上的注解。并且当类文件载入到虚拟机的时候，仍可以保留下来。

##### 注解语法

##### 注解接口

注解是由注解接口来定义的：

```java
mofifiers @interface AnnotationName {
    elementDeclaration1
    elementDeclaration2
}
```

每个元素声明都具有下面这种形式：

```java
type elementName();
```

或者

```java
type elementName() default value;
```

```java
// 下面这个注解具有两个元素：assignedTo 和 severity
public @interface BugReport {
    String assignedTo() default "[none]";
    int severity;
}
```

所有的注解接口都隐式地扩展 `java.lang.annotation.Annotation` 接口。这个接口是一个常规接口，不是一个注解接口。无法扩展注解接口，即所有的注解接口都直接扩展自 `java.lang.annotation.Annotation`

##### 注解类型用法

声明注解提供了正在被声明的项的相关信息。

##### 标准注解

Java SE 在 `java.lang`、`java.lang.annotation`、`javax.annotation` 包中定义了大量的注解接口。其中四个是元注解，用于描述注解接口的行为属性，其他的三个是规则接口，用它们来注解源代码中的项

`Deprecated`   应用于全部，将项标记为过时的

`SuppressWarnings` 除了包和注解之外的所有情况，阻止某个给定类型的警告信息

`SafeVarargs` 方法和构造器，断言 `varargs` 参数是安全使用

`Override` 方法，检查该方法是否覆盖了某一个超类方法

`FunctionalInterface`接口，将接口标记为只有一个抽象方法的函数式接口

`PostConstruct` 、`PreDestroy` 方法，被标记的方法应该在构造之后或移除之前立即被调用

`Resource` 类，接口、方法、域，在类或接口上；标记为在其他地方要用到的资源，在方法或域上；为注入而标记

`Resources` 类、接口，一个资源数组

`Generated` 全部

`Target` 注解，指明可以应用这个注解的那些项

`Retention` 注解，指明这个注解可以保留多久

`Documented` 注解，指明这个注解应该包含在注解项的文档中

`Inherited` 注解，指明当这个注解应用于一个类的时候，能够自动被它的子类继承

`Repeatable` 注解，指明这个注解可以在同一个项上应用多次

##### 用于编译的注解

`@Deprecated` 注解可以被添加到任何不再鼓励使用的项上。所以，当你使用一个已过时的项时，编译器将会发出警告。这个注解与 `Javadoc` 标签 `@deprecated` 具有同等功效。

`@SuppressWarnings` 注解会告知编译器阻止特定类型的警告信息

`@Override` 这种注解只能应用到方法上。编译器会检查具有这种注解的方法是否真正覆盖了一个来自于超类的方法

`@Generated` 注解的目的是提供代码生成工具来使用。任何生成的源代码都可以被注解，从而与程序员提供的代码区分开。

##### 用于管理资源的注解

`@PostConstruct` 和 `@PreDestroy` 注解用于控制对象生命周期的环境中，如 `web` 容器和应用服务器。标记这些注解的方法应该在对象被构建之后，或者在对象被移除之前，紧接着调用

`@Resource` 注解用于资源注入。如，访问数据库的 `web` 应用。当然，数据库访问信息不应该被硬编码到 `Web` 应用中。而是应该让 `Web` 容器提供某种用户接口，以便设置连接参数和数据库资源的 `JNDI` 名字。

```java
@Resource(name="jdbc/mydb")
private DataSource source;
```

当包含这个域的对象被构造时，容器会“注入”一个对该数据源的引用

##### 元注解

`@Target` 元注解可以应用于一个注解，以限制该注解可以应用到那些项上。一条没有 `@Target` 限制的注解可以应用于任何项上。编译器将检查是否将一条注解只应用到了某个允许的项上。

`@Retention` 元注解用于指定一条注解应该保留多长时间

`@Documented` 元注解为像 `Javadoc` 这样的归档工具提供了一些提示。

`@Inherited` 元注解只能应用于对类的注解。如果一个类具有继承注解，那么它的所有子类都自动具有同样的注解，这使得创建一个与 `Serializable` 这样的标记接口具有相同运行方式的注解变得很容易

实际上，`@Serializable` 注解应该比没有任何方法的 `Serializable` 标记接口更适用。一个类之所以可以被序列化，是因为存在着对它的成员域进行读写的运行期支持，而不是因为任何面向对象的设计原则。注解比接口继承更擅长描述这一事实

#### lambda

##### lambda 表达式语法

参数，箭头（`->`）以及一个表达式。

```java
(String first, String second)
    ->first.length() - seconde.length()
```

如果代码要完成的计算无法放在一个表达式中，就可以像写方法一样，把这些代码放在 `｛｝`  中，并包含显式的 `return` 语句。

```java
(String first, String second)->
	{
        if (first.length() < second.length()) {
            return 1;
        } else {
            return 0;
        }
	}
```

即使 `lambda` 表达式没有参数，仍然要提供空括号，就像无参数方法一样。

```Java
()->{
    for (int i = 100; i )
}
```

如果可以推导出一个 `lambda` 表达式的参数类型，则可以忽略其类型。如果方法只有一个参数，而且这个参数的类型可以推导得出，那么可以省略小括号

```java
(first, second)->first.length() - second.length()
```

对于只有一个抽象方法的接口，需要这种情况的对象时，可以提供一个 lambda 表达式。这种接口即函数式接口

#### 时间日期

##### 时间线

Java 的 Date 和 Time API 规范要求 Java 使用的时间尺度为：

- 每天 86400 秒
- 每天正午与官方时间精确匹配
- 在其他时间点上，以精确定义的方式与官方时间接近匹配

在 Java 中，`Instant` 表示时间线上的某个点。被称为“新纪元”的时间线原点被设置为UNIX初始时间。从该原点开始，时间按照每天 86400 秒向前或向回度量，精确到纳秒。`Instant` 的值向回可追朔 10 亿年（`Instant.MIN`）。最大值 `Instant.MAX` 是公元 `1000000000` 年的 12 月 31 日

静态方法调用 `Instant.now()` 会给出当前的时刻。可以按照常用的方式，用 `equals` 和 `compareTo` 方法来比较两个 `Instant` 对象，因此可以将 `Instant` 对象用作时间戳

为了得到两个时刻之间的时间差，可以使用静态方法 `Duration.between` 

```
  // 获取算法的运算时间
Instant start = Instant.now();
runAlgorithm();
Instant end = Instant.now();
Duration timeElapsed = Duration.between(start, end);
long millis = timeElapsed.toMillis();   
LocalDate today = LocalDate.now();
LocalDate alonzosBirthday = LocalDate.of(1903, 6, 14);
alonzosBirthday = LocalDate.of(1903, Month.JUNE, 14);
// 获取某个月的第一个星期二
LocalDate firstTuesday = LocalDate.of(year, month, 1).with(TemporaAdjusters.nextOrSame(DayOfWeek.TUESDAY));
// 计算下一个工作日的调整器
TemporalAdjuster NEXT_WORKDAY = w -> {
    LocalDate result = (LocalDate) w;
    do {
        result = result.plusDays(1);
    }
    while (result.getDayOfWeek().getValue() >= 6);
    return result;
}
LocalDate backToWork = today.with(NEXT_WORKDAY);
TemporalAdjuster Next_WORKDAY = TemporalAdjusters.ofDateAdjuster(w -> {
    LocalDate result = w;
    do {
        result = result.plusDays(1);
    }
    while (result.getDayOfWeek().getValue() >= 6);
    return result;
})
ZonedDateTime apollolllaunch = ZonedDateTime.of(1969, 7, 16, 9, 32, 0, 0, ZonedId.of("America/New_York"));
String formatted = DateTimeFormatter.ISO_OFFSET_DATE_TIME.format(apollolllaunch);
DateTimeFormatter formatter = DateTimeFormatter.ofLocalizedDateTime(FormatStyle.LONG);
String formatted = formatter.format(apollolllaunch);
formatted = formatter.withLocal(Locale.FRENCH).format(apollolllaunch);
for (DayOfWeek w: DayOfWeek.values()) {
    System.out.print(w.getDisplayName(TextStyle.SHORT, Locale.ENGLISH) + " ");
}
formatter = DateTimeFormatter.ofPattern("E yyyy-MM-dd HH:mm");
LocalDate churchsBirthday = LocalDate.parse("1903-06-14");
ZonedDateTime apollolllaunch = ZonedDateTime.parse("1969-07-16 03:32:00-0400", DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss xx"))
```

​	![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/time%E7%B1%BB%E4%B8%8E%E9%81%97%E7%95%99%E7%B1%BB%E4%B9%8B%E9%97%B4%E7%9A%84%E8%BD%AC%E6%8D%A2.png?lastModify=1581229804)

​	*java.time类与遗留类之间的转换*

另一个可用于日期和时间类的转换集位于 `java.sql` 包中。还可以传递一个 `DateTimeFormatter` 给使用 `java.text.Format` 的遗留代码

类似的，`ZonedDateTime` 近似于 `java.util.GregorianCalendar` ，在 Java 8 中，这个类有细粒度的转换方法。`toZonedDateTime` 方法可以将 `GregorianCalendar` 转换为 `ZonedDateTime` ，而静态的 `from` 方法可以执行反方向的转换

`Instant` 类近似于 `java.util.Date` 。在 Java 8 中，这个类有两个额外的方法：将 Date 转换为 `Instant` 的 `toInstant` 方法，以及反方向转换的静态的 `from` 方法

作为全新的创造，`Java Date` 和 `Time API`  必须能够与已有类之间进行互操作，特别是无处不在的 `java.util.Date`、`java.util.GregorianCalendar` 、`java.sql.Date/Time/Timestamp`

##### 与旧API交互

第一个调用使用了标准的 `ISO_LOCAL_DATE` 格式器，而第二个调用使用的是一个定制的格式器

为了解析字符串中的日期、时间值，可以使用众多的静态 `parse` 方法之一。

​	![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/%E6%97%A5%E6%9C%9F%E6%97%B6%E9%97%B4%E6%A0%BC%E5%BC%8F%E5%8C%96%E7%AC%A6%E5%8F%B7.png?lastModify=1581229804)

​	*常用的日期、时间格式的格式化符号*

会将日期格式化 `Wed 1969-07-16 09:32` 的形式。每个字母都表示一个不同的时间域，而字母重复的次数对应于所选择的特定格式

可以通过指定模式来定制自己的日期格式

```
java.time.format.DateTimeForMatter` 类被设计用来替代 `java.util.DateFormat` 如果为了向后兼容性而需要后者的示例，那么可以调用 `formatter.toFormat()
```

`DayOfWeek` 和 `Month` 枚举都有 `getDisplayName` 方法，可以按照不同的 `Locale` 和格式给出星期日期和月份的名字

这些方法使用了默认的 `Locale` 。为了切换到不同的 `Locale` ，可以直接使用 `withLocale` 方法。

静态方法 `ofLocalizedDate`、`ofLocalizedTime` 、`ofLocalizedDateTime` 可以创建这种格式器

​	![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/Locale%E7%9B%B8%E5%85%B3%E7%9A%84%E6%A0%BC%E5%BC%8F%E5%8C%96%E9%A3%8E%E6%A0%BC.png?lastModify=1581229804)

​	*Locale* 相关的格式化风格

标准格式器主要是为了机器刻度的时间戳而设计的。为了向人类读者表示日期和时间，可以使用 `Locale` 相关的格式器。对于日期和时间而言，有 4 种与 `Locale` 相关的格式化风格，即 `SHORT`、`MEDIUM`、`LONG`、`FULL`

要使用标准的格式器，可以直接调用其 `format` 方法

- 预定义的格式器

- `Locale` 相关的格式器

- 带有定制模式的格式器

  ​                   *预定义的格式器*

  ![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/%E9%A2%84%E5%AE%9A%E4%B9%89%E7%9A%84%E6%A0%BC%E5%BC%8F%E5%99%A8.png?lastModify=1581229804)

`DateTimeFormatter` 类提供了三种用于打印日期、时间值的格式器

##### 格式化和解析

还有一个 `OffsetDateTime` 类，表示与 `UTC` 具有偏移量的时间，但是没有时区规则的束缚。这个类被设计用于专用于专用应用，这些应用特别需要剔除这些规则的约束，例如某些网络协议。对于人类时间，还是应该使用 `ZonedDateTime` 类

当夏令时开始时，时钟要向前一个小时。当构建的时间对象正好落入了跳过去的一个小时内。

​		![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/ZonedDateTime%E7%9A%84%E6%96%B9%E6%B3%95.png?lastModify=1581229804)

​		*ZonedDateTime的方法*

`ZonedDateTime` 的许多方法都与  `LocalDateTime` 的方法相同，它们大多数都很直接，但在夏令时带来了一些复杂性

这是一个具体的时刻，调用 `apollolllaunch.toInstant` 可以获得对应的 `Instant` 对象。反过来，如果有一个时刻对象，调用 `instant.atZone(ZoneId.of("UTC"))` 可以获得格林威治皇家天文台的 `ZonedDateTime` 对象，或者使用其他的 `ZoneId` 获得地球上其他地方的 `ZoneId`

给定一个时区 ID，静态方法 `ZoneId.of(id)` 可以产生一个 `ZoneId` 对象。可以通过调用 `local.atZone(zoneId)` 用这个对象将 `LocalDateTime` 对象转换为 `ZoneDateTime` 对象，或者可以通过调用静态方法 `ZonedDateTime.of(year, month, day, hour, minute, second, nano, zoneId)` 来构造一个 `ZonedDateTime` 对象。

每个时区都有一个 ID，例如 `America/New_York` 和 `Europe/Berlin`。要找出所有可用的时区，可以调用 `ZoneId.getAvailableZoneIds`。

##### 时区时间

还有一个表示日期和时间的 `LocalDateTime` 类。这个类适合存储固定时区的时间点。

​		![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/LocalTime%E7%9A%84%E6%96%B9%E6%B3%95.png?lastModify=1581229804)

​		*LocalTime的方法*

```
LocalTime bedtime = LocalTime.of(22, 30);
LocalTime rightNow = LocalTIme.now()
```

`LocalTime` 表示当日时刻，如：15：30：30。可以用  `now` 或 `of` 方法创建其实例

##### 本地时间

`lambda` 表达式的参数类型为 `Temporal` ，它必须被强制转型为 `LocalDate`。可以用 `ofDateAdjuster` 方法来避免这种强制转型，该方法期望得到的参数是类型为 `UnaryOperator` 的 `lambda` 表达式

还可以通过实现 `TemporalAdjuster` 接口来创建自己的调整器。

​	![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/TemporalAdjusters%E7%B1%BB%E4%B8%AD%E7%9A%84%E6%97%A5%E6%9C%9F%E8%B0%83%E6%95%B4%E5%99%A8.png?lastModify=1581229804)

​	*TemporalAdjusters类中的日期调整器*

`with` 方法返回一个新的 `LocalDate` 对象，而不会修改原来的对象

对于日程安排应用来说，经常需要计算诸如"每个月的第一个星期二"这样的日期。`TemporalAdjusters` 类提供了大量用于常见调整的静态方法。可以将调整方法的结果传给 `with` 方法

##### 日期调整器

​		![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/LocalDate%E7%9A%84%E6%96%B9%E6%B3%95.png?lastModify=1581229804)

​		*LocalDate的方法*

`LocalDate` 是带有年、月、日的日期。为了构建 `LocalDate` 对象，可以使用 `now` 或 `of` 静态方法：

在 Java API 中有两种人类时间，本地日期/时间和时区时间。本地日期/时间包含日期和当天的时间，但是与时区信息没有任何关联。1903 年 6 月 14 日就是一个本地日期的实例。因为这个日期既没有当天的时间，也没有时区信息，因此它并不对应精确的时刻。1969 年 7 月 16 日 09：32：00 EDT 是一个时区日期/时间，表示的是时间线上的一个精确的时刻

##### 本地时间

`Instant` 和 `Duration` 类都是不可修改的类，所以 `multipliedBy` 和 `minus` 这样的方法都会返回一个新的实例

![img](file:///Users/zhangyaowen/notes/Languages/Java/Language/Images/%E7%94%A8%E4%BA%8E%E6%97%B6%E9%97%B4%E7%9A%84Instant%E5%92%8CDuration%E8%BF%90%E7%AE%97.png?lastModify=1581229804)

​			*用于时间的Instant和Duration的算术运算*

```
Duration` 对象的内部存储所需的空间超过了一个 `long` 的值，因此秒数存储在一个 `long` 中，而纳秒数存储在一个额外的 `int` 中。如果想要让计算精确到纳秒级，那么实际上需要整个 `Duration` 的存储内容。如果不需要这么高的精度，可以用 long 的值来执行计算，然后调用 `toNanos
```

`Duration` 是两个时刻之间的时间量。可以通过调用 `toNanos` 、`toMillis`、`getSeconds`、`toMinutes`、`toHours` 和 `toDays` 来获得 `Duration` 按照传统单位度量的时间长度

#### 国际化

##### Locale 对象

##### 数字格式

数字和货币的格式高度依赖于 `locale` 。Java 类库提供了一个格式器对象的集合，可以对 `java.text` 包中的数字值进行格式化和解析。可以通过下面的步骤对特定 `Locale` 的数字进行格式化：使用一个工厂方法得到一个格式器对象，使用这个格式器对象来完成格式化和解析工作

工厂方法是 `NumberFormat` 类的静态方法，它们接受一个 `Locale` 类型的参数。总共有 3 个工厂方法：`getNumberInstance` 、`getCurrencyInstance` 、`getPercentInstance`，这些方法返回的对象可以分别对数字、货币量和百分比进行格式化和解析。

```java
// 对德语中的货币进行格式化
Locale loc = Locale.GERMAN;
NumberFormat currFmt = NumberFormat.getCurrencyInstance(loc);
double amt = 123456.78;
String result = currFmt.format(amt);
```

如果要读取一个按照某个 `Locale` 的惯用法而输入或存储的数字，那么就需要使用 `parse` 方法。`parse` 方法能处理小数点和分隔符以及其他语言中的数字

```java
TextField inputField;
NumberFormat fmt = NumberFormat.getNumberInstance();
Number input = fmt.parse(inputField.getText().trim());
double x = input.doubleValue();
```

`parse` 的返回类型是抽象类型的 `Number`。返回的对象是一个 `Double` 或 `Long` 的包装器类对象，这取决于被解析的数字是否是浮点数。如果不关心两者的差异。可以直接使用 `Number` 类中的 `doubleValue` 方法来读取被包装的数字

`Number` 类型的对象并不能自动转换成相关的基本类型，因此，不能直接将一个 `Number` 对象赋给一个基本类型，而应该使用 `doubleValue` 或 `intValue` 方法

如果数字文本的格式不正确，该方法会抛出一个 `ParseException` 异常。（字符串以空白字符开头是不允许的，但是任何跟在数字之后的字符都将被忽略，所以这些跟在后面的字符是不会引起异常的）

由  `getXxxInstance` 工厂方法返回的类并非是 `NumberFormat` 类型的。`NumberFormat` 类型是一个抽象类，而我们实际上得到的格式器是它的一个子类。工厂方法只知道如何定位属于特定 `locale` 的对象

可以用静态的 `getAvailableLocales` 方法得到一个当前所支持的 `Locale` 对象列表。这个方法返回一个 `Locale` 对象数组，从中可以获得针对它们的数字格式器对象

##### 货币

为了格式化货币值，可以使用 `NumberFormat.getCurrencyInstance` 方法。但是这个方法灵活性不好，它返回的是一个只针对一种货币的格式器。处理这样的情况，应该使用 `Currency` 类来控制被格式器所处理的货币。可以通过将一个货币标识符传给静态的 `Currency.getInstance` 方法来得到一个 `Currency` 对象，然后对每一个格式器都调用 `setCurrency` 方法

##### 日期和时间

当格式化日期和时间时，需要考虑 4 个与 `Locale` 相关的问题

* 月份和星期应该用本地语言来表示
* 年月日的顺序要符号本地习惯
* 公历可能不是本地首选的日期表示方法
* 必须要考虑本地的时区

`java.time` 包中的 `DateTimeFormatter` 类可以处理这些问题。可以使用 `LocalDate`、`LocalDateTime`、`LocalTime` 和 `ZonedDateTme` 的静态的 `parse` 方法之一来解析字符串中的日期和时间

```java
LocalTime time = LocalTime.parse("9:32 AM", formatter);
```

##### 排序和范化

`compareTo` 方法使用的是字符串的 `UTF-16` 编码值，这会导致很荒唐的结果，即使在英文比较中也是如此。为了获得 `Locale` 敏感的比较符，可以调用静态的 `Collator.getInstance` 方法：

```java
Collator coll = Collator.getInstance(locale);
words.sort(coll);
```

因为 `Collator` 类实现了 `Comparator` 接口，因此，可以传递一个 `Collator` 对象给 `list.sort(Comparator)` 方法来对一组字符串进行排序

排序器有几个高级设置项。可以设置排序器的强度以此来选择不同的排序行为。字符间的差别可以被分为首要的、其次的和再次的。如果将排序器的强度设置成 `Collator.PRIMARY`，那么排序器将只关注 `primary` 级的差别。如果设置成 `Collator.SECONDARY`，排序器将把 `secondary` 级的差别也考虑进去。即，两个字符串在 `secondary` 或 `tertiary` 强度下更容易被区分开来。如果强度被设置为 `Collator.IDENTICAL` 则不允许有任何差异。这种设置与排序器的第二种具有相当技术性的设置，即分解模式，联合使用时，就会显得非常有用

让排序器去多次分解一个字符串是很浪费的。如果一个字符串要和其他字符串进行多次比较，可以将分解的结果保存在一个排序键对象中。`getCollationKey` 方法返回一个 `CollationKey` 对象，可以用它来进行更进一步的、更快速的比较操作。

```java
String a = ...;
CollationKey akey = coll.getCollationKey(a);
if (akey.compareTo(coll.getCollationKey(b)) == 0) {
    
}
```

在将字符串存储到数据库中，或与其他程序进行通信时。`java.text.Normalizer` 类实现了对范化的处理

```java
String normalized = Normalizer.normalize(name, Normalizer.Form.NFD);
```

#### 消息格式化

Java 类库中有一个 `MessageFormat` 类，它与用 `printf` 方法进行格式化很类似，但是它支持 `Locale` ，并且会对数字和日期进行格式化。

##### 格式化数字和日期

典型的消息格式化字符串

```java
"On {2}, a {0} destroyed {1} houses and caused {3} of damage."
```

括号中的数字是占位符，可以用实际的名字和值来替换它们。使用静态方法 `MessageFormat.format` 可以用实际的值来替换这些占位符。它是一个 "varargs" 方法，可以通过下面的方法提供参数

```java
String msg = MessageFormat.format("On {2}, a {0} destroyed {1} houses and caused {3} of damage.", "hurricane", 99, new GregorianCalendar(1999, 0, 1).getTime(), 10.0E8);
```

上面例子中，占位符 `{0}` 被 "hurricane" 替换，`{1}` 被 99 替换。

占位符索引后面可以跟一个类型（type）和一个风格（style）, 它们之间用逗号隔开。类型是：`number` , `time`、`date` 、`choice` 如果类型是 `number` 那么风格可以是 `integer` 、`currency`、`percent`，如果类型是 `time` 或 `date` ，风格可以是 `short` ，`medium`、`long`、`full` 或者是一个日期格式模式，就像 `yyyy-MM-dd` 

静态的 `MessageFormat.format` 方法使用当前的 `locale` 对值进行格式化。要用任意的 `locale` 进行格式化，还有一些工作要做，因为这个类还没有提供任何可以使用的 "varargs" 方法。需要把将要格式化的值置于 `Object[]` 数组中。

```java
MessageFormat mf = new MessageFormat(pattern, loc);
String msg = mf.format(new Object[] {});
```

##### 文本文件和字符集

##### 源文件的字符编码

作为程序员，要与 Java 编译器交互，这种交互需要通过本地系统的工具来完成。例如，可以使用中文版的记事本来写 Java 源代码文件，但这样写出来的源码不是随处可用的，因为它们使用的是本地的字符编码，只有编译后的 `class` 文件才能随处使用，它们会自动地使用 `modified UTF-8` 编码来处理标识符和字符串。即在程序编译和运行时，依然有3种字符编码参与其中：

* 源文件：本地编码
* 类文件：`modified UTF-8`
* 虚拟机：`UTF-16`

使用 `-encoding` 标记来设定源文件的字符编码

```shell
javac -encoding UTF-8 Myfile.java
```

为了使源文件能够到处使用，必须使用普通的 `ASCII` 编码。即，需要将所有非 `ASCII` 字符转换成等价的 `Unicode` 编码。JDK 包含一个工具---native2ascii, 可以用它来将本地字符编码转换成普通的 ASCII。这个工具直接将输入中的每一个非 ASCII 字符替换为一个  \u 加 4 位十六进制数字的 Unicode 值。使用 `native2ascii` 时，需要提供输入和输出文件的名字

```shell
native2ascii Myfile.java Myfile.temp
```

用 `-reverse` 选项来进行逆向转换

```
native2ascii -reverse Myfile.temp Myfile.java
```

用 `-encoding` 选项指定另一种编码

```java
native2ascii -encoding UTF-8 Myfile.java Myfile.temp
```

#### 正则表达式

##### 正则表达式的匹配

1.通过调用静态方法 `Pattern.compile()` 来创建一个模式

2.为每个String（或其他字符序列）调用 `pattern.matcher(CharSequence)`，以从模式中请求一个 `Matcher`

3.在结果 `Matcher` 中调用（一次或多次）方法

正则表达式用于指定字符串的模式，可以在任何需要定位匹配某种特定模式的字符串的情况下使用正则表达式。

正则表达式的常用就是测试某个特定的字符串是否与它匹配。在 Java 中，首先用表示正则表达式的字符串构建一个 `Pattern` 对象。然后从这个模式获得一个 `Matcher`，并调用它的 `matches` 方法

```java
Pattern pattern = Pattern.compile(patternSing);
Matcher matcher = pattern.matcher(input);
if (matcher.matches()) {
    m.group();
}
```

这个匹配器的输入可以实任何实现了 `CharSequence` 接口的类的对象

在编译这个模式时，可以设置一个或多个标志

```java
Pattern pattern = Pattern.compile(expression, Pattern.CASE_INSENSIVE + Pattern.UNICODE_CASE);
```

标志：

* `Pattern.CASE_INSENSITIVE` 或 `r`：匹配字符时忽略字母的大小写，默认情况下，这个标志只考虑 `US ASCII` 字符
* `Pattern.UNICODE_CASE` 或 `u`：当与 `CASE_INSENSITIVE` 组合使用时，用 `Unicode` 字母的大小写来匹配
* `Pattern.UNICODE_CHARACTER_CLASS` 或 `U`：选择 `Unicode` 字符流代替 `POSIX` ，其中蕴含了 `UNICODE_CASE`
* `Patern.MULITLINE` 或 `m`：`^` 和 `$` 匹配行的开头和结尾，而不是整个输入的开头和结尾
* `Pattern.UNIX_LINES` 或 `d`：在多行模式中匹配 `^` 和 `$` 时，只有 `\n` 被识别成行终止符
* `Pattern.DOTALL` 或 `s`：当使用这个标志时，`.` 符号匹配所有字符，包括行终止符
* `Pattern.COMMENTS` 或 `x`：空白字符和注释（从 # 到行末尾）将被忽略
* `Pattern.LITERAL`：该模式将被逐字地采纳，必须精确匹配，因字母大小写而造成的差异除外
* `Pattern.CANON_EQ`：考虑 `Unicode` 字符规范的等价

最后两个标志不能在正则表达式内部指定

如果想要在集合或流中匹配元素，可以将模式转换为谓词

```java
Stream<String> strings = ...;
Stream<String> result = strings.filter(pattern.asPredicate());
```

其结果中包含了正则表达式的所有字符串

如果正则表达式包含群组，那么 `Matcher` 对象可以揭示群组的边界。

`Matcher` 有多个 `finder` 方法，能比 `String` 的 `match` 操作提供更大的灵活性，这些方法返回布尔值，返回 `true` 意味着匹配成功，`false` 意味着匹配不成功。

* `matches()` 

  将整个字符串和模式比较，这和 `java.lang.String` 中一样。因为它匹配整个字符串

* `lookingAt()`

  只在字符串的开始匹配

* `find()`

  在字符串中匹配模式（不一定非从字符串的第一个字符开始），从字符串的首字符开始，或者如果之前已成功调用该方法，则从未能符合前面匹配的第一个字符开始匹配

匹配成功后，可以使用以下方法获取匹配信息：

* `start()，end()`

  分别返回匹配结果在字符串中的开始或结束位置

* `groupCount()`

  返回用括号括起来的捕捉组的数量，如果没有分组则返回 0

* `group(int i)`

  如果 `0 <= i <= groupCount()`，则返回当前匹配中与分组 i 匹配的字符。如果组号是 0，则表示完全匹配。`group(0)` 或 `group()` 将返回匹配的整个字符串

##### 替换

Java 正则表达式提供了一些相应的替换方法，对于各种形式的替换方法而言，都需要传递替换文本或替换“右手边”这样的参数（在命令行文本编辑器的替换命令中，左手边是模式，右手边是替换文本）

* `replaceAll(newString)`

  用 `newString` 替换所有匹配的地方

* `appendReplacement(StringBuffer, newString)`

  将匹配结果之前的字符串填加到 `StringBuffer`，再将匹配结果替换为 `newString`，并追加到 `StringBuffer`。即获取替换后的 `StringBuffer`

* `appendTail(StringBuffer)`

  将上次替换过的内容连接后面未替换过的内容，并放入 `StringBuffer`（通常在调用 `appendReplacement()` 之后调用 `appendTail()`，即获取原始`StringBuffer`

  

  



