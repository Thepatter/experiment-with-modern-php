### tools

#### 基础工具

|     工具     |                            用途                             |
| :----------: | :---------------------------------------------------------: |
| appletviewer | 在不使用 Web 浏览器的情况下运行和调试 Applet，JDK 11 中移除 |
|   extcheck   |              检查 JAR 冲突的工具，JDK 9 中移除              |
|     jar      |                     创建和管理 JAR 文件                     |
|     java     |          Java 运行工具，用于运行 Jar 和 Class 文件          |
|    javac     |                 用于 Java 编程语言的编译器                  |
|   javadoc    |                   Java 的 API 文档生成器                    |
|    javah     |      C 语言头文件和 Stub 函数生成器，用于编写 JNI 方法      |
|    javap     |                     Java 字节码分析工具                     |
|    jlink     |        将 module 和它的依赖打包成一个运行时镜像文件         |
|     jdb      |  用于 JPDA 协议的调试器，类似于 GDB 方式进行调试 Java 代码  |
|    jdeps     |                     Java 类依赖性分析器                     |
|  jdeprscan   |  用于搜索 JAR 包中使用 deprecated 的类，从 JDK 9 开始提供   |

##### javac

Java 虚拟机启动时，可以指定不同的参数对运行模式进行选择。 比如，指定“-Xint”，就是告诉 JVM 只进行解释执行，不对代码进行编译，这种模式抛弃了 JIT 可能带来的性能优势。毕竟解释器（interpreter）是逐条读入，逐条解释运行的。与其相对应的，还有一个“-Xcomp”参数，这是告诉 JVM 关闭解释器，不要进行解释执行，或者叫作最大优化级别。那你可能会问这种模式是不是最高效啊？简单说，还真未必。“-Xcomp”会导致 JVM 启动变慢非常多，同时有些 JIT 编译器优化方式，比如分支预测，如果不进行 profiling，往往并不能进行有效优化

* 获取编译警告，设置编译选项，-Xline:unchecked

-Xlint 选项告诉编译器对一些普遍容易出现的代码问题进行检查

* -Xlint -Xlint:all，执行所有的检查
* -Xlint:deprecation，与 -deprecation 一样，检查废弃的方法
* -Xlint:fallthrough，检查 switch 语句中是否缺少 break 语句
* -Xlint:none，不执行任何检查
* -Xlint:path，检查类路径和源代码路径上的所有目录是否存在
* -Xlint:serial，警告没有 serialVersionUTD 的串行化类
* -Xlint:unchecked，对通用类型与原始类型之间的危险转换给予警告

```
# 反编译类
javap -c ClassName
```

###### 源文件的字符编码

作为程序员，要与 java 编译器交互，这种交互需要通过本地系统的工具来完成。例如，可以使用中文版的记事本来写 java 源代码文件，但这样写出来的源码不是随处可用的，因为它们使用的是本地的字符编码，只有编译后的 class 文件才能随处使用，它们会自动地使用 modified UTF-8 编码来处理标识符和字符串。即在程序编译和运行时，依然有3种字符编码参与其中：

* 源文件：本地编码
* 类文件：modified UTF-8
* 虚拟机：UTF-16

使用 -encoding 标记来设定源文件的字符编码

```shell
javac -encoding UTF-8 Myfile.java
```

为了使源文件能够到处使用，必须使用普通的 ASCII 编码。即，需要将所有非 ASCII 字符转换成等价的 Unicode 编码。JDK 包含一个工具---native2ascii，可以用它来将本地字符编码转换成普通的 ASCII。这个工具直接将输入中的每一个非 ASCII 字符替换为一个  \u 加 4 位十六进制数字的 Unicode 值。使用 native2ascii 时，需要提供输入和输出文件的名字

```shell
native2ascii Myfile.java Myfile.temp
```

用 -reverse 选项来进行逆向转换

```
native2ascii -reverse Myfile.temp Myfile.java
```

用 -encoding 选项指定另一种编码

```java
native2ascii -encoding UTF-8 Myfile.java Myfile.temp
```

##### java

* 运行属性设置

    ```
    # 设置编码
    java -Dfile.encoding=UTF-8
    # 配置日志文件
    java -Djava.util.logging.config.file=configFile
    ```

* 设置路径：-classpath -cp

