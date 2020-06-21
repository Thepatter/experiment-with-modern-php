### 注解

#### Spring 内置注解

##### 通用注解

既可以用在类级别上，也可以用在方法级别，也可以用在类级别

###### @Primary

标识首选 bean，避免装配歧义，能够与 @Component 组合用在组件扫描的 bean 上，也可以与 @Bean 组合用在 Java 配置的 bean 声明中。

@Primary 无法将可选方案的范围限定到唯一一个无歧义的选择中，只能标示一个优先的可选方案。

###### @Qualifier

声明限定符

*   与 @Autowired 和 @Inject 协同使用，在注入的时候指定想要注入进去的是那个 Bean 的限定符（所引用的 Bean 要具有 String 类型的限定符，如果没有指定其他的限定符，所有的 Bean 都会给定一个 默认的限定符，这个限定符与 Bean 的 ID 相同）

*   与 @Component 和 @Bean 协同使用，声明 Bean 的限定符

###### @Scope

声明 @Bean 作用域，可以与 @Component 或 @Bean 使用，Spring 定义了多种作用域，可以基于这些作用域创建 bean。

1.  value 属性指定作用域

    *   Singleton

        单例，常量：**ConfigurableBeanFactory.SCOPE_SINGLETON** 或 Singleton

    *   Prototype

        每次注入或者通过 Spring 应用上下文获取的时候，都会创建一个新的 bean 实例 **ConfigurableBeanFactory.SCOPE_PROTOTYPE** 或 Prototype

    *   Session

        在 Web 应用中，为每个会话创建一个 bean 实例，**WebApplicationContext.SCOPE_SESSION** 或 session

    *   Request

        在 Web 应用中，为每个请求创建一个 bean 实例，**WebApplicationContext.SCOPE_REQUEST** 或 request

2.  proxyMode 属性声明代理

    *   **ScopedProxyMode.INTERFACES**

        表明这个代理要实现对应接口，并将调用委托给实现 Bean

    *   **ScopedProxyMode.TARGET_CLASS**

        表明代理要实现对应类（即 Bean 类型是具体类）

###### @Profile

3.1 版本引入，使用该注解指定某个 Bean 属于那个 profile（spring 会在对应 profile 激活时创建 Bean），3.2 支持方法级别

###### @Conditional

指定条件化的创建 Bean

###### @RequestMapping

指定请求 url，并定制相关属性，可以应用在类级别和方法级别，部分属于与方法级别 mapping 注解通用

* value

    指定 url，如果只有该属性，可以省略属性名称

* method

    指示该方法的 HTTP 方法

    ```java
    @RequestMapping(value="/order_process", method={RequestMethod.POST, RequestMethod.PUT)
    ```

    如果 @RequestMapping 注解类型用来注解一个控制器类，这种情况下，所有的方法都将映射为相对于类级别的请求

* produces

    指定输出，只会处理 Accept 头信息保护该值的请求，它不仅会限制 API 只会生成 JSON 结果，同时还允许其他的控制器处理具有相同路径的请求，只要这些请求不要求 JSON 格式的输出就可以

    ```java
    @RequestMapping(path="/design", produces={"application/json", "text/xml"})
    ```

* consumes

    指定请求输入

    ```java
    @PostMapping(consumes="application/json")
    ```

    该方法只会处理 Content-type 与 application/json 相匹配的请求

###### @Autowired

通过在字段或方法声明来实现 Bean 之间的依赖自动装配。**使用该注解的类必须声明为 Bean**，如果只有一个 Bean 匹配，则这个 Bean 会被装配起来，如果没有匹配的 Bean 或有多个匹配的 Bean，在应用上下文创建时会抛出异常（*NullSuchBeanDefinitionException*）

*attribute*

|   属性   |                             含义                             |
| :------: | :----------------------------------------------------------: |
| required | false 时，如果没有匹配的 Bean，Spring 会让这个 Bean 处于未装配状态 |
|          |                                                              |

使用 XML 配置时，还需要启动组件扫描配置

