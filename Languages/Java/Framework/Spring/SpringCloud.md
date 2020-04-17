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
  
* Actuator

  服务检查

##### 开发流程

1. 搭建 Spring Cloud Config Server，依据服务拆分，为每个微服务端点提供不同环境不同配置
2. 搭建 Spring Cloud Eureka 中心，作为 Spring Cloud Config Client 获取配置
3. 搭建微服务端点，作为 Spring Cloud Eureka Client 和 Spring Cloud Client 端点消费服务中心与服务配置
4. 搭建 Hystrix Dashboard 监控流，作为 Spring Cloud Config Server Client 和 Spring Cloud Eureka Client

#### 服务发现

服务发现对于微服务和基于云的应用提供了：

* 可以快速地对在环境中运行的服务实例数量进行水平伸缩，通过服务发现，服务消费者能够将服务的物理位置抽象出来。由于服务消费者不知道实际服务实例的物理位置，因此可以从可用服务池中添加或移除服务实例
* 有助于提高应用程序的弹性，当微服务实例变得不健康或不可用时，大多数服务发现引擎将从内部可用服务列表中移除该实例。由于服务发现引擎会在路由时绕过不可用服务，因此能够使不可用服务造成的损害最小

##### 服务发现架构

1. 启动一个或多个服务发现节点，这些服务发现实例通常是独立的，在它们之前一般不会有负载均衡器
2. 当服务实例启动时，它们将通过一个或多个服务发现实例来注册它们可以访问的物理位置、路径和端口（每个服务实例都将以相同的服务 ID 进行注册）
3. 服务通常只在一个服务发现实例中进行注册。大多数服务发现的实现使用数据传播的点对点模型，每个服务实例的数据都被传递到服务发现集群中的所有其他节点
4. 每个服务实例将通过服务发现服务去推送服务实例的状态，或者服务发现服务从服务实例拉取状态。任何未能返回良好的健康检查信息的服务都将从可用服务实例池中删除
5. 服务在向服务发现服务进行注册之后，这个服务就可以被需要使用这项服务功能的应用程序或其他服务使用。客户端可以使用不同的模型来发现服务，在每次调用服务时，客户端可以只依赖于服务发现引擎来解析服务位置。（每次调用注册的微服务实例时，服务发现引擎就会被调用，但很脆弱，服务客户端完全依赖于服务发现引擎来查找和调用服务，更健壮的方法是使用客户端负载均衡）

客户端服务调用流程：

1. 当服务客户端需要调用服务时，它将检查本地缓存的服务实例 IP。服务实例之间的负载均衡会发生在该服务上
2. 如果客户端在缓存中找到一个服务 IP，那么客户端将使用它，否则，客户端将会联系服务发现
3. 客户端缓存将定期使用服务发现层进行刷新。客户端缓存最终是一致的，但是始终存在这样的风险（在客户端联系服务发现实例以进行刷新和调用时，调用可能会被定向到不健康的服务实例上）
4. 如果在调用服务的过程中，服务调用失败，那么本地的服务发现缓存失效，服务发现客户端将尝试从服务发现代理刷新数据

###### 服务注册

服务如何使用服务发现代理进行注册：一个服务上线时，这个服务会向服务发现代理注册它的 IP 地址 

###### 服务地址的客户端查找

服务客户端查找服务信息的方法：客户端不会直接指导服务的 IP 地址，从服务发现代理那里获取服务的 IP 地址，可以通过逻辑名称从服务发现代理查找服务的位置

###### 信息共享

如何跨节点共享服务信息：服务发现节点共享服务实例的健康信息

###### 健康监测

服务如何将它的健康信息传回给服务发现代理：服务向服务发现代理发送心跳包。如果服务死亡，服务发现层将移除死亡的实例的 IP

##### Eureka

###### Server

在微服务应用中，Eureka 会担当所有服务的注册中心。Eureka 本身也可以视为一个微服务，在整体应用中它的目的是让其他的服务能够互相发现

