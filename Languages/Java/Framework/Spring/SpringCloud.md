### SpringCloud

#### 使用 SpringCloud 开发微服务

##### Spring 微服务组件

在 Spring 中，通过使用 Spring Boot 来拆分业务，一个 Spring Boot 即为一个微服务端点。SpringCloud 提供了一系列的组件来快速搭建微服务：

* Spring Boot

  以业务拆分的微服务端点

* 服务注册中心 Eureka

  提供所有服务注册，供其他服务发现与调用

* Ribbon 组件

  客户端负载均衡

* 配置中心 ConfigServer

  提供所有微服务配置获取及热更新（使用消息队列）

* Hystrix 组件

  提供了服务调用失败的保障及监控机制

##### 开发流程

1. 搭建 Spring Cloud Config Server，依据服务拆分，为每个微服务端点提供不同环境不同配置
2. 搭建 Spring Cloud Eureka 中心，作为 Spring Cloud Config Client 获取配置
3. 搭建微服务端点，作为 Spring Cloud Eureka Client 和 Spring Cloud Client 端点消费服务中心与服务配置
4. 搭建 Hystrix Dashboard 监控流，作为 Spring Cloud Config Server Client 和 Spring Cloud Eureka Client

#### Eureka

##### Server

在微服务应用中，Eureka 会担当所有服务的注册中心。Eureka 本身也可以视为一个微服务，在整体应用中它的目的是让其他的服务能够互相发现

当服务实例启动时，它会按照名称将自己注册到 Eureka 中。Eureka 希望服务实例能够注册上来，并且每隔 30 秒向它发送一次注册更新请求。通常，如果 Eureka 在 3 个更新周期内没有收到服务的更新请求，就会将该服务注销。

自我保护模式下，不会注销服务实例，在生产环境中，自我保护模式可以防止在网络出现故障时更新请求无法发送至 Eureka 所导致的活跃服务被注销。自我保护模式会将已停止服务的注册项保留下来。

使用 Eureka

1.配置

```xml
<dependency>
	<groupId>org.springframework.cloud</groupId>
  <artifactId>spring-cloud-starter-netflix-eureka-server</artifactId>
</dependency>
```

2.应用主引导类声明 @EnableEurekaServer该项目为服务注册中心，客户端默认监听 8761 端口

3.配置

```yml
eureka:
	instance:
		hostname: localhost
	client:
		fetch-registry: false # 生产环境为 true
		register-with-eureka: false  # 生产环境为 true 从其他 eureka 获取信息
		service-url:
			defaultZone: 
				http://${eureka.instance.hostname}:${server.port}/eureka
	server:
		enable-self-preservation: false  # 禁用自我保护模式
server:
	port: 8761 # Eureka 客户端默认监听端口
```

##### Client

为了让应用成为服务注册中心的客户端，需要将 Eureka 客户端添加到服务应用的构建文件中

```xml
<dependency>
  <groupId>org.springframework.cloud</groupId>
  <artifactId>spring-cloud-starter-netflix-eureka-client</artifactId>
</dependency>
```

```properties
server.port=0
# 服务名称
spring.application.name=order-service
eureka.client.service-url.defaultZone=http://eurekal.tacocloud.com:8761/eureka/,http://eurekal.taocloud.com:8761/eureka/
```

#### Ribbon

作为客户端的负载均衡器能够按照客户的的数据成比例伸缩，每个负载均衡器都可以配置成最适合对应客户端的负载算法，而不必对所有服务都使用相同的配置

#### 消费服务

支持负载均衡的 RestTemplate，Feign 生成的客户端接口来进行消费

##### RestTemplate

为带有 @Bean 注解的方法添加 @LoadBalanced 注解可以声明支持负载均衡的 RestTemplate bean

```java
@Bean
@LoadBalanced
public RestTemplate restTemplate() {
		return new RestTemplate();
}
```

@LoadBalanced 注解声明该 RestTemplate 能够使用 Ribbon 查找服务，其次，它会作为一个注入限定符，可以在注入的地方声明此处想要支持负载均衡的 RestTemplate

