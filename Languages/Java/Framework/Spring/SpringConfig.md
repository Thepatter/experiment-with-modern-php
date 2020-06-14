### Spring Config

#### 容器

容器是 Spring 的核心，应用 Bean 存在于 Spring 容器中，容器负责创建、装配、管理 Bean 的生命周期

*   Bean 工厂

    由 *org.springframework.beans.factory.BeanFactory* 接口定义是最简单的容器，提供基本的 DI 支持

*   应用上下文

    由 *org.springframework.context.ApplicationContext* 接口定义，基于 *BeanFactory* 构建，提供应用框架级别的服务

##### Bean 管理

###### 内置上下文容器

Spring 自带了多种类型的应用上下文，可以显式使用上下文容器，并在上下文容器上创建 Bean

*   AnnotationConfigApplicationContext

    从一个或多个基于 java 的配置类中加载 Spring 应用上下文

    ```java
    ApplicationContext context = new AnnotationConfigApplicationContext(com.springinaction.BeansConfig.class)
    ```

*   AnnotationConfigWebApplicationContext

    从一个或多个基于 java 配置类中加载 Spring Web 应用上下文

*   ClassPathXmlApplicationContext

    从类路径下的一个或多个 XML 配置文件中加载上下文定义，把应用上下文的定义文件作为类资源

    ```java
    ApplicationContext context = new ClassPathXmlApplicationContext("knight.xml");
    ```

*   FileSystemXmlApplicationContext

    从指定文件系统下的一个或多个 XML 配置文件中加载上下文定义

    ```java
    // 装载应用上下文
    ApplicationContext context = new FileSystemXmlApplicationContext("c:/bean.xml")
    ```

*   XmlWebApplicationContext

    从 Web 应用下的一个或多个 XML 配置文件中加载上下文定义

###### Bean 生命周期

Bean 在 Spring 容器中从创建到销毁会经历多个阶段，每个阶段都可以针对 Spring 如何管理 Bean 进行定制

1.  Spring 对 Bean 进行实例化
2.  Spring 将值和 Bean 的引用注入到 Bean 对应的属性中
3.  如果 Bean 实现了 *BeanNameAware* 接口，Spring 将 Bean 的 ID 传递给 setBeanName() 方法
4.  如果 Bean 实现了 *BeanFactoryAware* 接口，Spring 将调用 setBeanFactory() 方法，将 BeanFactory 容器实例传入
5.  如果 Bean 实现了 *ApplicationContextAware* 接口，Spring 将调用 setApplicationContext()，将 Bean 所在的应用上下文的引用传入进来
6.  如果 Bean 实现了 *BeanPostProcessor* 接口，Spring 将调用它们的 postProcessBeforeInitialization() 方法
7.  如果 Bean 实现了 *InitializingBean* 接口，Spring 将调用它们的 afterPropertiesSet() 方法，如果 Bean 使用 init-method 声明了初始化方法，该方法也会被调用
8.  如果 Bean 实现了 *BeanPostProcessor* 接口，Spring 将调用它们的 postProcessAfterInitialization() 方法
9.  此时 Bean 已经准备就绪，可以被应用程序使用，它们将一直驻留在应用上下文中，直到该应用上下文被销毁
10.  如果 Bean 实现了 *DisposableBean* 接口，Spring 将调用它的 destroy() 接口，同样，如果 Bean 使用 destroy-method 声明了销毁方法，该方法也会被调用

##### Bean 作用域

默认 Spring 应用上下文中所有 Bean 都是单例形式创建的，每次注入的都是同一个实例。Spring 定义了单例、原型（每次注入或通过 Spring 应用上下文获取时，都创建一个新的 Bean 实例）、会话（在 Web 应用中，为每个会话创建一个 Bean 实例）、请求（在 Web 应用中，为每个请求创建一个 Bean 实例）的 Bean 作用域

###### 会话与请求作用域

将会话或请求作用域的 Bean 注入到单例 Bean 时，需要配置代理，代理真正需要注入的 Bean，因为被注入 Bean  会在应用上下文加载时候创建，当它创建时，Spring 会试图将要注入的会话或请求 Bean 注入，但由于此时请求或会话还未创建，因此注入的 Bean 还不存在，Spring 此时会注入一个类或接口的代理，代理会暴露与注入 Bean 相同的方法，当需要调用注入 Bean 时，代理会对其进行懒解析并将调用委托给会话作用域内真正的 Bean。使用 @Scope 的 proxyMode 属性声明代理模式是接口还是类