当服务实例启动时，它会按照名称将自己注册到 Eureka 中。Eureka 希望服务实例能够注册上来，并且每隔 30 秒向它发送一次注册更新请求。通常，如果 Eureka 在 3 个更新周期内没有收到服务的更新请求，就会将该服务注销。

自我保护模式下，不会注销服务实例，在生产环境中，自我保护模式可以防止在网络出现故障时更新请求无法发送至 Eureka 所导致的活跃服务被注销。自我保护模式会将已停止服务的注册项保留下来。

使用 `http://eureka.service:8761/eureka/apps/<APPID>` 查看单个服务信息

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
		# 生产环境为 true, eureka 客户端获取注册表的本地副本，ture 将在本地缓存注册表，而不是每次查找 eureka 服务
		fetch-registry: false 
		# 生产环境为 true，注册自身，从其他 eureka 获取信息
		register-with-eureka: false  
		service-url:
			# 包含客户端用于解析服务位置的 Eureka 服务的列表
			defaultZone: 
				http://${eureka.instance.hostname}:${server.port}/eureka
	server:
		enable-self-preservation: false  # 禁用自我保护模式
server:
	port: 8761 # Eureka 客户端默认监听端口
```

###### Client

为了让应用成为服务注册中心的客户端，需要将 Eureka 客户端添加到服务应用的构建文件中

1. 依赖

   ```xml
   <dependency>
   	<groupId>org.springframework.cloud</groupId>
       <artifactId>spring-cloud-starter-netflix-eureka-client</artifactId>
   </dependency>
   ```

2. properties 配置

   ```properties
   server.port=0
   # 服务名称
   spring.application.name=order-service
   eureka.client.service-url.defaultZone=http://eurekal.tacocloud.com:8761/eureka/,http://eurekal.taocloud.com:8761/eureka/
   ```

#### 消费服务

支持负载均衡的 RestTemplate，Feign 生成的客户端接口来进行消费

##### Ribbon

作为客户端的负载均衡器能够按照客户的的数据成比例伸缩，每个负载均衡器都可以配置成最适合对应客户端的负载算法，而不必对所有服务都使用相同的配置

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
	public IngredientServiceClient(@LoadBalanced RestTemplate rest) {							this.rest = rest;
	}
	public Ingredient getIngredientById(String ingredientId) {
		return rest.getForObject("http://ingredient-service/ingredients/{id}", Ingredient.class, ingredientId);
	}
}
```

使用服务名 ingredient-service，在内部，RestTemplate 会要求 Ribbon 根据名称查找服务并从中选择一个实例，Ribbon 会将 URL 重写为选定服务实例的主机和端口

##### Feign

Feign 是启用 Ribbon 的 RestTemplate 类替代方案，Feign 库采用不同的方法来调用 REST 服务，方法是让开发人员定义一个 Java 接口，然后使用 Spring Cloud 注解来标注接口，以映射 Ribbon 将要调用的基于 Eureka 的服务。

Spring Cloud 框架将动态生成一个代理类，用于调用目标 REST 服务。除了编写接口定义，开发人员不需要编写调用服务的代码

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

当在环境变量或 java 系统属性中设置配置属性时，修改这些属性需要应用重启，如果选择将属性打包到要部署的 JAR 或 WAR 文件中，那么在属性变更时，必须要完全重新构建和重新部署应用。

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

     处于激活状态的 Spring profile，请求特定环境的 profile 时，将返回所请求的 profile 和默认的 profile

   * master

     git 标签/分支（可选）默认 master

###### 配置中心配置

* 将配置放到相对于 git 仓库子目录下来组织配置，只需在 config-server 的 properties 下指定搜索路径

  ```properties
  spring.cloud.config.server.git.uri=http://github.com/cloud/config
  # 指定搜索路径，搜索 config 目录和 more 开头的目录
  spring.cloud.config.server.git.search-paths=config,more*
  # 指定默认 git 分支
  spring.cloud.config.server.git.default-label: v1
  # git auth
  spring.cloud.config.server.git.username=admin
  spring.cloud.config.server.git.password=secret
  ```

