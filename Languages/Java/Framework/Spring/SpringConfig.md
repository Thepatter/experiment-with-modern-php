### Spring Config

#### Spring 配置

##### 自动配置

在 Spring 技术中，自动配置起源于自动装配和组件扫描

*   组件扫描

    Spring 能自动发现应用类路径下的组件，并将它们创建成 Spring 应用上下文中的 bean

*   自动装配

    Spring 能够自动为组件注入它们所依赖的其他 bean

###### spring boot 自动配置

Spring Boot 能够基于类路径中的条目、环境变量和其他因素合理猜测需要配置的组件并将它们装配在一起（没有代码就是自动装配的本质）

##### 注解配置

基于 POJO 的注解配置

##### XML 配置

###### 容器部署描述符

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
    // 如没有 annotation-driver，resources 元素会阻止任意控制器被调用，若不需要使用 resources，则不需要 annotation-driver 元素
    <mvc:annotation-driven/>
    <mvc:resources mapping="/css/ **" location="/css/"/>
    <mvc:resources mapping="/ *.html" location="/"/>
    <bean id="viewResolver" class="org.springframework.web.servlet.view.InternalResourceViewResolver">
        <property name="prefix" value="/WEB-INF/jsp/"/>
        <property name="suffix" value=".jsp"/>
    </bean>
</beans>
```

#### Spring 容器

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

    ```
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



 