##### 装配 Bean

Spring 支持 XML、自动装配、POJO 类注解装配混合装配

###### 自动装配

在 Spring 技术中，自动配置起源于自动装配和组件扫描

* 组件扫描

  Spring 能自动发现应用类路径下的组件，并将它们创建成 Spring 应用上下文中的 bean。

  在 Spring 中使用 @ComponentScan 注解启用主键扫描

  *使用 XML 启动*

  ```xml
  <context:component-scan base-package="soundsystem"/>
  ```

* 自动装配

  Spring 能够自动为组件注入它们所依赖的其他 bean，Spring 使用 @Autowrid 注解实现自动装配

###### spring boot 自动配置

Spring Boot 能够基于类路径中的条目、环境变量和其他因素合理猜测需要配置的组件并将它们装配在一起（没有代码就是自动装配的本质）

###### POJO 代码装配

在没有源码或需要将第三方库中的组件进行装配时，使用 @Configuration 和 @Bean 注解来显式生成 Bean

##### XML 配置

###### servlet 容器部署描述符

*web.xml*

```xml
<?xml version="1.0" encoding="UTF-8"?>
<web-app version="3.0" xmlns="http://java.sun.com/xml/ns/javaee"
        			   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                       xsi:schemaLocation="http://java.sun.com/xml/ns/javaee
                                           http://java.sun.com/xml/ns/javaee/web-app_3_0.xsd">
    <servlet>
    	<servlet-name>springmvc</servlet-name>
        <servlet-class>
            org.springframework.web.servlet.DispatcherServlet
        </servlet-class>
        <init-param>
            <param-name>contextConfigLocation</param-name>
            <param-value>
                /WEB-INF/config/springmvc-config.xml
            </param-value>
        </init-param>
        <load-on-startup>1</load-on-startup>
    </servlet>
    
    <servlet-mapping>
    	<servlet-name>springmvc</servlet-name>
        // 当匹配 / 时，所有请求都映射 dispatcherServlet，需要在 SpringMvc 配置文件添加 resources 元素以处理静态资源
        <url-pattern>/</url-pattern>
    </servlet-mapping>
</web-app>
```

###### spring 配置文件

配置 Bean

*springmvc-config.xml*

```xml
<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="http://www.springframework.org/schema/beans"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xmlns:p="http://www.springframework.org/schema/p"
       xmlns:mvc="http://www.springframework.org/schema/mvc"
       xmlns:context="http://www.springframework.org/schema/context"
       xsi:schemaLocation="http://www.springframework.org/schema/beans
                           http://www.springframework.org/schema/beans/spring-beans.xsd
                           http://www.springframework.org/schema/mvc
                           http://www.springframework.org/schema/mvc/spring-mvc.xsd
                           http://www.springframework.org/schema/context
                           http://www.springframework.org/schema/context/springcontext.xsd">
    
    <context:component-scan base-package="com.app"/>
    // 如没 annotation-driver，resources 会阻止任意控制器被调用，若不使用 resources，则不要 annotation-driver 元素
    <mvc:annotation-driven/>
    <mvc:resources mapping="/css/ **" location="/css/"/>
    <mvc:resources mapping="/ *.html" location="/"/>
    <bean id="viewResolver" class="org.springframework.web.servlet.view.InternalResourceViewResolver">
        <property name="prefix" value="/WEB-INF/jsp/"/>
        <property name="suffix" value=".jsp"/>
    </bean>
</beans>
```

*Beans 子元素*

|         子元素         |           含义            |
| :--------------------: | :-----------------------: |
|          bean          |         配置 Bean         |
|    import resource     | 导入配置 Bean 的 xml 文件 |
| context:component-scan |    启用上下文组件扫描     |
| mvc:annotation-driven  |   配置 MVC 静态元素访问   |
| mvc:resources mapping  |   指定 MVC 资源匹配目录   |
|         beans          |      支持嵌套子元素       |

###### XML 装配