* 使用文件系统组织配置

  ```properties
  # 指定存储配置的后端存储库为文件系统
  spring.profiles.active=native
  # 文件存储位置的路径，支持逗号分隔文件夹列表。file:/// 协议
  spring.cloud.config.server.native.search-locations=file:///path/to/app/config
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
     	<artifactId>spring-cloud-config-client</artifactId>
   </dependency>
   ```

2. 配置

   配置信息可以在 bootstrap.yml 和 application.yml 两个配置文件之一中设置。
   
   在其他所有配置信息被使用之前，bootstrap.yml 文件要先读取应用程序属性。一般来说，bootstrap.yml 文件包含服务的应用程序名称、应用程序 profile 和连接的 Spring Cloud Config 服务器的 URI
   
   希望保留在本地服务的其他配置，都可以在服务中的 application.yml 文件中进行本地设置
   
   ```properties
   spring.cloud.config.uri=http://cloud.service.com:8888
   spring.application.name=product-service
   ```

当应用启动时，Config Server Client 会对 Config Server 发送请求，接收的属性将会放到应用环境中，并缓存起来，即便 Config Server 停机也依然可用

##### 存储加密属性

ConfigServer 可以借助存储在 Git 中的属性文件提供加密值

###### 使用对称密钥加密

使用一个密钥来配置 ConfigServer，在将属性提供给客户端应用之前，ConfigServer 使用这个密钥对数值进行解密。在 ConfigServer 中将 encrypt.key 属性设置密钥值，这个属性要设置到 bootstrap 配置中（bootstrap.properties 或 bootstrap.yml，在自动配置功能启用 Config Server 之前，才会加载和启用）

```yml
encrypt.key: secretmd5sum
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
   		# 使用 '{cipher}' 标识该字符串为密文
   		password: '{cipher}01f4376a3ca7a72cd05da52e08cf6407fd4714f432899374e85bbb9b78004352'
   ```

3. 请求属性时 ConfigServer 会自动解密

   默认情况下，ConfigServer 提供的所有加密值只是在后端 git 仓库中处于加密状态，它们在对外提供之前会解密，如果需要以未解密的形式对外提供加密属性，配置 spring.cloud.config.server.encrypt.enabled = false，则 ConfigServer 会返回秘文

###### 客户端解密

1. 配置 Spring Cloud Config 不要在服务器端解密属性

2. 在许可证服务器上设置对称密钥

3. 将 spring-security-rsa 依赖添加到客户端

   ```xml
   <dependency>
   	<groupId>org.springframework.security</groupId>
       <artifactId>spring-security-rsa</artifactId>
   </dependency>
   ```


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

##### 注册中心配置中心

* 微服务中，通过 Config Server 了解 Eureka 服务注册中心。

* 还可将 Config Server 本身注册到 Eureka 中，让微服务发现去查找 Config Server。

  这种模式，需要将Config Server 变成服务发现的客户端，并将 spring.cloud.config.discovery.enabled 属性设置为 false。这样的话，Config Server 会将自身以 “configserver” 名称注册到 Eureka 中。

  这种方式下，每个服务在启动的时候都要调用两次外部的服务：第一次调用 Eureka 发现 Config Server 的位置，第二次调用 Config Server 获取配置数据

#### 服务失败与延迟

##### 服务失败处理

###### 客户端弹性模式

在远程服务发生错误或表现不佳时保护远程资源的客户端免于崩溃。目标是让客户端快速失败，而不消耗诸如资源，并且可以防止远程服务的问题向客户端的消费者上游传播。

常见客户端弹性模式：

*   客户端负载均衡（Client load balance）

    客户端负载均衡涉及让客户端从服务发现代理查找服务的所有实例，然后缓存服务实例的物理位置。每当消费者需要调用该服务实例时，客户端负载均衡器将从它维护的服务位置池中返回一个位置。

    负载均衡器可以检测服务实例是否抛出错误或表现不佳。如果客户端负载均衡器检测到问题，它可以从可用服务位置池中移除该服务实例，并防止将来的服务调用访问该服务实例。