```xml
<context:component-scae base-package="dependencyPackage"/>
```

##### 类级别注解

###### @ActiveProfiles

指定激活的 profile

###### @SpringBootApplication

复合注解，SpringBoot 使用这个注解来告诉 Spring 容器，这个类是 Spring 中使用的 bean 定义的源。声明 Spring Boot 服务入口点。

该注解将入口类标记为配置类，然后开始自动扫描 Java 类路径上所有的类以形成其他 Spring Bean

###### @Controller

构造型注解，标识为 Spring MVC 控制器，并且将其作为组件扫描的候选者

###### @Repository

构造型注解，标识当前类定义为数据仓库由 Spring 容器管理，Spring 的组件扫描会自动发现它，并且会将其初始化为 Spring 应用上下文中的 bean

###### @Component

构造型注解，标识该类为 Spring 组件，把普通 POJO 实例化到 Spring 容器中

###### @Service

构造型注解，标识将该类定义为服务

###### @Repository

组件扫描会发现它，并且会将其初始化为 spring 上下文中的 bean，spring 应该将这个接口视为存储库并为它生成动态代理

###### @SessionAttributes

类级别的 @SessionAttributes 能够指定模型对象要保存在 session 中

###### @RestController

类似于 @Controller 和 @Service 的构造型注解，能够让类被组件扫描功能发现。且控制器中的所有处理方的返回值都要直接写入响应体中，而不是将值放到模型中并传递给一个试图以便于进行渲染

###### @Component

在类级别声明，使用类路径扫描自动检测和配置 bean，在已用的类和 Bean（即每个类一个 Bean）之间存在一个隐含的一对一映射。Bean 的 ID 为首字符小写的类名

###### @ComponentScan

启用组件扫描，默认会扫描与配置类相同的包及子包

*attribute*

|        属性        |                     含义                     |
| :----------------: | :------------------------------------------: |
|    basePackage     |  指定扫描基础扫描包，支持数组设置多个基础包  |
|       value        |                指定扫描包名称                |
| basePackageClasses | 指定类所在包为基础扫描包，支持数组设置多个类 |

###### @Configuration

表明该类是一个配置类，可在类中声明一个或多个 @Bean 方法

###### @Import

将配置类组合在一起，以便能使用 @Import 注解导入类的 Bean

###### @ImportResource

将 XML 配置导入配置类以组合使用

###### @EnableWebMvc

启用 Spring Mvc

###### @ConfigurationProperties

指定配置文件前缀，属性 prefix 指定前缀

###### @EnableConfigurationProperties

启用配置类

##### 方法级别注解

###### @PropertySource

声明属性源

###### @CrossOrigin

允许跨域

```java
@CrossOrigin(origins = "*")
```

###### @GetMapping

在 Spring 4.3 引入处理 HTTP GET 请求

###### @PostMapping

处理 POST 请求

###### @PutMapping

处理 PUT 请求

###### @DeleteMapping

处理 DELETE 请求

###### @PatchMapping

处理 PATCH 请求

###### @ModelAttribute

Spring MVC  在每次调用请求处理方法时，都会创建 Model 类型的一个实例。若打算使用该实例，则可以在方法中添加一个 Model 类型的参数。还可以在方法中添加 `ModelAttribute` 注解类型来访问 Model 实例。可以用 `@ModelAttribute` 来注解方法参数或方法。带 `@ModelAttribute` 注解的方法会将其输入的或创建的参数对象添加到 Model 对象中（若方法中没有显式添加）。

```java
@RequestMapping(method = RequestMethod.POST)
public String submitOrder(@ModelAttribute("newOrder") Order order, Model model) {}
```

输入或创建的 Order 实例将用 newOrder 键值添加到 Model 对象中，如果未定义键值名，则将使用该对象类型的名称。

`@ModelAttribute` 的第二个用途是标注一个非请求的处理方法。被 `@ModelAttribute` 注解的方法会在每次调用该控制器类的请求处理方法时被调用。Spring MVC 会在调用请求处理方法之前调用带 `ModelAttribute` 注解的方法。带 `@ModelAttribute` 注解的方法可以返回一个对象或一个 `void` 类型。如果返回一个对象，则返回对象会自动添加到 Model 中，若方法返回 `void`，则还必须添加一个 `Model` 类型的参数，并自行将实例添加到 Model 中