```java
@Component
public class IngredientServiceClient {
		private RestTemplate rest;
		public IngredientServiceClient(@LoadBalanced RestTemplate rest) {
				this.rest = rest;
		}
		public Ingredient getIngredientById(String ingredientId) {
				return rest.getForObject("http://ingredient-service/ingredients/{id}", Ingredient.class, ingredientId);
		}
}
```

使用服务名 ingredient-service，在内部，RestTemplate 会要求 Ribbon 根据名称查找服务并从中选择一个实例，Ribbon 会将 URL 重写为选定服务实例的主机和端口

##### Feign

Feign 是一个接口驱动的 REST 客户端，类似 repository。

```xml
<dependency>
	<groupId>org.springframework.cloud</groupId>
  <artifactId>spring-cloud-starter-openfeign</artifactId>
</dependency>
```

1. 配置

   ```java
   @Configuration
   @EnableFeignClients
   public RestClientConfiguration {
   
   }
   ```

2. 定义接口

   ```java
   @FeignClient("ingredient-service")
   public interface IngredientClient {
     @GetMapping("/ingredients/{id}")
     Ingredient getIngredient(@PathVariable("id") String id);
   }
   ```

3. 使用

   ```java
   @RestController
   public class IngredientController {
     	private IngredientClient client;
     	@Autowired
     	public IngredientController(IngredientClient client) {
         	this.client = client;
       }
     	@GetMapping("/{id}")
     	public String ingredientDetailPage(@PathVariable("id") String id) {
         	return client.getIngredient(id);
       }
   }
   ```

无需实现类，在运行期，当 Feign 发现它时，会自动创建一个实现类并将其暴露为 Spring 应用上下文中的 bean。

@FeignClient 注解会指定该接口上的所有方法都会对名为 ingredient-service 的服务发送请求，在内部，服务将会通过 Ribbon 进行查找，这与支持负载均衡的 RestTemplate 运行方式一致

#### Config

##### 单体配置

可以通过多种属性源设置属性来对 Spring 应用进行配置。如果某个配置属性可能会更改或者只针对运行时环境有效，那么 java 系统属性或操作系统环境变量是一个合适的可选方案。

对于不太可能发生变化或者应用特定的属性，将它们放到 application.yml 或 application.properties 中，随着打包的应用一起部署是一种很好的方案。

当在环境变量或 java 系统属性中设置配置属性时，修改这些属性需要应用重启，如果选择将属性到爆道要部署的 JAR 或 WAR 文件中，那么在属性变更时，必须要完全重新构建和重新部署应用。

##### 基于微服务架构配置

在基于微服务架构的应用中，属性管理会跨越多个代码库和部署实例，因此将相同变更应用到应用中的多个服务的每个实例是不现实的。Spring Cloud Config Server 提供了中心化的配置功能，应用中的所有微服务均可以依赖该服务器来获取配置。

Config Server 暴露了 REST API，客户端可以通过它来消费配置属性

##### 搭建配置中心

###### 快速开始

1. 依赖

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-config-server</artifactId>
   </dependency>
   ```

2. 使用 @EnableConfigServer 注解启用 ConfigServer 服务，客户端默认监听 8888 端口

3. 配置 git 配置仓库并上传 application.yml 配置文件到仓库根路径下

4. 使用 Rest 接口消费 /application/default/master 它会向该路径发送请求，路径组成：

   * application

     指发送请求的应用的名称

   * default

     处于激活状态的 Spring profile

   * master

     git 标签/分支（可选）默认 master

###### 配置

可以将配置放到相对于 git 仓库子目录下来组织配置，只需在 config-server 的 properties 下指定搜索路径

```yml
spring:
	cloud:
		config:
			server:
				git:
				 	# git 配置仓库 url，支持各种 git 协议
					uri: http://github.com/cloud/config
					# 搜索路径,搜索 config 目录和 more 开头的目录
					search-paths: config,more*
					# 指定默认 git 分支
					default-label: v1
					# auth
					username: admin
					password: secret