使用 xml 元素声明 Bean，并填充 Bean 属性及管理 Bean 依赖

*bean 元素属性*

|      属性      |                             含义                             |
| :------------: | :----------------------------------------------------------: |
|       id       |                         指定 Bean ID                         |
|     class      |                       指定 Bean class                        |
|    c:cd-ref    | 等于 constructor-arg ref 子元素，需要在 XML 中引入 spring c 命名空间 |
| c:_*paramName* |                装配字面量，paramName 为参数名                |
|    profile     |                         指定 profile                         |

*bean 子元素*

|        子元素         |                             含义                             |
| :-------------------: | :----------------------------------------------------------: |
|  constructor-arg ref  |               指定要传入构造器参数 Bean 的 Id                |
| constructor-arg value |                          装配字面量                          |
|       set/list        |         constructor-arg 子元素，用于装配集合类型参数         |
|         value         |           set/list 子元素，声明 set/list 子元素值            |
|       ref bean        |        set/list 子元素，声明 set/list 引用 Bean 的 ID        |
|       property        | 元素配置 Bean 属性，支持 name、ref 属性及 constructor 子元素（除集合外） |
|        profile        |                         指定 profile                         |
|         scope         |                       声明 Bean 作用域                       |
|   aop:scoped-proxy    | 为 Bean 创建作用域代理，默认使用 CGLib 创建目标类代理，proxy-target-class=false 创建基于接口代理，需要声明 aop 命名空间 `xmlns:aop="http://www.springframework.org/schema/aop"` |

##### Bean 属性值注入

自动注入会将 Bean  的引用进行注入。需要将一个固定的值注入到 Bean 的属性时，Spring 提供了属性占位符和 Spring 表达式语言的方式

###### 外部值注入

声明属性源并通过 Spring 的 Environment 来检索属性

###### 属性占位符

支持将属性定义到外部的属性文件中，并使用占位符将其值插入到 Spring Bean 中。占位符使用 `${}` 包装属性名。在使用 XML 配置 Bean 属性时，使用占位符必须使用 ` <context:property-placeholder />` 声明配置 Bean。或使用 @PropertySource 声明属性源文件

#### 应用配置

Spring 的环境抽象是各种配置属性的一站式服务，它抽取了原始的属性，需要这些属性的 bean 可以从 Spring 本身中获取。Spring 环境会拉取多个属性源：jvm 系统属性，操作系统环境变量，命令行参数，application.yml，将这些属性聚合在一个源中。通过这个源可以注入到 Spring 的 bean 中。

Spring Boot 自动配置的 bean 都可以通过 Spring 环境提取的属性进行配置。

##### application.yml

*   在配置文件中配置中文值，读取时可能会出现乱码，建议使用 Unicode 字符
*   使用 `${}` 访问定义属性，或随机属性

###### 日志配置

*   默认使用 Logback 日志组件，将日志输出到控制台

*   支持设置包级日志记录级别：TRACE，DEBUG，INFO，ERROR，FATAL，OFF

    ```properties
    # root 日志以 WARN 级别输出
    logging.level.root = WARN
    # com.wap.api 包下的类以 DEBUG 级别输出，支持用 ，分割来设置一组日志级别
    logging.level.com.wap.api = DEBUG
    ```

*   可以通过在 *src/mian/resources* 文件夹下定义 logback.xml 或 logback-spring.xml（在日志输出的时候引入一些 Spring Boot 特有的配置项） 作为日志配置

*日志配置项属性*

|               配置                |                             含义                             |
| :-------------------------------: | :----------------------------------------------------------: |
| logging.exception-conversion-word |                    记录异常时使用的转换字                    |
|           logging.file            | 设置日志文件（绝对或相对路径，同事设置目录或文件时，文件选项优先） |
|       logging.file.max-size       |          最大日志文件大小（默认 10MB 分割日志文件）          |
|          logging.config           |                           日志配置                           |
|     logging.file.max-history      |                       最大归档文件数量                       |
|           logging.path            |  日志文件目录（设置目录后，会在目录下创建一个 spring.log）   |
|      logging.pattern.console      |                    在控制台输出的日志模式                    |
|    logging.pattern.dateformat     |                     日志格式内的日期格式                     |
|       logging.pattern.file        |                       默认使用日志模式                       |
|       logging.pattern.level       |           日志级别（默认记录：ERROR、WARN、INFO）            |
|                PID                |                          当前进程ID                          |