```java
@ModelAttribute
public void populateModel(@RequestParam String id, Model mode.addAttribute(new Account(id))) {}
```

###### @Valid

验证对象属性

|     注解     |                             描述                             |
| :----------: | :----------------------------------------------------------: |
| @AssertFalse |           所注解的元素必须时 Boolean，且值为 false           |
| @AssertTrue  |           所注解的元素必须是 Boolean，且值为 true            |
| @DecimalMax  | 所注解的元素必须是数字，且值小于或等于给定的 BigDecimalString 值 |
| @DecimalMin  | 所注解的元素必须是数字，且值大于或等于给定的 BigDecimalString 值 |
|   @Digits    |      所注解的元素必须是数字，并且它的值必须有指定的位数      |
|   @Future    |             所注解的元素的值必须是一个将来的日期             |
|     @Max     |        所注解的元素必须是数字，且它的值小于等于给定值        |
|     @Min     |        所注解的元素必须是数字，且它的值大于等于给定值        |
|   @NotNull   |                所注解元素的值必须不能为 null                 |
|    @Past     |            所注解的元素的值必须是一个已过去的日期            |
|   @Pattern   |           所注解的元素的值必须匹配给定的正则表达式           |
|    @Size     | 所注解的元素的值必须是 String，集合或数组，并且它的长度要符合给定的范围 |
|    @Null     |                 所注解的元素的值必须为 null                  |

###### @Bean

显式声明单个 Bean，将 Bean 的声明与类的定义分离。并允许自定义方法创建和配置 Bean，表明这些方法（方法体中包含了最终产生 Bean 实例的逻辑）所返回的对象会以 Bean 的形式添加到 Spring 的应用上下文中（默认情况下，这些 Bean 所对应的 ID 与定义它们方法名称是相同的）一般用于我们没有源码情况

| 属性 |       含义       |
| :--: | :--------------: |
| name | 设置  Bean ID 名 |
|      |                  |



###### @ResponseStatus

指定响应状态码

###### @RequestBody

请求应该被转换为一个 Book 对象并绑定到该参数上

```
public Book store(@RequestBody Book book) {}
```

###### @PostConstruct

指定初始化 Bean 后要执行方法

##### 属性级别注解

###### @Value

在属性上声明，实例化 Bean 时读取应用配置属性并赋值，使用 `${author.name}` 语法

###### @PathVariable

方法参数上使用，指定 url 路径变量，路径变量的类型可以不是字符串，Spring MVC 将尽力转换为非字符串类型

```java
// 路径变量 http://domain/app/product/3
@RequestMapping(value = "/product/{id}")
public String viewProduct(@PathVariable Long id, Model model) {
	Product product = productService.get(id);
	model.addAttribute("product", product);
}
```

###### @RequestParam

注解方法参数

```java
// 请求参数 http://domain/app/product?productId=3 支持使用 List, Map, Optional 类型接收参数
public void sendProduct(@RequestParam int productId)
```

*attribute*

|     属性     |          含义           |
| :----------: | :---------------------: |
|     name     |    声明请求参数名称     |
|   required   | 默认 true，指定是否必须 |
| defaultValue |       指定默认值        |



##### SpringDataJPA

###### @Entity

类级别，声明 JPA 实体

###### @Id

属性上声明该属性为数据库表主键

###### @GeneratedValue

属性上声明，提供了主键的生成策略

