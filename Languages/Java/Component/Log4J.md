### Log4J

#### 组成

Log4J 是 Apache 的一个开源项目，一个日志操作包。使用 Log4J 可以指定日志信息输出的目的地，日志输出格式等。Log4J 由三大组件构成：

* Logger

  负责生成日志，并能够对日志信息进行分类筛选

  *org.apache.logging.log4j.Logger*

  ```java
  // 打印各种级别的日志
  void trace(Object message);
  void debug(Object message);
  void info(Object message);
  void warn(Object message);
  void error(Object message);
  void fatal(Object message);
  // 打印日志
  void log(Level level, Object message);
  ```

  *org.apache.logging.log4j.LogManager*

  ```java
  // 获取Log4J配置文件中 name 对应 logger 实例
  public static Logger getLogger(String name);
  ```

  Log4J 提供了一个 root Looger，它是所有 Logger 组件的祖先。可以在配置文件中配置继承关系，使用 `.` 继承前面的 Logger 组件。继续关系：

  * 如果子类 Logger 组件没有配置日志级别，则将继承父类的日志级别
  * 如果子类 Logger 组件配置了日志级别，就不会继承父类的日志级别
  * 默认子类 Logger 组件会继承父类所有 Appender，把它们加入自己的 Appender 集中
  * 如果在配置文件中把子类 Logger 组件的 additivity 属性设为 false，那么它就不会继承父类的 Appender。additivity 标志的默认值为 true。

* Appender

  定义日志输出目的地，一个 Logger 可以由多个 Appender

* Layout

  指定日志输出格式，每个 Appender 都对应一种 Layout。有以下类型：

  * org.apache.log4j.PatternLayout

    依照 Coversion Pattern 去定义输出格式，类似 printf

    *PatternLayout格式*

    |  符号   |                    描述                    |
    | :-----: | :----------------------------------------: |
    |   %r    | 自程序开始运行到输出当前日志所消耗的毫秒数 |
    |   %t    |        表示输出当前日志的线程的名字        |
    | %level  |               表示日志的级别               |
    |   %d    |        表示输出当前日志的日期和时间        |
    | %logger |      表示输出当前日志的 Logger 的名字      |
    | %msg%n  |                表示日志内容                |

    为 file 的 Appeender 配置 PatternLayout 布局

    ```xml
    <File name="file" fileName="app.log">
    	<PatternLayout pattern = "%d{HH:mm:ss.SSS} [%t] %-5level %logger{36} - %msg%n"
    </File>
    ```

  * org.apache.log4j.HTMLLayout

  * org.apache.log4j.XMLLayout

  * org.apache.log4j.SerializedLayout

#### Log4J 配置文件

使用 Log4J 首先需要在使用 一个配置文件来配置 Log4J 的各个组件，支持 XML 格式的配置文件，默认名字为：log4j2.xml，默认存放路径是 classpath 的根路径

```xml
<?xml version="1.0" encoding="UTF-8"?>
<Configuration status="WARN">
  <Appenders>
    <Console name="console" target="SYSTEM_OUT">
      <PatternLayout pattern="%d{HH:mm:ss.SSS} [%t] %-5level %logger{36} - %msg%n" />
    </Console>
    <File name="file" fileName="app.log">
      <PatternLayout pattern="%d{HH:mm:ss.SSS} [%t] %-5level %logger{36} - %msg%n" />
    </File>
  </Appenders>
  <Loggers>
    <Root level="info">
      <AppenderRef ref="console" />
    </Root>
    <Logger name="helloappLogger" level="warn" additivity="false">
      <AppenderRef ref="file" />
      <AppenderRef ref="console" />
    </Logger>
    <Logger name="helloappLogger.childLogger" level="debug" additivity="false">
      <AppenderRef ref="console" />
    </Logger>
 </Loggers>
</Configuration>
```

#### 使用 Log4J

程序中使用 Log4J：

1. 获得日志记录器
2. 输出日志信息

