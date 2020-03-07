### 注解

#### Spring 内置注解

##### 类级别注解

###### @Controller

将类识别为控制器，并且将其作为组件扫描的候选者

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

###### @Autowired@Service

`@Autowired` 属于 `org.springframework.beans.factory.annotation` 包，通过注解 `@Autowired` 到字段或方法来实现依赖注入。为了能被注入，类必须要注明为 `@Service`，该类型是 `org.springframework.stereotype` 包的成员。`@Service` 注解类型指示类是一个服务。此外，在配置文件中，还需要添加一个 `<component-scan/>` 元素来扫描依赖基本包：

```xml
<context:component-scae base-package="dependencyPackage"/>
```

###### @Repository

组件扫描会发现它，并且会将其初始化为 spring 上下文中的 bean

@SessionAttributes

类级别的 @SessionAttributes 能够指定模型对象要保存在 session 中

###### @RestController

类似于 @Controller 和 @Service 的构造型注解，能够让类被组件扫描功能发现。且控制器中的所有处理方的返回值都要直接写入响应体中，而不是将值放到模型中并传递给一个试图以便于进行渲染

###### @Component

##### 方法级别注解

###### @CrossOrigin

允许跨域

```
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

###### @PathVariable

指定 url 占位符

###### @RequestParam

使用 `org.springframework.web.bind.annotation.RequestParam` 注解类型来注解方法参数。

```java
// 请求参数 http://domain/app/product?productId=3
public void sendProduct(@RequestParam int productId)
```

`@RequestParam` 注解的参数类型不一定是字符串。路径变量类似请求参数，但没有 `key` 部分，只是一个值。路径变量的类型可以不是字符串，Spring MVC 将尽力转换为非字符串类型

```java
// 路径变量 http://domain/app/product/3
@RequestMapping(value = "/product/{id}")
public String viewProduct(@PathVariable Long id, Model model) {
	Product product = productService.get(id);
	model.addAttribute("product", product);
}
```

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

###### @Bean

会初始化 bean 并立即为它的属性设置值

###### @ResponseStatus

指定响应状态码

###### @RequestBody

请求应该被转换为一个 Book 对象并绑定到该参数上

```
public Book store(@RequestBody Book book) {}
```

##### Spring Data JPA

###### @Entity

类级别，声明 JPA 实体

###### @Id

属性上声明该属性为数据库表主键

###### @GeneratedValue

属性上声明该值为数据库自增值

```java
@GeneratedValue(startegy=GenerationType.AUTO)
```

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

###### @Table

类级别注解，指定表名

###### @Query

方法注解，声明方法调用时要执行的查询

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