* strategy

  1. *GenerationType*.TABLE

      使用一个特定的数据库表格来保存主键，持久化引擎通过关系数据库的一张特定的表格来生成主键，这种策略的好处就是不依赖于外部环境和数据库的具体实现，在不同数据库间可以很容易的进行移植。

     但由于其不能充分利用数据库的特性，所以不会优先使用。

     该策略一般与另外一个注解一起使用 @TableGenerator，@TableGenerator 注解指定了生成主键的表(可以在实体类上指定也可以在主键字段或属性上指定)，然后 JPA 将会根据注解内容自动生成一张表作为序列表(或使用现有的序列表)。如果不指定序列表，则会生成一张默认的序列表，表中的列名也是自动生成。数据库上会生成一张名为 sequence 的表（SEQ_NAME，SEQ_COUNT）。序列表一般只包含两个字段：第一个字段是该生成策略的名称，第二个字段是该关系表的最大序号，它会随着数据的插入逐渐累加

  2. *GenerationType*.SEQUENCE

     在某些数据库中，不支持主键自增长，比如 Oracle，其提供了”序列（sequence）“的机制生成主键。

     该策略的不足之处正好与 TABLE 相反，由于只有部分数据库 (Oracle，PostgreSQL，DB2) 支持序列对象，所以该策略一般不应用于其他数据库。

     类似的，该策略一般与另外一个注解一起使用 @SequenceGenerator，@SequenceGenerator 注解指定了生成主键的序列，然后 JPA 会根据注解内容创建一个序列（或使用一个现有的序列）。如果不指定序列，则会自动生成一个序列 SEQ_GEN_SEQUENCE。

     @SequenceGenerator 可以理解为将数据库中存在的序列进行了一个映射，在 @GeneratedValue 注解中的 generator 属性可以根据此标识来声明主键生成器。

  3. *GenerationType*.IDENTITY

     数据库主键自增

  4. *GenerationType*.AUTO

     默认，主键生成策略由 JPA 提供，持久化引擎会根据数据库在以上三种主键生成策略中选择其中一种

* generator

  属性值为一个字符串，默认为""，声明了主键生成器的名称（对应于同名的主键生成器 @Sequence 和 @TableGenerator）

###### @ManyToMany

指定多对多

```java
@ManyToMany(targetEntity=Ingredient.class)
```

###### @PrePersist

方法注解，声明在持久化之前调用

```java
@PrePersist
void createdAt() {
	this.createdAt = new Date();
}
```

###### @JoinTable

指定中间表的表名字以及关联两个表的字段

```
@JoinTable(name = "UserRole", joinColumns = {$JoinColumn(name = "userId")}, inverseJoinColumns = {$JoinColumn(name = "roleId")})
```

###### @Table

类级别注解，指定表名

###### @Query

Repository 接口方法注解，声明方法调用时要执行的查询，Spring Data JPA 写的 SQL 是 JPQL，需要使用 JAP 对象实体查询，不支持部分 SQL 功能，如 limit，可以使用参数 nativeQuery = true，来写原生 SQL

###### @DynamicInsert

插入数据时使用字段的默认值

###### @DynamicUpdate

更新数据时使用字段的默认值

##### AOP

###### @AspectJ

标识 POJO 为切面

###### @Pointcut

标识方法为切点，值为切点表达式

###### @AfterThrowing

通知方法会在目标方法返回后调用

###### @After

通知方法会在目标方法返回或抛出异常后调用

###### @AfterReturning

通知方法会在目标方法返回后调用

###### @Around

通知方法会将目标方法封装起来

###### @Before

通知方法会在目标方法调用之前执行

###### @DeclareParents

为切面 Bean 引入新的功能（指定接口的实现）

|    属性     |               含义               |
| :---------: | :------------------------------: |
|    value    | 指定那种类型的 Bean 要引入该接口 |
| defaultImpl |      为引入功能提供实现的类      |

#### 其他组件

##### Lombok

###### @Data

类级别的 @Data 会生成缺省方法：equals()、getter()、setter()、hashCode()、toString()，同时还会生成所有以 final 属性作为参数的构造器

###### @Slf4j

运行时，会在这个类中自动生成一个 SLF4J（Simple Logging Facade for java）Logger。等效于在类中

```java
private static final org.slf4j.Logger log = org.slf4j.LoggerFactory.getLogger(Current.class);
```

###### @NoArgsConstructor

