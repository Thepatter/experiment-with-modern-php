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

2.应用主引导类声明 @EnableEurekaServer该项目为服务注册中心

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