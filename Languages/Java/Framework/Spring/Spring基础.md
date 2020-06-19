### Spring 基础

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

 #### AOP

切面提供了取代继承和委托的另一种可选方案，在使用面向切面编程时，在一处定义通用功能，但可以通过声明的方式定义这个功能要以何种方式在何处作用，而无需修改受影响的类。

横切关注点可以被模块化为特殊的类，这些类被称为切面。

##### 切面定义

###### 通知

Advice，切面的工作被称为通知，通知定义了切面是什么以及何时使用，包含：前置通知（在目标方法被调用之前调用通知功能）、后置通知（在目标方法完成之后调用通知，此时不会关心方法的输出是什么）、返回通知（在目标方法成功执行之后调用通知）、异常通知（在目标方法抛出异常后调用通知）、环绕通知（在被通知的方法调用之前和调用之后执行自定义的行为）

声明环绕通知方法时，必须指定调用 ProcessdingJoinPoint.proceed() 方法，将控制权交给被通知的方法，否则会阻塞对被通知方法的调用

切点定义中的参数与切点方法中参数名称一样时，即可完成从命名切点到通知方法的参数转移（获取连接点的参数）

```java
@Aspect
public class TrackCounter {

    @Pointcut("execution(* package.class.method(int)) && args(trackNumber)")
    public void trackPlayed(int trackNumber) {}
    
    @Before("trackPlayed(trackNumber)")
    public void countTrack(int trackNumber) {
    	System.out.print(trackNumber);
    }
}
```

###### 连接点

join point 连接点是应用执行过程中能够插入切面的一点，这个点可以是调用方法时，抛出异常时，修改字段时，切面代码可以利用这些点插入到应用的正常流程之中，并添加新的行为

###### 切点

Pointcut 

切点定义了何处，切点的定义『匹配通知所要织入的一个或多个连接点』，即将横切关注点应用的具体的方法上。通常使用明确的类和方法名称，或者利用正则表达式定义所匹配的类和方法名称来指定这些切点。有些 AOP 框架允许动态创建切点

在 Spring 中，使用 AspectJ 的切点表达式语言来定义切点，仅支持 AspectJ 切点指示器的一个子集，使用列表之外的指示器会抛出 *IllegalArgumentException*

| AspectJ 指示器 |                             描述                             |
| :------------: | :----------------------------------------------------------: |
|     arg()      |            限制连接点匹配参数为指定类型的执行方法            |
|    @args()     |          限制连接点匹配参数由指定注解标注的执行方法          |
|  execution()   |                  用于匹配是连接点的执行方法                  |
|     this()     |      限制连接点匹配 AOP 代理的 bean 引用为指定类型的类       |
|     target     |             限制连接点匹配目标对象为指定类型的类             |
|   @target()    | 限制连接点匹配特定的执行对象，这些对象对应的类要具有指定类型的注解 |
|    within()    |                   限制连接点匹配指定的类型                   |
|    @within     | 限制连接点匹配指定注解所标注的类型（当使用 Spring AOP时，方法定义在由指定的注解所标注的类里） |
|  @annotation   |                 限定匹配带有指定注解的连接点                 |

```java
// 定义切点接口
public interface Performance { void perfore(); }
// 切点表达式 * 不关心返回值，指定全限定的类名和方法名，方法 .. 即不关心入参（支持指定参数类型），支持使用 and or ! 等语义限制，bean 关键字指定 Bean ID
execution(* package.class.method(..) and bean("beanId"))
```

###### 切面

Aspect 切面是通知和切点的结合，通知和切点共同定义了切面的全部内容

切面是一个 POJO，使用 @AspectJ 注解标注其为一个切面，并在其方法上使用 @Before 等注解（在注解中定义切点表达式）标注切面执行时机。

使用时需要将切面定义的 POJO 装配为 Bean，并启用代理功能：

*   使用 @EnableAspectJAutoProxy

*   xml

    ```xml
    <aop:aspectj-autoproxy/>
    ```

###### 引入

Intruduction 引入允许向现有的类i添加新的方法或属性

###### 织入

weaving 把切面应用到目标对象并创建新的代理对象的过程。切面在指定的连接点被织入到目标对象中。在目标对象的生命周期里有多个点可以进行织入

* 编译期

  切面在目标类编译时被织入，这种方式需要特殊的编译器，AspectJ 的织入编译器就是以这种方式织入切面的