类级别注解，实现一个无参构造器，会移除 @Data 添加的有参构造器

###### @RequiredArgsConstructor

除了 private 的无参构造器之外，还会有一个有参构造器

##### Hibernate Validator

###### @NotNull

###### @Size

###### @CreditCardNumber

###### @Digits

###### @NotBlank

##### RestData

###### @RepositoryRestController

所有映射将会具有和 spring.data.rest.base-path 属性值一样的前缀。但没有和 @RestControllerr 相同的语义。不能保证处理器方法返回的值会自动写入响应体。

#### SpringCloud 组件

##### Ribbon

###### @LoadBalanced

为带有 @Bean 注解的方法添加 @LoadBalanced 注解可以声明支持负载均衡的 RestTemplate bean

它会作为一个注入限定符，可以在注入的地方声明此处想要支持负载均衡的 RestTemplate

##### Eureka

###### @EnableEurekaServer

应用主引导类声明该项目为服务注册中心

##### Feign

###### @EnableFeignClients

在 @Configuration 配置类上声明或在主引导类上声明启用 Feign 客户端

###### @FeignClient

Feign 接口声明，属性为服务名

##### Hystrix

###### @EnableHystrix 

在主引导类上使用该注解声明启用

###### @HystrixCommand

将 java 类方法标记为由 Hystrix 断路器进行管理。Spring 对 @HystrixCommand 标记的方法将动态生成一个代理，该代理将包装该方法，并通过专门用于处理远程调用的线程池来管理对该方法的所有调用

|         属性         |                             含义                             |
| :------------------: | :----------------------------------------------------------: |
|    fallbackMethod    | 后备方法，要与原始方法具有相同的签名（除了方法名称），支持嵌套备用方法 |
|  commandProperties   | 一个或多个 @HystrixProperty 注解组成数组，指定了要设置的属性名和值，配置断路器属性 |
|    threadPoolKey     |                     定义线程池的唯一名称                     |
| threadPoolProperties |                 定义和定义 threadPool 的行为                 |

默认情况下，所有带有 @HystrixCommand 注解的方法都会在 1 秒后超时，并调用它们声明的后备方法。

commandProperties 支持属性名

|                       属性                       |                    含义                    |
| :----------------------------------------------: | :----------------------------------------: |
| execution.isolation.thread.timeoutInMilliseconds |           指定超时时间，单位毫秒           |
|            execution.timeout.enabled             |               false 取消超时               |
|     metrics.rollingState.timeInMilliseconds      |      设置断路器观察时间段时长单位毫秒      |
|      circuitBreaker.requestVolumeThreshold       |          断路器观察时间段调用次数          |
|      ciruitBreaker.sleepWindowInMillseconds      | 设置断路器打开时长单位毫秒，之后会进行半开 |
|     circuitBreaker.errorThresholdPercentage      |      设置断路器观察时间段内最低失败率      |

如果在 metrics.rollingState.timeInMilliseconds 设定的时间范围内超出了 ci rcuitBreaker.requestValuemThreshold 和 circuitBreaker.errorThresholdPercentage 设置的值，那么断路器将会进入打开状态。

在circuitBreaker.sleepWindowInMilliseconds 限定的时间范围内，它会一直处于打开状态，在此之后将进入半打开状态，进入半打开状态后，将会再次尝试失败的原始方法

###### @HystrixProperty

设置 @HystrixCommand 属性。属性为 name 和 value 的键值对

###### @DefaultProperties

类级别注解，指定对应级别的 Hystrix 属性

##### Config

###### @EnableConfigServer

使用该注解在主引导类声明该项目是注册中心服务

##### Zuul

###### @EnableZuulProxy

使用该注解在主引导类声明该项目为一个 Zuul 服务器

###### @EnableZuulServer

使用此注解创建一个 Zuul 服务器，它不会加载任何 Zuul 反向代理过滤器，也不会使用 Netflix Eureka 进行服务发现，如果想要构建自己的路由服务，而不使用任何 Zuul 预置的功能时会使用 @EnableZuulServer（如与 Consul 集成）