*   断路器（circuit breaker）

    当远程服务被调用时，断路器将监视这个调用。如果调用时间太长，断路器将会介入并中断调用。断路器将监视所有对远程资源的调用，如果对某一个远程资源的调用失败次数足够多的，那么断路器实现就会出现并采取快速失败，阻止将来调用失败的远程资源

*   后备（fallback）

    当远程服务调用失败时，服务消费者将执行替代代码路径，并尝试通过其他方式执行操作

*   舱壁（bulkhead）

    可以把远程资源的调用分到线程池中，并降低一个缓慢的远程资源调用拖垮整个应用程序的风险。线程池充当服务的舱壁，每个远程资源都是隔离的，并分配给线程池。如果一个服务响应缓慢，那么这种服务调用的线程池就会饱和并停止处理请求，而对其他服务的服务调用则不会变的饱和，它们会被分配给其他线程池

###### 断路器模式

软件中的断路器起初会处于关闭状态，允许方法调用，如果方法调用失败，断路器就会打开，就不会对失败的方法再执行调用，提供了后备行为和自校正功能

如果被保护的方法在给定的失败阈值内发生了失败，那么可以调用一个后备方法代替它的位置，在断路器处于打开状态之后，几乎始终都会调用后备方法。处于打开状态的断路器偶尔会进入半开状态，并尝试调用发生失败的方法，如果依然失败，断路器就恢复为打开状态；如果调用成功，它会认为问题已经解决，断路器会回到闭合状态

断路器是应用到方法上的，在给定的一个微服务中，决定在代码的什么地方声明断路器其实就是识别那些方法易于出现失败，当遇到失败时，对于微服务应用，应在微服务中发生的事情，就留在微服务中

如下方法是断路器保护首选：

* 调用 REST 的方法

  可能会因为远程服务不可用或者返回 HTTP 500 错误而失败

* 执行数据库查询的方法

  可能因为数据库不响应死锁而失败

* 可能会比较慢的方法

在微服务中，某个执行缓慢的微服务会拖慢整个微服务的性能，避免上游的服务产生级联延迟是非常重要的

##### Hystrix

构建断路器模式、后备模式和舱壁模式的实现需要对线程和线程管理有深入的理解。

Netflix Hystrix 是断路器模式的 java 实现，为一个切面，会在目标方法发生失败的时候触发后备方法，还会追踪目标方法失败的频率，如果失败超过了某个阈值，那么所有的请求都会转发至后备方法

在配置 Hystrix 时，可以使用 Application、class、function 级别的配置。每个 Hystrix 属性都有默认设置的值，这些值将被应用程序中每个 @HystrixCommand 注解所使用，除非这些属性值在 java  类级别被设置，或者被类中单个 Hystrix 线程池级别的值覆盖

###### 使用