```

###### 提供特定应用的属性

有些属性可能是某个服务特有的，不应该与所有的服务共享，除了共享配置之外，Config Server 还能面向特定应用的配置属性。<u>要实现需要将配置文件的名称命名为该应用 spring.application.name 属性的值</u>，如：product-service.yml。

不管服务应用的名称是什么，所有的应用都会接收来自 application.yml 文件的配置。但是向 Config Server 发请求的时候，每个服务应用的 spring.application.name 的属性值会一同发送。如果存在匹配的属性文件，那么该文件中的属性将会一并返回。如果 application.yml 中通用的属性与特定应用配置文件中的属性出现重复，特定应用的属性优先。

###### 提供来自 profile 的属性

Spring Cloud Config Server 采用与单个 Spring Boot 应用完全相同的方式，提供了对特定 profile 属性的支持：

* 提供特定 profile 的 properties 或 yml 文件，如，application-production.yml 的配置文件
* 在一个 yaml 文件中提供多个 profile 配置组，之间以 --- 和 spring.profiles 分割

在应用从 Config Server 获取配置信息的时候，Config Server 会识别那个 profile 处于激活状态（位于请求路径第二部分）。如果活跃 profile 是 production，那么两个属性集（application.yml 和 application-production.yml）都将会返回，且 production 中的属性会优先

##### 客户端消费配置

Spring cloud config server 提供了一个客户端库，会包含在 spring boot 应用的构建文件中，允许应用成为 Config Server 的客户端

###### 快速开始

1. 依赖

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-starter-config</artifactId>
   </dependency>
   ```

2. 配置

   ```yml
   spring:
   	cloud:
   		config:
   			uri: http://localhost:8888
   	application:
   		name: product-service
   ```

当应用启动时，Config Server Client 会对 Config Server 发送请求，接收的属性将会放到应用环境中，并缓存起来，即便 Config Server 停机也依然可用

##### 注册中心配置中心

* 微服务中，通过 Config Server 了解 Eureka 服务注册中心。

* 还可将 Config Server 本身注册到 Eureka 中，让微服务发现去查找 Config Server。

  这种模式，需要将Config Server 变成服务发现的客户端，并将 spring.cloud.config.discovery.enabled 属性设置为 false。这样的话，Config Server 会将自身以 “configserver” 名称注册到 Eureka 中。

  这种方式下，每个服务在启动的时候都要调用两次外部的服务：第一次调用 Eureka 发现 Config Server 的位置，第二次调用 Config Server 获取配置数据

##### 存储加密属性

ConfigServer 可以借助存储在 Git 中的属性文件提供加密值

###### 使用对称密钥加密

使用一个加密密钥来配置 ConfigServer，在将属性提供给客户端应用之前，ConfigServer 要使用这个密钥对数值进行解密。在 ConfigServer 中将 encrypt.key 属性设置为加密和解密密钥的值，这个属性要设置到 bootstrap 配置中（bootstrap.properties 或 bootstrap.yml，在自动配置功能启用 Config Server 之前，才会加载和启用）

```yml
encrypt:
	key: secretmd5sum
```

###### 使用非对称秘钥

ConfigServer 支持非对称的 RSA 密钥对或引用一个 keystore。

```shell
# 创建密钥对和自签名证书 config.jks
keytool -genkeypair -alias configkey -keyalg RSA -dname "CN=web Server,OU=Unit,O=Organization,L=City,S=State,C=US" -keypass secret -keystore config.jks -storepass secret
```

将 config.jks 放到文件系统中或应用本身（为了在 Config Server 中使用加密功能，需要安装 Java Cryptogrophy Extensions Unlimited Strength 策略文件），并配置

```yml
encrypt:
	key-store:
		alias: configkey
		location: classpath:/config.jks
		password: secret
		secret: secret
```

###### 在 git 中使用加密值

1. 使用接口加密原始数据

   ConfigServer 暴露了一个 /encrypt 端口，使用 POST 请求 /encrypt 端口，其中包括要加密的数据，会收到一个加密的值作为响应

   ```shell
   # 加密字符串 password
   curl --location --request POST 'http://127.0.0.1:8888/encrypt' \
   --header 'Content-Type: text/plain' \
   --data-raw 'password'
   ```

2. 标识秘文名写入 git 属性

   ```yml
   spring:
   	datasource:
   		username: root
   		# 使用 '{cipher}' 标识该字符串为秘闻
   		password: '{cipher}01f4376a3ca7a72cd05da52e08cf6407fd4714f432899374e85bbb9b78004352'
   ```