* 启用或禁用断言：-enableassertions -ea

    ```shell
    # 在某个类或整个包中使用断言
    java -ea:MyClass -ea:com.mycompany.mylib... MyApp
    // 用选项 -disableassertions 或 -da 禁用某个特定类和包的断言
    ```

    有些类不是由类加载器加载，而是直接由虚拟机加载。可以使用这些开关有选择地启用或禁用那些类中的断言。启用和禁用所有断言的 -ea 和 -da 开关不能应用到那些没有类加载器的『系统类』 上。对于这些系统类，需要使用 -enablesystemassertions/-esa 开关启用断言。

* -Xprof 分析 10 版本之后移除

##### jar

###### 归档

```shell
# jar [OPTION...] [ [--release VERSION] [-C dir] files] ...
# 创建包含两个类文件的名为 class.jar 的档案
jar --create --file classes.jar Foo.class Bar.class
# 使用现有的清单创建档案，其中包含 foo/ 中的所有文件
jar --create --file classes.jar --manifest mymanifest -C foo/ .
# 创建模块化 jar 档案，其中模块描述符位于 classes/module-info.class
jar --create --file foo.jar --main-class com.foo.Main --module-version 1.0 -C foo/ module-info.class
# 将现有的非模块化 jar 更新为模块化 jar
jar --update --file foo.jar --main-class com.foo.Main --module-version 1.0 -C foo/ module-info.class
# 创建包含多个发行版的 jar, 并将一些文件放在 META-INF/versions/9 目录中：
jar --create --file mr.jar -C foo classes --release 9 -C foo9 classes
# 可以在单独的文本文件中指定参数并使用 @ 符号作为前缀将此文件传递给 jar 命令
jar --create --file my.jar @classes.list
```

jar 创建类和资源的档案，并且可以处理档案中的单个类或资源或者从档案中还原单个类或资源。

*主操作模式*

|            选项             |               含义               |
| :-------------------------: | :------------------------------: |
|        `-c,--create`        |             创建档案             |
| `-i, --generate-index=FILE` |  为指定的 jar 档案生成索引信息   |
|         `-t,--list`         |          列出档案的目录          |
|        `-u,--update`        |        更新现有 jar 档案         |
|       `-x, --extract`       | 从档案中提取指定的（或全部）文件 |
|   `-d, --describe-module`   |   输出模块描述符或自动模块名称   |

*在任意模式下有效的操作修饰符*

|        选项         |                             含义                             |
| :-----------------: | :----------------------------------------------------------: |
|      `-C DIR`       |                    更改为指定的目录并包含                    |
|  `-f, --file=FILE`  |       档案文件名，省略时，基于操作使用 stdin 或 stdout       |
| `--release VERSION` | 将下面的所有文件都放在 jar 的版本化目录中（即 META-INF/versions/VERSION/） |
|   `-v, --verbose`   |                   在标准输出中生成详细输出                   |

*在创建和更新模式下有效的操作修饰符*

|             选项             |                             含义                             |
| :--------------------------: | :----------------------------------------------------------: |
| `-e, --main-class=CLASSNAME` | 操作到模块化或可执行 jar 档案的独立应用程序的应用程序入口点  |
|    `-m, --manifest=FILE`     |                 包含指定清单文件中的清单信息                 |
|     `-M, --no-manifest`      |                     不为条目创建清单文件                     |
|  `--module-version=VERSION`  |        创建模块化 jar 或更新非模块化 jar 时的模块版本        |
|   `--hash-modules=PATTERN`   | 计算和记录模块的散列，这些模块按指定模式匹配并直接或间接依赖于所创建的模块化 jar 或所更新的非模块化 jar |
|     `-p, --module-path`      |              模块被依赖对象的位置，用于生成散列              |

###### 清单文件

除了类文件，图像和其他资源外，每个 jar 文件还包含一个用于描述归档特征的清单文件。清单文件被命名为 MANIFEST.MF，它位于 jar 文件的一个特殊 META-INF 子目录中。最小的符合标准的清单文件是很简单的：

```manifest
Manifest-Version: 1.0
```

复杂的清单文件可能包含更多条目。这些清单条目被分成多个节。第一节被称为主节。它作用于整个 jar 文件。随后的条目用来指定已命名条目的属性，这些已命名的条目可以是某个文件、包或者 URL。它们都必须起始于名为 Name 的条目。节与节之间用空行分开。

```java
Manifest-Version: 1.0 	// 描述这个归档文件的行
Name: Woozle.class		// 描述这个文件的行
Name: com/company/mypkg/     // 描述这个包的行
```

创建一个包含清单的 JAR 文件

```java
// 将希望添加到清单文件中的行放到文本文件中，然后运行
jar cfm MyArchive.jar manifest.mf com/mycompany/mypkg/*.class
```

