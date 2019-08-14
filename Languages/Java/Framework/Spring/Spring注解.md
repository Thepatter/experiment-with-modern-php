## Spring 注解

### Controller 注解类型

`org.springframework.stereotype.Controller` 注解类型用于指示 `Spring` 类的实例是一个控制器。Spring 使用扫描机制来找到应用程序中所有基于注解的控制器类。

### RequestMapping 注解

`org.springframework.web.bind.annotation.RequestMapping` 注释类型映射的 URI 与方法

* value

  指定 url，如果只有该属性，可以省略属性名称

* method

  指示该方法的 HTTP 方法

  ```java
  @RequestMapping(value="/order_process", method={RequestMethod.POST, RequestMethod.PUT)
  ```

如果 RequestMapping 注解类型用来注解一个控制器类，这种情况下，所有的方法都将映射为相对于类级别的请求

```java
@Controller
@RequestMapping("/customer")
public class CustomerController {
	@RequestMapping(value="/delete", method={RequestMethod.POST, RequestMethod.PUT})
	public String deleteCustomer() {
		...
	}
}
```

由于控制器类的映射使用 `/customer`，而 `deleteCustomer` 方法映射为 `/delete`，则 `http://domain/context/customer/delete` 将会映射在 `deleteCustomer` 方法上

### 依赖注入 @Autowired@Service

`@Autowired` 属于 `org.springframework.beans.factory.annotation` 包，通过注解 `@Autowired` 到字段或方法来实现依赖注入。为了能被注入，类必须要注明为 `@Service`，该类型是 `org.springframework.stereotype` 包的成员。`@Service` 注解类型指示类是一个服务。此外，在配置文件中，还需要添加一个 `<component-scan/>` 元素来扫描依赖基本包：

```xml
<context:component-scae base-package="dependencyPackage"/>
```

### 注解方法参数

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

#### @ModelAttribute

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