3. 请求属性时 ConfigServer 会自动解密

   默认情况下，ConfigServer 提供的所有加密值只是在后端 git 仓库中处于加密状态，它们在对外提供之前会解密，如果需要以未解密的形式对外提供加密属性，配置 spring.cloud.config.server.encrypt.enabled = false，则 ConfigServer 会返回秘文

##### 运行时刷新配置

###### 手动刷新配置

设置为 ConfigServer 客户端时，自动配置功能会配置一个特殊的 Actuator 端点，用来刷新配置属性

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
  <artifactId>spring-boot-starter-actuator</artifactId>
</dependency>
```

在 ConfigServer 客户端添加 actuator 后，可以在任意时间发送 HTTP POST 请求到 “/actuator/refresh"，通知它从后端仓库刷新配置属性

```shell
curl localhost:53419/actuator/refresh -X POST
```

###### 使用消息通知自动刷新

1. 创建 webhook

   使用 gogs 创建 webhook，PayloadURL 为 ConfigServer 的 /monitor 端点，ContentType 为 application/json。

2. 在 ConfigServer 中处理 webhook 更新

   ConfigServer 中添加依赖，自动启用 /monitor 端点

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-config-monitor</artifactId>
   </dependency>
   ```

   添加 Spring Cloud Stream 依赖，创建通过底层绑定机制通信的服务，这种机制可能是 RabbitMQ 或 Kafka。服务在编写的时候并不会关心如何使用这些通信机制，只是接受流中的数据，对其进行处理，并返回到流中，由下游的服务继续处理

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-starter-stream-rabbit</artifactId>
   </dependency>
   ```

   修改配置以适应 kafka 或 rabbit 

3. 创建 Gogs 的通知提取器

   对于每个 Git 实现来说，webhook post 请求所携带的内容会有所不同，对于 /monitor 端点，很重要的一点就是在处理 webhook post 请求时能够理解不同的数据格式。

   在幕后，/monitor 端点会有一组组件来检查 POST 请求，试图弄清楚请求来自那种 Git 服务器，然后将请求数据映射为通用的通知类型，并发送至每个客户端。ConfigServer 对多个流行的 Git 实现（Github、GitLab、Bitbucket）提供了开箱即用的支持

4. 在 ConfigServer 的客户端中启用自动刷新

   添加依赖

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-starter-bus-amqp</artifactId>
   </dependency>
   ```

   修改配置以适应 kafka 或 rabbit

#### 服务失败与延迟

##### 断路器模式

软件中的断路器起初会处于关闭状态，允许方法调用，如果方法调用失败，断路器就会打开，就不会对失败的方法再执行调用，提供了后备行为和自校正功能

如果被保护的方法再给定的失败阈值内发生了失败，那么可以调用一个后备方法代替它的位置，在断路器处于打开状态之后，几乎始终都会调用后备方法。处于打开状态的断路器偶尔会进入半开状态，并尝试调用发生失败的方法，如果依然失败，断路器就恢复为打开状态；如果调用成功，它会认为问题已经解决，断路器会回到闭合状态

断路器是应用到方法上的，在给定的一个微服务中，决定在代码的什么地方声明断路器其实就是识别那些方法易于出现失败，当遇到失败时，微服务应用：在微服务中发生的事情，就留在微服务中

如下方法是断路器保护首选：

* 调用 REST 的方法

  可能会因为远程服务不可用或者返回 HTTP 500 错误而失败

* 执行数据库查询的方法

  可能因为数据库不响应死锁而失败

* 可能会比较慢的方法

在微服务中，某个执行缓慢的微服务会拖慢整个微服务的性能，避免上游的服务产生级联延迟是非常重要的

##### Hystrix

Netflix Hystrix 是断路器模式的 java 实现，为一个切面，会在目标方法发生失败的时候触发后备方法，还会追踪目标方法失败的频率，如果失败超过了某个阈值，那么所有的请求都会转发至后备方法

###### 使用断路器