##### 多环境配置

当应用部署到不同的运行环境时，有些配置细节通常会有些差别。可以使用环境变量，通过这种方式来指定配置属性，而不是在 application.properties 中进行定义。但不好管理

Spring profile 提供了一种条件化的配置，在运行时，根据哪些 profile 处于激活状态，可以使用或忽略不同的 bean，配置类和配置属性

###### profile 属性

* 多个文件

  定义特定 profile 相关的属性的一种方式就是创建另外一个配置文件，其中只包含用于该 profile 对应的属性。文件名遵循：application-{profile-name}.yml 或 application-{profile-name}.properties。在该 profile 中声明配置属性。可以指定多个 profile  文件。

* 单 yml

  该方式仅适用于 YAML 配置，将特定 profile 的属性和非 profile 的属性都放到 application.yml 中，它们之间使用 --- 分割，并且使用 spring.profiles 属性来命名 profile

  ```yaml
  logging:
  	level:
  		root: WARN
  ---
  spring:
  	profiles: prod
  logging:
  	level:
  		web: DEBUG
  ```

###### 激活 profile

Spring 在确定那个 profile 处于激活状态时，需要依赖两个独立的属性：`spring.profiles.active`（优先） 和 `spring.profiles.default` ，没有激活 profile 时，只创建没有定义 profile 的 Bean，可以在 DispatcherServlet 的初始化参数、Web 应用的上下文参数、JNDI 条目、环境变量 JVM 的系统属性来指定这两个属性，支持同时激活多个 profile

* *web.xml 中指定*

  ```xml
  <?xml version="1.0" encoding="UTF-8"?>
  <web-app version="2.5">
  	<context-param>  // 设置上下文默认 profile
  		<param-name>spring.profile.default></param-name>
          <param-value>dev</param-value>
      </context-param>
      <servlet>
      	<init-param>   // 为 servlet 设置默认 profile
              <param-name>spring.profile.default></param-name>
          	<param-value>dev</param-value>
          </init-param>
      </servlet>
  ```

* 将 profile 名称的列表赋值给 spring.profiles.active 属性

  ```yaml
  spring:
  	profiles:
  		active:
  			- prod
  			- audit
  			- ha
  ```

  如果在 application.yml 中设置处于激活状态的 profile，那么这个 profile 就会变成默认的 profile

* 使用环境变量激活 profile

  ```shell
  export SPRING_PROFILES_ACTIVE=prod,audit,ha
  ```

* 以 jar 文件形式运行应用，以命令行参数形式激活 profile

  ```shell
  java -jar web.jar --spring.profiles.active=prod
  ```

###### 使用 profile 条件化地创建 bean

有时候，为不同的 profile 创建一组独特的 bean 是非常有用的。正常情况下，不管那个 profile 处于激活状态，Java 配置类中声明的所有 bean 都会被创建。假设希望某些 bean 仅在特定 profile 激活的情况下才需要创建。

在这种情况下，@profile 注解可以将某些 bean 设置为仅适用于给定的 profile，支持在带由 @Configuration 注解的类上使用 @Profile

```java
@Bean
@Profile({"dev","qa"}) // 在 dev 或 qa profile 激活的时候都需要创建 CommandLineRunner bean
public CommandLineRunner dataLoader(IngredientRepository repo {}
@Bean
@Profile("!prod") // 只要 prof profile 不激活就要创建 CommandLineRunner bean
public COmmandLineRunner dataLoade(IngredientRepository repo) {}
```

###### 创建自定义的限定符注解

使用自定义的限定符注解来表达 Bean 所希望限定的特性，需要创建一个注解，它本省使用 @Qualifier 注解来标注

```java
@Target(
    {ElementType.CONSTRUCTOR, ElementType.FIELD,ElementType.METHOD, ElementType.TYPE})
@Retention(RetentionPolicy.RUNTIME)
@Qualifier
public @interface Cold { }
```

使用自定义限定符注解，可以同时使用多个限定符，不会有 Java 编译器的限制（不允许使用多个同类注解）

 