更新一个已有的 jar 文件的清单，需要将增加的部分放置到一个文本文件中，然后执行

```jar
jar ufm MyArchive.jar manifest-additions.mf
```

###### 可执行 jar 文件

可以使用 jar 命令中的 e 选项指定程序的入口点（通常需要在调用 java 程序加载器时指定的类）：

```jar
jar cvfe MyProgram.jar com.mycompany.mypkg.MainAppClass files to add
```

或者在清单中指定应用程序的主类，包括以下形式的语句：

```
// 不要将扩展名.class 添加到主类名中
Main-Class: com.mycompany.mypkg.MainAppClass   	
```

清单文件的最后一行必须以换行符结束。否则，清单文件将无法被正确地读取。

启动应用程序：

```
java -jar MyProgram.jar
```

* 在 Windows 平台中，java 运行时安装器将建立一个扩展名为 .jar 的文件与 javaw -jar 命令相关联来启动文件（与 java 命令不同，javaw 命令不打开shell窗口）
* 在  Solaris 平台中，操作系统能够识别 jar 文件的魔法数格式，并用 java -har 命令启动它
* 在 Mac OS X 平台中，操作系统能够识别 .jar 扩展名文件。当双击 jar 文件时就会执行 java 程序可以运行

###### 密封

可以将 java 包密封以保证不会有其他的类加入到其中。如果在代码中使用了包可见的类、方法和域，就可能希望密封包。如果不密封，其他类就有可能放在这个包中，进而访问包可见的特性

想要密封一个包，需要将包中的所有类放到一个 jar 文件中。在默认情况下，jar 文件中的包是没有密封的。可以在清单文件的主节中加入一行来改变全局的默认设定。

```
// 全局设定
Sealed: true 				
```

对于每个单独的包，可以通过在 JAR 文件的清单中增加一节，来指定是否想要密封这个包

```java
Name: com/mycompany/util/
Sealed: true					// 单个包设定

Name: com/mycompany/misc/
Sealed: false	 			// 单个包设定
```

#### 虚拟机性能监控、故障处理工具

##### jps

```shell
jps [options] [hostid]
```

虚拟机进程状况工具

*options*

| 选项 |                           作用                           |
| :--: | :------------------------------------------------------: |
| `-q` |               只输出 LVMID，省略主类的名称               |
| `-m` |     输出虚拟机进程启动时传递给主类 main() 函数的参数     |
| `-l` | 输出主类的全名，如果进程执行的是 JAR 包，则输入 JAR 路径 |
| `-v` |             输出虚拟机进程启动时的 JVM 参数              |

JVM Process Status Tool 列出正在运行的虚拟机进程，并显示虚拟机执行主类（Main Class，main() 函数所在的类）名称以及这些进程的本地虚拟机唯一 ID（LVMID，Local Virtual Machine Identifier）

其他的 JDK 工具大多需要输入它查询到的 LVMID  来确定要监控的是哪一个虚拟机进程。对于本地虚拟机进程，LVMID 与操作系统的进程 ID 是一致的

jps 还可以根据 RMI 协议查询开启了 RMI 服务的远程虚拟机进程状态，参数 hostid 为 RMI 注册表中注册的主机名。

##### jstat

```shell
# 如果是本地虚拟机进程，VMID 与 LVMID 是一致的；如果是远程虚拟机进程，VMID 格式：[protocol:][//][lvmid][@hostname][:port]/servername]
# interval 代表查询间隔
# count 代表查询次数
jstat [option vmid [interval[sms] [count]]]
# 每 250 毫秒查询一次进程 2764 垃圾收集状况，一共查询 20 次
jstat -gc 2764 250 20
```

虚拟机统计信息监视工具

*options*

|        选项         |                             含义                             |
| :-----------------: | :----------------------------------------------------------: |
|      `-class`       |      监视类加载、卸载数量、总空间以及类装载所耗费的时间      |
|        `-gc`        | 监视 Java 堆状况，包括 Eden 区、2 个 Survivor 区、老年代、永久代等的容量，已用空间，垃圾收集时间合计等信息 |
|    `-gccapacity`    | 监视内容与 `-gc` 基本相同，但输出主要关注 Java 堆各个区域使用到的最大、最小空间 |
|      `-gcutil`      | 监视内容与 `-gc` 基本相同，但输出主要关注已使用空间占总空间的百分比 |
|     `-gccause`      | 与  `-gcutil` 功能一样，但是会额外输出导致上一次垃圾收集产生的原因 |
|      `-gcnew`       |                    监视新生代垃圾收集状况                    |
|  `-gcnewcapacity`   | 监视内容与 `-gcnew`基本相同，输出主要关注使用到的最大、最小空间 |
|      `-gcold`       |                    监视老年代垃圾收集状况                    |
|  `-gcoldcapacity`   | 监视内容与 `-gcold` 基本相同，输出主要关注使用到的最大、最小空间 |
|  `-gcpcrmcapacity`  |               输出永久代使用到的最大、最小空间               |
|     `-compiler`     |            输出即时编译器编译过的方法、耗时等信息            |
| `-printcompilation` |                   输出已经被即时编译的方法                   |