* 类加载期

  切面在目标类加载到 jvm 时被织入，这种方式需要特殊的类加载器，它可以在目标类被引入应用之前增强该目标类的字节码。AspectJ 5 的加载时织入支持以该方式织入切面

* 运行期

  切面在应用运行的某个时刻被织入，一般情况，在织入切面时，AOP 容器会为目标对象动态创建一个代理对象，Spring 以该方式织入切面

##### Spring AOP

Spring AOP 建立在动态代理基础之上，局限于方法拦截，提供了 4 种类型的 AOP 支持：

* 基于代理的经典 Spring AOP
* 纯 POJO 切面
* @AspectJ 注解驱动的切面
* 注入式 AspectJ 切面

Spring 所创建的通知都是标准的 java 类，在代理类中包裹切面，Spring 在运行期把切面织入到 Spring 管理的 Bean 中，代理类封装了目标类，并拦截被通知方法的调用，再把调用转发给真正的目标 Bean，当代理拦截到方法调用时，在调用目标 Bean 方法之前，会执行切面逻辑

直到应用需要被代理的 Bean 时，Spring 才创建代理对象。如果使用的 ApplicationContext，在 ApplicationContext 从 BeanFactory 中加载所有 Bean 的时候，Spring 才会创建被代理的对象。Spring 基于动态代理，只支持方法连接点

###### 使用 AOP

1.  定义切面的 POJO（并在其方法上定义切点）
2.  将切面的 POJO 装配为 Spring Bean
3.  配置自动代理注册（AspectJ 自动代理都会为使用 @Aspect 注解的 Bean 创建一个代理）

###### XML 配置切面

对于没有源码无法使用注解时，可以使用 XML 进行切面的配置

|       AOP 配置元素        |                             用途                             |
| :-----------------------: | :----------------------------------------------------------: |
|      `<aop:advisor>`      |                       定义 AOP 通知器                        |
|       `<aop:after>`       |      定义 AOP 后置通知（不管被通知的方法是否执行成功）       |
|  `<aop:after-returning>`  |                      定义 AOP 返回通知                       |
|  `<aop:after-throwing>`   |                      定义 AOP 异常通知                       |
|      `<aop:around>`       |                      定义 AOP 环绕通知                       |
|      `<aop:aspect>`       |                        定义其一个切面                        |
| `<aop:aspectj-autoproxy>` |                 启用 @AspectJ 注解驱动的切面                 |
|      `<aop:before>`       |                    定义一个 AOP 前置通知                     |
|      `<aop:config>`       | 顶层的 AOP 配置元素，大多数的 `<aop:*>` 元素必须包含在 `<aop:config>` 元素内 |
|  `<aop:declare-parents>`  |           以透明的方式为被通知的对象引入额外的接口           |
|     `<aop:pointcut>`      |                         定义一个切点                         |

```xml
<aop:config>
    <!-- 声明切面，引用 audience Bean，ref 元素引用的 bean 提供了切面中通知所调用的方法 -->
    <aop:aspect ref="audience">
        <!-- 定义 id 为 performance 的切点，并在通知中引用该切点  -->
        <aop:pointcut id="performance" expression="execution(** concert.Performance.perform(..))"/>
        <!-- 定义了匹配切点的方法执行之前调用前置通知方法 taksSeats 和 solenceCellPhones  -->
        <aop:before pointcut="performance" method="solenceCellPhones"/>
        <aop:before pointcut="performance" method="takeSeats"/>
        <!-- 定义了一个返回通知，在切点所匹配的方法调用之后再调用 applause 方法 -->
        <aop:after-returning pointcut="performance" method="applause"/>
        <!-- 定义了异常通知，如果所匹配的方法执行时抛出任何异常，都会调用 demandRefund() 方法 -->
        <aop:after-throwing pointcut="performance" method="demandRefund"/>
        <!-- 声明环绕通知 -->
        <aop:around pointcut-ref="performance" method="watchPerformance"/>
    </aop:aspect>
</aop:config>
```

###### Spring Boot 相关配置

*   star 依赖

    ```xml
    <dependency>
    	<groupId>org.springframework.boot</groupId>
        <artifactId>spring-boot-starter-aop</artifactId>
    </dependency>
    ```

*   配置

    ```properties
    # 默认开启自动代理，即默认添加了 @EnableAspectJAutoProxy
    spring.aop.auto=true
    # 使用 CGLIB 实现 AOP，false 则使用标注 Java 实现
    spring.aop.proxy-target-class=true
    ```

    