1. 依赖

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-starter-netflix-hystrix</artifactId>
   </dependency>
   ```

2. 启用 Hystrix

   在主配置类上添加 @EnableHystrix 注解启用

3. 使用

   使用 @HystrixCommand 注解在方法上声明断路器切面

###### 断路器注解

* @HystrixCommand

  |       属性        |                             含义                             |
  | :---------------: | :----------------------------------------------------------: |
  |  fallbackMethod   | 后备方法，要与原始方法具有相同的签名（除了方法名称），支持嵌套备用方法 |
  | commandProperties | 一个或多个 @HystrixProperty 注解组成数组，指定了要设置的属性名和值 |
  |                   |                                                              |

  默认情况下，所有带有 @HystrixCommand 注解的方法都会在 1 秒后超时，并调用它们声明的后备方法。

  commandProperties 支持属性名

  |                       属性                       |           含义           |
  | :----------------------------------------------: | :----------------------: |
  | execution.isolation.thread.timeoutInMilliseconds |     指定超时时间毫秒     |
  |            execution.timeout.enabled             |      false 取消超时      |
  |     metrics.rollingState.timeInMilliseconds      | 设置断路器在时间段内操作 |
  |      circuitBreaker.requestVolumeThreshold       |                          |

  如果在 metrics.rollingState.timeInMilliseconds 设定的时间范围内超出了 ci rcuitBreaker.requestValuemThreshold 和 circuitBreaker.errorThresholdPercentage 设置的值，那么断路器将会进入打开状态。在circuitBreaker.sleepWindowInMilliseconds 限定的时间范围内，它会一直处于打开状态，在此之后将进入半打开状态，进入半打开状态后，将会再次尝试失败的原始方法

  ```java
  @HystrixCommand(fallbackMethod="getDefaultIngredients",
                  // 调整失败设置，20 秒内调用超过 30 次，且失败率超过 25%
                 commandProperties={
                   @HystrixProperty(
                     name="circuitBreaker.requestVolumeThreshold",
                     value="30"
                   ),
                   @HystrixProperty(
                   	 name="circuitBreaker.errorThresholdPercentage",
                     value="25"
                   ),
                   @HystrixProperty(
                   	 name="metrics.rollingStats.timeInMilliseconds",
                     value="20000"
                   )
                   // 处于打开状态后断路器必须保持 1 分钟，然后才进入半开状态
                   @HystrixProperty(
                   	 name="ciruitBreaker.sleepWindowInMillseconds",
                     value="60000"
                   )
                 })
  public list<User> getAllIngredients() {}
  ```

##### 监控

###### Hystrix 流

每当断路器保护的方法被调用时，它都会收集一些调用相关的数据，并将其发布到一个 HTTP 流中，这些数据可以实时监控正在运行中的应用健康状况，Hystrix 流包含：

* 方法被调用了多少次
* 调用成功了多少次
* 后备方法调用了多少次
* 方法超时了多少次

Hystrix 流是由 Actuator 端点提供的。需要添加依赖

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
  <artifactId>spring-boot-starter-actuator</artifactId>
</dependency>
```

Hystrix 流会通过端点 /actuator/hystrix.stream 对外暴露，默认情况下，大多数端点是禁用的，可以通过在每个应用的配置中启用

```yml
management:
	endpoints:
		web:
			exposure:
				include: hystrix.stream
```

###### Hystrix Dashboard

用来消费 Hystrix 流

1. 依赖

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-starter-netflix-hystrix-dashboard</artifactId>
   </dependency>
   ```

2. 在主配置类上添加 @EnableHystrixDashboard 注解启用

聚合流

1. 依赖

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
     <artifactId>spring-cloud-starter-netflix-turbine</artifactId>
   </dependency>
   ```

2. 在主配置类 上添加 @EnableTurbine 注解启用

3. 配置要聚合的流

   Turbine 会消费多个微服务的流并将它们的断路器指标合并到一个流中。它会作为 Eureka 的客户端，发现那些需要聚合到自己的流的服务。如果不想聚合 Eureka 上注册的所有服务，可以使用 turbine.app-config 属性配置

   ```yml
   turbine:
     # 收集 order-service，user-service 流
   	app-config: order-service,user-service
   	# 指定要收集 default 集群
   	cluster-name-expression: "'default'"
   ```

   



