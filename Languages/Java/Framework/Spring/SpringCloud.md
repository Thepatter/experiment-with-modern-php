### SpringCloud

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

  