##### jinfo

Configuration Info for Java 实时查看和调整虚拟机各项参数

```shell
jinfo [option] pid
jinfo -flag CMSInitiatingOccupancyFraction 1444
```

*options*

|            选项            |                  含义                   |                     备注                     |
| :------------------------: | :-------------------------------------: | :------------------------------------------: |
|       `-flag <name>`       | 打印 VM 未被显式指定的参数的系统默认值  | jdk 6 以上，可用 `java -XX:+PrintFlagsFinal` |
|    `-flag [+|-]<name>`     |             修改虚拟机 flag             |                                              |
|   `-flag <name>=<value>`   |      将命名的 VM 标志设置为给定值       |                                              |
|          `-flags`          |              打印 VM flag               |                                              |
|        `-sysprops`         | 打印虚拟机进程 `System.getProperties()` |                                              |
|       `<no option>`        |    打印 VM flag 和 System.properties    |                                              |
| `-? | -h | --help | -help` |              打印帮助信息               |                                              |

##### jmap

Memory Map for Java 命令用于生成堆转储快照（heapdump 或 dump 文件）。还可以查询 finalize 执行队列、Java 堆和方法区的相信信息

```
jmap [option] vmid
jmap -dump:format=b,file=eclipse.bin 3500
```

*options*

|         选项          |                             含义                             |
| :-------------------: | :----------------------------------------------------------: |
| `-dump[:dump-option]` | 生成 Java 堆转储快照，`-dump:[live,]format=b,file=<filename>`，其中 live 子参数说明是否只 dump 出存活对象 |
|   `-finalizerinfo`    | 显示在 F-Queue 中等待 Finalizer 现成执行 finalize 方法的对象，只在 Linux 平台有效 |
|        `-heap`        | 显示 Java 堆详细信息，如使用哪种回收器、参数配置、分代状况，仅 Linux |
|    `-histo[:live]`    |       显示堆中对象统计信息，包括类、实例数量、合计容量       |
|      `-permstat`      |    以  ClassLoader 为统计口径显示永久代内存状态，仅 Linux    |
|         `-F`          | 当虚拟机进程堆 `-dump` 选项没有响应时，可使用这个选项强制生成 dump 快照，只在 Linux |

##### jhat

JVM Heap Analysis Tool 命令与 jmap 搭配使用，来分析 jmap 生成的堆转储快照。jhat 内置了一个微型的 HTTP/Web 服务器，生成堆转储快照的分析结果后，可以在浏览器查看。

```
jhat eclipse.bin
```

##### jstack

stack trace for java 命令用于生成虚拟机当前时刻的线程快照（一般为 threaddump 或 javacore 文件）。线程快照即当前虚拟机内每一天线程正在执行的方法堆栈的集合，生成线程快照的目的通常是定位线程出现长时间停顿的原因

```
jstack [option] vmid
```

*jstack*

| 选项 |                     含义                     |
| :--: | :------------------------------------------: |
| `-F` | 当正常输出的请求不被响应时，强制输出线程堆栈 |
| `-l` |        除堆栈外，显示关于锁的附加信息        |
| `-m` |   如果调用到本地方法，可以显示 C/C++ 堆栈    |

##### jconsole

jdk 附带的 jconsole 是一个图像分析界面

可以使用 jmap 实用工具获得一个堆的转储，其中显示了堆中的每个对象

```shell
jmap -dump:format=b,file=dumpfileName processID
jhat dumpFileName
```

#### 安全工具

##### keytool

##### jarsigner

##### policytool

#### 国际化工具

##### native2ascii

#### 远程方法调用工具

##### rmic

##### rmiregistry

##### rmid

##### serialver

##### tnameserv

##### idlj

##### orbd

##### servertool

#### 部署工具

##### javapackager

##### pack200

##### unpack200

##### javaws