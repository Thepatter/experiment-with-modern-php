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

由于控制器类的映射使用 `/customer`，而 `deleteCustomer` 方法映射为 `/delete`，则 `http://domain/context/customer/delete` 将会映射在 `deleteCustomer` 方法上。