Hystrix 实现了后备、断路器、舱壁模式

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
   
   ```java
     @HystrixCommand(
         fallbackMethod="getDefaultIngredients", // 后备方法调用链底部必须有一个不会失败的方法
         threadPoolKey="services" // 定义唯一线程池名称
         threadPoolProperties={  // 定义线程池属性
             @HystrixProperty(name="coreSize", value=30),
             @HystrixProperty(name="maxQueueSize", value=20)
         }
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
   
   默认情况下，指定不带属性的 @HystrixCommand 注解时，这个注解会将所有远程服务调用都放在同一线程池下。

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

#### 服务网关

服务网关充当服务客户端和被调用的服务之间的中介。服务客户端仅与服务网关管理的单个 URL 进行连接。服务网关从服务客户端调用中分离出路径，并确定服务客户端正在尝试调用那个服务。服务网关充当应用程序内所有微服务调用的入站流量的入口。服务客户端永远不会直接调用单个服务的 URL，而是将所有调用都放到服务网关上

将横切关注点抽象成一个独立且作为应用程序中所有微服务调用的过滤器和路由器的服务。这种横切关注点被称为服务网关（service gatervay）。服务客户端不再直接调用服务，服务网关作为单个策略执行点（Policy Enforcement Point，PEP），所有调用都通过服务网关进行路由，然后被路由到最终目的地

##### Zuul

是 Netflix 开源服务网关，Spring Cloud 组合 Zuul 完成一下工作：

1.  将所有服务调用放在一个 URL 后面，并使用服务发现将这些调用映射到实际的服务实例
2.  将关联 ID 注入流经服务网关的每个服务调用中
3.  在从客户端发回的 HTTP 响应中注入关联 ID
4.  构建一个动态路由机制，将各个具体的组织路由到服务实例端点，该端点与其他人使用的服务实例端点不同

###### 快速开始

1.  依赖

    ```xml
    <dependency>
    	<groupId>org.springframework.cloud</groupId>
        <artifactId>spring-cloud-starter-zuul</artifactId>
    </dependency>
    ```

2.  使用 @EnableZuulProxy 注解声明 Zuul（Zuul 服务器默认设计为在 Spring 产品上工作，Zuul 将自动使用 Eureka 来通过服务 ID 查找服务，然后使用 Netflix Ribbon 对来自 Zuul 的请求进行客户端负载均衡）

3.  配置 erueka

###### 配置路由

Zuul 核心是一个反向代理。在微服务架构下，Zuul 从客户端接收微服务调用并将其转发给下游服务。

*   通过服务发现自动映射路由

    Zuul 的所有路由映射都是通过在 Zuul 的配置文件中定义路由来完成，Zuul 可以根据其服务 ID 自动路由请求，而无需配置，如果没有指定任何路由，Zuul 将自动使用正在调用的服务的 Eureka 服务 ID，并将其映射到下游服务实例。

    ```
    # uri 组成 Zuul + server_id + origin_uri
    http://zuul_service/app_service/v1/user/1
    # 列出所有路由
    http://zuul_service/routes
    ```

    使用 Eureka 与 Zuul 的优点在于，可以添加和删除服务的实例，而无须修改 Zuul。使用自动路由时，Zuul 只基于 Eureka 服务 ID 来公开服务，如果服务的实例没有在运行，Zuul 将不会公开该服务的路由

*   使用服务发现手动映射路由

    Zuul 允许在配置文件中明确定义路由映射

    ```yml
    zuul:
    	ignored-services: 'organizationservice' // 排除的 Eureka 服务 ID 列表逗号分隔
    	prefix: /api		// 所有已定义的服务都将添加前缀 /api
    	routes:
    		organizationservice: /organization/&shy;**
    ```

    如果在没有使用 Eureka 注册服务实例的情况下，手动将路由映射到服务发现 ID，那么 Zuul 仍然会显示这条路由，如果尝试为不存在的路由调用路由，Zuul 将返回 500 错误

*   使用静态 URL 手动映射路由

###### 过滤器

提供网关内的过滤器构建自定义逻辑。支持以下过滤器：

*   前置过滤器

    可以在 HTTP 请求到达实际服务之前对 HTTP 请求进行检查和修改，前置过滤器不能将用户重定向到不同的端点或服务

*   后置过滤器

*   路由过滤器

    用于在调用目标服务之前拦截调用，通常使用路由过滤器来确定是否进行某些级别的动态路由，路由过滤器可以更改服务所指向的目的地，路由过滤器可以将服务调用重定向到 Zuul 服务器被配置的发送路由以外的位置。Zuul 路由过滤器不会执行 HTTP 重定向，而是会终止传入的 HTTP 请求，然后代表原始调用者调用路由。

所有 Zuul 过滤器必须扩展 ZuulFilter 类，并覆盖以下方法：

*   filterType（返回过滤器类型）
*   filterOrder（返回一个整数值，指示不同的过滤器的执行顺序）
*   shouldFilter（返回一个布尔值，指示该过滤器是否要执行）
*   run（每次服务通过过滤器时执行的代码）

