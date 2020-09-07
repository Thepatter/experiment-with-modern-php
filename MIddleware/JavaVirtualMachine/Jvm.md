### Jvm 相关

#### 指令

*操作码助记符*

|           指令名            |                        含义                         |
| :-------------------------: | :-------------------------------------------------: |
|         ILOAD/ALOAD         | 将基本/引用类型局部变量压入栈（首字母指定基础类型） |
|        ISTORE/ASTORE        |             从操作栈顶存储到局部变量表              |
|           ICONST            |             加载 -1 ~ 5 的数到操作栈顶              |
|           BIPUSH            |     Byte Immediate PUSH 加载 -128 ~ 127 到栈顶      |
|           SIPUSH            |   Short Immediate PUSH 加载 -32768 ~ 32767 到栈顶   |
|             LDC             |        Load Constant 加载 int 范围值或字符串        |
|          IADD/IMUL          |    对两个操作栈帧上的值进行运算，将结果写入栈顶     |
|           I2L/D2F           |             显式转换两种不同的数值类型              |
|        NEW/NEWARRAY         |                    创建对象指令                     |
| GETFIELD/PUTFIELD/GETSTATIC |                    访问属性指令                     |
|    INSTANCEOF/CHECKCAST     |                  检查实例类型指令                   |
|          POP/POP2           |                    出栈1/2个元素                    |
|             DUP             |                复制栈顶元素并压入栈                 |
|        INVOKEVIRTUAL        |                 调用对象的实例方法                  |
|        INVOKESPECIAL        |       调用实例初始化方法、私有方法、父类方法        |
|        INVOKESTATIC         |                    调用静态方法                     |
|           RETURN            |                   返回 VOID 类型                    |
|  MONITORENTER/MONITOREXIT   |               支持 synchronized 语义                |



#### 类加载

将 `.class` 字节码文件实例化成 Class 对象并进行相关初始化的过程，在这个过程中，JVM 会初始化继承树上还没有被初始化过的所有父类，并且会执行这个链路上所有未执行过的静态代码块、静态变量赋值语句等

##### 加载机制

###### 双亲委派

低层次的当前类加载器，不能覆盖更高层次类加载器已经加载的类。当需要加载一个未知类时，会逐步请求上级类加载器（直至 Bootstrap ClassLoader）进行加载，然后向下级逐级尝试是否能够加载此类，如果都加载不了，则通知发起加载请求的当前类加载器进行加载。

###### 自定义类加载器

双亲委派并非强制模型，用户可以自定义类加载器，以下情况需要自定义类加载器：

1.  隔离加载类

    在某些框架内进行中间件与应用的模块隔离，把类加载到不同的环境

2.  修改类加载方式

    类的加载模型并非强制、除 Bootstrap 外，其他的加载并非一定要引入

实现自定义类加载器步骤：1.继承 *ClassLoader* 2. 重写 `findClass()`3.调用 `defineClass()` 方法

##### 类加载器

类加载器是一个运行时核心基础设施模块，主要是在启动之初进行类的 Load、Link、Init

*   Load

    load 阶段读取类文件产生二进制流，并转化为特定的数据结构，初步校验 cafe babe 魔法数、常量池、文件长度、是否有父类等，然后创建对应类的 *java.lang.Class* 实例

*   Link

    包含验证（进一步校验：final 是否合规、静态变量是否合规、类型是否正确）、准备（为静态变量分配内存，并设定默认值，解析类和方法确保类与类之间的相互引用正确性，完成内存结构布局）、解析

*   Init

    执行类构造器 `<clinit>` 方法，如果赋值运算是通过其他类的静态方法来完成的，那么会马上解析另外一个类，在虚拟机栈中执行完毕后通过返回值进行赋值

###### 上下文类加载器

每个线程都有一个对类加载器的引用，称为上下文类加载器。主线程的上下文类加载器是系统类加载器。当新线程创建时，它的上下文类加载器会被设置为创建该线程的上下文类加载器。如果不做处理，所有线程都会将它们的上下文类加载器设置为系统类加载器

```java
// 设置线程上下文类加载器
Thread t = Thread.currentThread();
t.setContextClassLoader(loader);
// 获取上下文类加载器
ClassLoader loader = Thread.currentThread().getContextClassLoader();
```

在同一个虚拟机中，可以有两个类，它们的类名和报名都是相同的。类是由它的全名和类加载器来确定的

###### Bootstrap ClassLoader

在 JVM 启动时创建，通常由与操作系统相关的本地代码实现，是基础类加载器，负责加载最核心的 Java 类（Object、System、String），使用 C/C++ 实现，获取 *ClassLoader* 对象时返回 null

###### Platform ClassLoader

*   在JDK 9 版本中，称为 Platform ClassLoader 平台类加载器，加载一些扩展 `$JAVA_HOME/lib/ext/` 的系统类（XML、加密、压缩相关的功能类）。
*   JDK 9 之前是 Extension ClassLoader

###### Application ClassLoader

应用类加载器，主要加载用户定义的 CLASSPATH 路径下的类。