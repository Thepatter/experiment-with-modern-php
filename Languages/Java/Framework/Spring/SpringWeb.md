### Spring Web

#### Spring MVC

Spring MVC 处理用户请求会经过以下流程：

1. 请求离开浏览器到达 *DispatcherServlet*
2. *DispatcherServlet* 会查询一个多个处理器映射
3. *DispatcherServlet* 将请求发送到与 URL 匹配的控制器
4. 控制器处理逻辑（一般控制器将业务逻辑委托给一个或多个服务处理），控制器将模型数据打包，并且标示出用于渲染输出的视图名，将请求连同模型和视图名发送回 *DispatcherServlet*
5. *DispatcherServlet* 使用视图解析器来将逻辑视图名匹配为特定的视图实现
6. *DispatcherServlet* 请求视图，在视图交付模型数据，视图将使用模型数据渲染输出，输出通过想要对象传递给客户端

##### 基础配置

*DispatcherServlet* 是 Spring MVC 的核心，在这里请求会第一次接触到框架，它负责路由请求。当 *DispatcherServlet* 启动时，它会创建 Spring 应用上下文，并加载配置文件或配置类中所声明的 Bean。

###### 注解配置

使用注解配置

容器中配置 *DispatcherServlet* 流程

1.  在 Servlet 3.0 环境中，容器会在类路径中查找实现 *java.servlet.ServletContainerInitializer* 接口的类，用它来配置 Servlet 容器。
2.  Spring 提供了这个接口的实现 *SpringServletContainerInitializer*，这个类会查找实现 *WebApplicationInitializer* 类并将配置的任务交给它们来完成
3.  Spring 3.2 增加了 *WebApplicationInitializer* 基础实现 *AbstractAnnotationConfigDispatcherServletInitializer*
4.  部署到 Servlet 3.0 容器时，容器会自动发现扩展 *AbstractAnnotationConfigDispatcherServletInitializer* 的类，并用它来配置 Servlet 上下文

*AbstractAnnotationConfigDispatcherServletInitializer* 会同时创建 *DispatcherServlet* 和 *ContextLoaderListener*，使用注解只能部署到支持 Servlet 3.0 的 Web 容器中，Tomcat 7 及以上

1. 配置 DispatcherServlet

   ```java
   import org.springframework.web.servlet.support.AbstractAnnotationConfigDispatcherServletInitializer;
   
   public class WebInitializer extends AbstractAnnotationConfigDispatcherServletInitializer {
       @Override
       protected String[] getServletMappings() {
           return new String[] { "/" };  // 将 DispatcherServlet 映射到 “/”，应用的默认 Servlet，会处理进入应用的所有请求
       }
       // 返回带有 @Configuration 注解的类将会用来配置 ContextLoaderListener 创建的应用上下文中的 bean
       @Override protected Class<?>[] getRootConfigClasses() {
           return new Class<?>[] { RootConfig.class };   // 指定 Root 配置类
       }
       // 返回带有 @Configuration 注解的类将会用来定义 DispatcherServlet 应用上下文中的 bean
       @Override protected Class<?>[] getServletConfigClasses() {
           return new Class<?>[] { WebConfig.class };  
       }
       /**
        * 重载 customizeRegistration 额外配置 DispatcherServlet
        */
       @Override
       protected void customizeRegistration(ServletRegistration.Dynamic registration) {
           registration.setLoadOnStartup(0);
           registration.setRunAsRole("manager");
           registration.setMultipartConfig(new MultipartConfigElement("/tmp/uploads", 200000, 200000, 0));
       }
       // 返回的所有 FIlter 都会映射到 DispatcherServlet 上
       @Override protected Filter[] getServletFilters() {
           return new Filter[]{ new MyFilter() };
       }
   } 
   ```

2. 配置视图解析器

   ```java
   import org.springframework.context.annotation.Bean;
   import org.springframework.context.annotation.ComponentScan;
   import org.springframework.context.annotation.Configuration;
   import org.springframework.web.servlet.ViewResolver;
   import org.springframework.web.servlet.config.annotation.DefaultServletHandlerConfigurer;
   import org.springframework.web.servlet.config.annotation.EnableWebMvc;
   import org.springframwork.web.servlet.config.annotation.WebMvcConfigurerAdapter;
   import org.springframwork.web.servlet.view.InternalResourceViewResolver;
   
   @Configuration
   @EnableWebMvc
   @ComponentScan("spitter.web")
   public class WebConfig extends WebMvcConfigurerAdapter {
       // 视图解析器（默认使用 BeanNameViewResolver，会查找 ID 与视图名称匹配的 bean，并且查找的 bean 要实现 view 接口）
       @Bean public ViewResolver viewResolver() {
           InternalResourceViewResolver resolver = new InternalResourceViewResolver();
           resolver.setPrefix("/WEB-INF/views/");
           resolver.setSuffix(".jsp");
           resolver.setExposeContextBeansAsAttributes(true);
           return resolver;
       }
       // 配置 DispatcherServlet 对静态资源的请求转发到 Servlet 容器中默认的 Servlet 上，而不是使用 DispatcherServlet 本身来处理此类要求
       @Override public void configureDefaultServletHandling(DefaultServletHandlerConfigurer configurer) {
          	configurer.enable();
       }
   }
   ```

3. 配置 ContextLoader

   ```java
   import org.springframework.context.annotation.ComponentScan;
   import org.springframework.context.annotation.ComponentScan.Filter;
   import org.springframework.context.annotation.Configuration;
   import org.springframework.context.annotation.FilterType;
   @Configuration
   @ComponentScan(basePackages={"apps"}, excludeFilters = {@Filter(type=FilterType.ANNOTATION,value=EnableWebMvc.class)})
   public class RootConfig{}
   ```

如果要在 Web 容器中注册其他组件，只需创建一个新的初始化器，实现 Spring 的 *WebApplicationInitializer* 接口

```java
public class MyServletInitializer implements WebApplicationInitializer {
	@Override
	public void onStartup(ServletContext servletContext) throws ServletException {
		// 注册 Servlet
		Dynamic myServlet = servletContext.addServlet("myServlet", MyServlet.class);
		// 映射 Servlet
		myServlet.addMapping("/custom/**");
        // 注册 Filter
        javax.Servlet.FilterRegistration.Dynamic filter = servletContext.addFiter("myFilter", MyFilter.class);
        // 添加 filter 映射路径
        filter.addMappingForUrlPatterns(null, false, "/custom/**")
	}
}
```

###### XML 配置

传统方式搭建 DispatcherServlet 和 ContextLoaderListener，在 *web.xml* 文件中配置

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<web-app version="2.5"
         xmlns="http://java.sun.com/xml/ns/javaee"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://java.sun.com/xml/ns/javaee
                             http://java.sun.com/xml/ns/javaee/web-app_2_5.xsd">
    
    <context-param>
    	<param-name>contextConfigLocation</param-name>
        <param-value>/WEB-INF/Spring/root-context.xml</param-value> <!-- 设置根上下文配置文件位置 -->
    </context-param>
    <listener>
        <!-- 注册 ContextLoaderListener -->
    	<listener-class>
        	org.springframework.web.context.ContextLoaderListener
        </listener-class>
    </listener>
    
	<servlet>
    	<!--默认DispatcherServlet加载时依据Servlet名XML文件中加载Spring应用上下文(此处/WEB-INF/app-context.xml)-->
		<servlet-name>app</servlet-name> 
    	<servlet-class>org.springframework.web.servlet.DispatcherServlet</servlet-class>
    	<load-on-startup>1</load-on-startup>
        <!-- 显示指定配置文件位置 -->
        <init-param>
        	<param-name>contextConfigLocation</param-name>
            <param-value>/WEB-INF/spring/appServlet/servlet-context.xml</param-value>
        </init-param>
        <!-- multipart 上传配置 -->
        <multipart-config>
        	<location>/tmp/spittr/uploads</location>
            <max-file-size>2097152</max-file-size>
            <max-request-size>4194304</max-request-size>
        </multipart-config>
	</servlet>
	<servlet-mapping>
		<servlet-name>spring</servlet-name>
    	<url-pattern>/</url-pattern>
	</servlet-mapping>
</web-app>
```

在 *app-context.xml*  配置 Spring 上下文

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<beans>
    <!-- 静态资源的请求路径以 resources 开始，提供服务的文件位置为 resources -->
    <mvc:resources mapping="/resources/**" location="/resources/" />
</beans>
```

###### 混合使用注解和 XML

*web.xml* 中配置 DispatcherServlet 的 Java 类和 ContextLoaderListener 的 Java 类

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<web-app version="2.5" 
         xmlns="http://java.sun.com/xml/ns/javaee"
         xmlns:xsi="http://www.w3.org/2001/XMLSchem-instance"
         xsi:schemaLocation="http://java.sun.com/xml/ns/javaee
                             http://java.sun.com/xml/ns/javaee/web-app_2_5.xsd">
	
    <context-param>
    	<param-name>contextClass</param-name> <!-- 使用 Java 配置 -->
        <param-value>org.springframework.web.context.support.AnnotationConfigWebApplicationContext</param-value>
    </context-param>
    <context-param>
        <!-- 指定根配置类 -->
    	<param-name>contextConfigLocation</param-name>
        <param-value>com.group.config.RootConfig</param-value>  <!-- 指定根配置类 -->
    </context-param>
    
    <listener>
    	<listener-class>org.springframework.web.context.ContextLoaderListener</listener-class>
    </listener>
    <servlet>
    	<servlet-name>appServlet</servlet-name>
        <servlet-class>org.springframework.web.servlet.DispatcherServlet</servlet-class>
    	<init-param>
            <param-name>contextClass</param-name> <!-- 使用 Java  配置类 -->
            <param-value>org.springframework.web.context.support.AnnotationConfigWebApplicationContext</param-value>
        </init-param>
        <init-param>
        	<param-name>contextConfigLocation</param-name>
            <param-value>com.group.config.WebConfig</param-value> <!-- 指定 DispatcherServlet 配置类 -->
        </init-param>
        <load-on-startup>1</load-on-startup>
    </servlet>
    <servlet-mapping>
    	<servlet-name>appServlet</servlet-name>
        <url-pattern>/</url-pattern>
    </servlet-mapping>
</web-app>	
```

##### 请求处理

###### 请求视图

*Controller*

```JAVA
@Controller
public class IndexController {
	@GetMapping("/")
	public String home() {
        return "index";  // 返回 spring 对应视图名，前缀为 redirect: 时重定向，forward: 请求该 url
    }
}
```

当控制器的结果是重定向时，原始的请求就结束了，并且会发起一个新的请求。此时可以为模型添加属性会作为查询参数传递给新的请求（简单属性（标量和字符串））。对于对象类型的属性可以使用 flash 属性（flash 属性会一直携带这些数据直到下一次请求，然后才消失，model.addFlashAttribute 方法添加 flash 属性），在重定向执行之前，所有的 flash 属性都会复制到会话中。在重定向之后，存在会话中的 flash 属性会被取出，并从会话转移到模型之中。

*Test*

```java
import com.scyxin.employeecenter.home.IndexController;
import org.junit.jupiter.api.Test;
import org.springframework.test.web.servlet.MockMvc;
import org.springframework.test.web.servlet.request.MockMvcRequestBuilders;
import org.springframework.test.web.servlet.result.MockMvcResultMatchers;
import org.springframework.test.web.servlet.setup.MockMvcBuilders;

public class IndexControllerTests {
    @Test
    public void testIndex() throws Exception {
        IndexController indexController = new IndexController();
    	// spring boot 使用时 mockmvc 需配置 viewResolver
        MockMvc mockMvc = MockMvcBuilders.standaloneSetup(indexController).build();
        mockMvc.perform(MockMvcRequestBuilders.get("/"))
                .andExpect(MockMvcResultMatchers.view().name("home"));
    }
}
```

###### 模型数据绑定视图

*Controller*

```java
public class IndexController {
	@GetMapping("/modules")
	public String modules(Model model) {
		model.addAttribute(ModuleRepository.allModules());
		return "modules";
	}
}
```

*Test*

```java
 @Test
  public void shouldShowPagedSpittles() throws Exception {
    List<Spittle> expectedSpittles = createSpittleList(50);
    SpittleRepository mockRepository = mock(SpittleRepository.class);
    when(mockRepository.findSpittles(238900, 50))
        .thenReturn(expectedSpittles);
    
    SpittleController controller = new SpittleController(mockRepository);
    MockMvc mockMvc = standaloneSetup(controller)
        .setSingleView(new InternalResourceView("/WEB-INF/views/spittles.jsp"))
        .build();

    mockMvc.perform(get("/spittles?max=238900&count=50"))
      .andExpect(view().name("spittles"))
      .andExpect(model().attributeExists("spittleList"))
      .andExpect(model().attribute("spittleList", 
                 hasItems(expectedSpittles.toArray())));
  }
```

###### 请求参数

* 查询参数

  使用 @RequestParam 获取请求参数，@RequestParam 的 value 属性与方法参数名一致时，可以省略 value 属性

* 表单参数

  1.表单字段需与对应对象属性一致时，会将字段值填充到对象属性中，可以在对象属性上指定验证规则来验证表单字段属性

  2.使用 @RequestParam 获取请求表单字段

* 路径变量

  url 中变量使用 {} 占位符包裹，方法使用 @PathVariable 获取，参数与占位符一致时可省略 @PathVariable 的 value 属性

可以通过在模型字段上设置验证注解规则来验证请求表单对应对象，并在控制器方法参数中注入一个 Errors，并检查 errors.hasErrors() 是否为真来判断请求参数

###### multipart

multipart 格式的数据会将一个表单拆分为多个部分，每个部分对应一个输入域。在一般的表单输入域，它对应的部分中会放置文本型数据，如果上传文件，它对应部分为二进制。*DispatcherServlet* 委托 Spring 中的 MultipartResolver 接口实现处理 multipart

* *CommonsMultipartResolver*

  使用 Jakarta Commons FileUpload 解析 multipart 请求，spring 内置了 *CommonsMultipartResolver*，可作为 *StandardServletMultipartResolver* 的替代方案

  ```java
  // 声明 CommonsMultipartResolver Bean
  @Bean
  public MultipartResolver multipartResolver() {
  	CommonsMultipartResolver multipartResolver = new CommonsMultipartResolver();
  	multipartResolver.setUploadTempDir(new FileSystemResource("/tmp/spittr/uploads"));
      multipartResolver.setMaxUploadsSize(20000);
      multipartResolver.setMaxInMemerySize(0);
  	return multipartResolver; // 默认使用 Servlet 容器的临时目录，无法指定请求体最大容量
  }
  ```

  直接在控制器方法参数上添加 @RequestPart 注解

  ```
  @RequestMapping(value="register", method=HTTP.POST)
  public String store(@RequestPart("avatar") MultipartFile avatar, @Valid profile profile, Errors errors) {}
  ```

* *StandardServletMultipartResolver*

  依赖于 Servlet 3.0 对 multipart 请求的支持（Spring 3.1）

  ```
  // 声明 StandardServletMultipartResolver Bean,通过重载 customizeRegistration 方法配置上传参数
  @Bean
  public MultipartResolver multipartResolver() throws IOException {
  	return new StandardServletMultipartResolver();
  }
  ```

  使用 Http.part 接受请求 part

##### 异常处理

Servlet 请求的输出都是一个 Servlet 响应，如果在请求处理的时候，出现了异常，那么它的输出依然是 Servlet 响应，异常必须要以某种方式转换为响应。Spring 提供了多种方式将异常转换为响应：

* 特定的 Spring 异常将会自动映射为指定的 HTTP 状态码
* 异常上可以添加 @ResponseStatus 朱建平，从而将其映射为一个 HTTP 状态码
* 在方法上可以添加 @ExceptionHandler 注解，使其用来处理异常

###### 将异常映射为 HTTP 状态码

默认情况下，Spring 会将自身的一些异常自动转换为合适的状态码

|              Spring 异常               |        HTTP 状态码         |
| :------------------------------------: | :------------------------: |
|             BindException              |      400 Bad Request       |
|    ConversionNotSupportedException     | 500 Internal Server Error  |
|  HttpMediaTypeNotAcceptableException   |     406 Not Acceptable     |
|   HttpMediaTypeNotSupportedException   | 415 Unsupported Media Type |
|    HttpMessageNotReadableException     |      400 Bad Request       |
|    HttpMessageNotWritableException     | 500 Internal Server Error  |
| HttpRequestMethodNotSupportedException |   405 Method Not Allowed   |
|    MethodArgumentNotValidException     |      400 Bad Request       |
| MissingServletRequestParmeterException |      400 Bad Request       |
|   MissingServletRequestPartException   |      400 Bad Request       |
|  NoSuchRequestHandingMethodException   |       404 Not Found        |
|         TypeMismatchException          |      400 Bad Request       |

*以上异常一般会由 Spring 自身抛出，作为 DispatcherServlet 处理过程中或执行校验时出现问题的结果*

###### 抛出异常

内置的映射异常对于应用所抛出的异常无法捕获与抛出。一般使用 @ResponseStatus 注解将异常映射为 HTTP 状态码，即在控制器方法中捕获程序异常，并抛出带有 @ResponseStatus 注解的异常，此时 Servlet 渲染异常时，会将状态码渲染为 @ResponseStatus 属性值

```java
// controller 方法
@GetMapping("/profiles/{id}")
public String profile(@PathVariable int id, Model model) {
	Profile profile = profileRepository.get(id);
	if (profile == null) {
		throw new ProfileNotFoundException();
	}
	model.addAttribute(profile)
}
@ResponseStatus(value = HttpStatus.NOT_FOUND, reason = "Profile Not Found")
public class ProfileNotFoundException extends RuntimeException {}
```

或者在 Controller 方法中声明抛出异常时处理逻辑

```java
@ExceptionHandler(DuplicateSpittleException.class)  // 可以处理同一控制器中所有处理器抛出的异常
public String handlerNotFound() { return "error/duplicate"; }
```

###### 控制器异常通知

Spring 3.2 引入了控制器通知：是任意带有 @ControllerAdvice 注解的类，这个类会包含一个或多个如下类型的方法：

* @ExceptionHandler 注解标注的方法

  用于捕获 Controller 中抛出的异常，用于异常全局处理，此次处理异常优先级最低

* @InitBinder 注解标注的方法

  用于请求中注册自定义参数的解析，实现自定义请求参数格式

* @ModelAttribute 注解标注的方法

  此方法会在执行目标 Controller 方法前执行，即可以在 Controller 中获取该注解标注的属性

  ```java
  @RestControllerAdvice // 等效 @ControllerAdvice + @ResponseBody
  public class GlobalHandler {
      @ModelAttribute("loginUserInfo") 
      public UserDetails modelAttribute() {
          return SecurityContextHolder.getContext().getAuthentication().getPrincipal();
      }
      @InitBinder
      protected void initBinder(WebDataBinder binder) {
          SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
          dateFormat.setLenient(false);
          binder.registerCustomEditor(Date.class, new CustomDateEditor(dateFormat, false));
      }
      // 如果任意的控制器方法抛出了 DuplicateProfileException，都会调用该方法
      @ExceptionHandler(DuplicateProfileException.class)
  	public String duplicateSpittleHandler() {
  		return "error/duplicate";
  	}
  }
  // contro中使用
  @RestController
  public class AppController {
      @PostMapping("/favorite/goods")
      public List<goods> getGoodsByUser(@ModelAttribute("loginUserInfo") UserDetails userDetails，Date start) {
       	return goodsService.getUserByStart(userDetails.getId, start);   
      }
  }
  ```

在带有 @ControllerAdvice 注解的类中，上述 3 个注解标注的方法会运用到整个应用程序所有控制器中带有 @RequestMapping 注解的方法上

##### 视图

###### 视图解析器

Spring MVC 定义了 *ViewResolver* 接口

```java
public interface ViewResolver {
    // 当给 resolveViewName 方法传入一个视图名和 Locale 对象时，它会返回一个 View 实例
    View resolveViewName(String viewName, Locale locale) throws Exception;
}
public interface View {
    String getContentType();
    void render(Map<String, ?> model, HttpServletRequest request, HttpServletResponse response) throws Exception;
}
```

View 接口的任务就是接受模型以及 Servlet 的 HttpServletRequest 和 HttpServletResponse 对象，并将输出结果渲染到 HttpServletResponse 中。Spring 提供了多个内置视图解析器的实现：

*spring 支持的视图解析器*

|            视图解析器            |                             描述                             |
| :------------------------------: | :----------------------------------------------------------: |
|      `BeanNameViewResolver`      | 将视图解析为 Spring 应用上下文中的 bean，其中 bean 的 ID 与视图的名字相同 |
| `ContentNegotiatingViewResolver` | 通过考虑客户端需要的内容类型来解析视图，委托给另外一个能够产生对应内容类型的视图解析器 |
|     `FreeMarkerViewResolver`     |                 将视图解析为 FreeMaker 模板                  |
|  `InternalResourceViewResolver`  |        将视图解析为 Web 应用的内部资源（一般为 JSP）         |
|   `JasperReportsViewResolver`    |               将视图解析为 JasperReports 定义                |
|   `ResourceBundleViewResolver`   |          将视图解析为资源 bundle（一般为属性文件）           |
|       `TilesViewResolver`        | 将视图解析为 Apache Tile，tile ID 与视图名相同，有两个 TileViewResolver 实现，分别对应 Tiles2.0 和 Tiles3.0 |
|      `UrlBasedViewResolver`      | 直接根据视图的名称解析视图，视图的名称会匹配一个物理视图的定义 |
|   `VelocityLayoutViewResolver`   | 将视图解析为 Velocity 布局，从不同的 Velocity 模板中组合页面 |
|      `VelocityViewResolver`      |                  将视图解析为 Velocity 模板                  |
|        `XmlViewResolver`         | 将视图解析为特定 XML 文件中的 bean 定义，类似于 BeanNameViewResolver |
|        `XsltViewResolver`        |                将视图解析为 XSLT 转换后的结果                |

###### JSP 视图

Spring 提供了两种支持 JSP 视图的方式：

* *InternalResourceViewResolver*

  *InternalResourceViewResolver* 会将视图名解析为 JSP 文件。如果在 JSP 页面中使用 JSTL，*InternalResourceViewResolver* 能够将视图名解析为 JstlView 形式的 JSP 文件，从而将 JSTL 本地化和资源 bundle 变量暴露给 JSTL 的格式化（formatting）和信息（message）标签。它遵循一种约定，会在视图名上添加前缀和后缀，进而确定一个 Web 应用中视图资源的物理路径

  ```java
  @Bean
  public ViewResolver viewResolver() {
      InternalResourceViewResolver resolver = new InternalResourceViewResolver();
      resolver.setPrefix("/WEB-INF/views/");
      resolver.setSuffix(".jsp");
      return resolver;
  }
  ```

  使用 XML 的 spring 配置视图解析器

  ```xml
  <bean id="viewResolver" class="org.springframework.web.servlet.view.InternalResourceViewResolver"
        p:perfix="/WEB-INF/views/"
        p:suffix=".jsp" />
  ```

  当逻辑视图名中包含斜线时，这个斜线也会带到资源的路径名中

  如果 JSP 使用 JSTL 标签来处理格式化和信息（JSTL 的格式化标签需要一个 Locale 对象，以便于恰当地格式化地域相关的值，信息标签可以借助 Spring 的信息资源和 Locale，从而选择适当的信息渲染到 HTML 之中。通过解析 JstlView，JSTL 能够获得 Locale 对象以及 Spring 中配置的信息资源）

  将视图解析为 JstlView

  ```java
  @Bean
  public ViewResolver viewResolver() {
  	InternalResourceViewResolver resolver = new InternalResourceViewResolver();
      resolver.setPrefix("/WEB-INF/views/");
      resolver.setSuffix(".jsp");
      resolver.setViewClass(org.springframework.web.servlet.view.JstlView.class);
      return resolver;
  }
  ```

  使用 XML 配置 JstlView

  ```xml
  <bean id="viewResolver"
        class="org.springframework.web.servlet.view.InternalResourceViewResolver"
        p:prefix="/WEB_INF/views/"
        p:suffix=".jsp"
        p:viewClass="org.springframework.web.servlet.view.JstlView" />
  ```

Spring 提供了两个 JSP 标签库，一个用于表单到模型的绑定，另一个提供了通用的工具类特性

* 将表单绑定到模型

  Spring 的表单绑定 JSP 标签库包含了 14 个标签，大多数都用来渲染 HTML 中的表单标签，与原生 HTML 标签的区别在于它们会绑定模型中的一个对象，能够根据模型中对象的属性填充值。标签库中还包含了一个为用户展现错误的标签，它会将错误信息渲染到最终的 HTML 中，使用表单绑定库，需要在 JSP 页面中声明

  ```
  <%@ taglib uri="http://www.springframework.org/tags/form" prefix="sf" %>
  ```

  *Spring 表单模型绑定*

  |      jsp 标签       |                             描述                             |
  | :-----------------: | :----------------------------------------------------------: |
  |   `<sf:checkbox>`   | 渲染成一个 HTML `<input>` 标签，其中 type 属性设置为 checkbox |
  |  `<sf:checkboxes>`  | 渲染成多个 HTML `<input>` 标签，其中 type 属性设置为 checkbox |
  |    `<sf:errors>`    |           在一个 HTML `<span>` 中渲染输入域的错误            |
  |     `<sf:form>`     | 渲染成一个 HTML `<form>` 标签，并为其内部标签暴露绑定路径，用于数据绑定 |
  |    `<sf:hidden>`    |   渲染成一个 HTML `<input>` 标签，其中 type 属性为 hidden    |
  |    `<sf:label>`     |                渲染成一个 HTML `<label>` 标签                |
  |    `<sf:option>`    | 渲染成一个 HTML `<option>` 标签，其 selected 属性根据所绑定的值进行设置 |
  |   `<sf:options>`    | 按照绑定的集合、数组或 Map，渲染成一个 HTML `<option>` 标签的列表 |
  |   `<sf:password>`   |     渲染成一个 HTML `<input>` 标签，type 属性为 password     |
  | `<sf:radiobutton>`  |     渲染一个 HTML `<input>` 标签，type 属性设置为 radio      |
  | `<sf:radiobuttons>` |  渲染成毒功而 HTML `<input>` 标签，其 type 属性设置为 radio  |
  |    `<sf:select>`    |               渲染为一个 HTML `<select>` 标签                |
  |   `<sf:textarea>`   |              渲染为一个 HTML `<textarea>` 标签               |
  |    `<sf:input>`     |   渲染成一个 HTML `<input>` 标签，其 type 属性设置为 text    |

  ```jsp
  <%@ taglib prefix="sf" uri="http://www.springframework.org/tags/form" %>
  <sf:form method="POST" commandName="spitter">   <!-- commandName 属性构建针对某个模型对象的上下文信息 -->
      First Name: <sf:input path="firstName"/><br>
      <sf:errors path="fistName"/><br/>   <!-- 
      Last Name: <label><sf:input path="lastName"/><br>
      Username: <label><sf:input path="username"/></label><br>
      Password: <sf:password path="password"/><br>
      Email: <label><sf:input type="email" path="email"/></label><br>
      <input type="submit" value="Register">
  </sf:form>
  ```

* Spring 通用的标签库

  ```xml
  <%@ taglib uri="http://www.springframework.org/tags" prefix="s" %>
  ```

  *通用标签库*

  |        标签         |                             描述                             |
  | :-----------------: | :----------------------------------------------------------: |
  |     `<s:bind>`      | 将绑定属性的状态导出到一个名为 status 的页面作用域属性中，与 `<s:path>` 组合使用获取绑定属性的值 |
  |  `<s:escapeBody>`   |             将标签体中的内容进行 HTML 或 Js 转义             |
  | `<s:hasBindErrors>` | 指定模型对象（在请求属性中）是否有绑定错误，有条件地渲染内容 |
  |  `<s:htmlEscape>`   |               为当前页面设置默认的 HTML 转义值               |
  |    `<s:message>`    | 根据给定的编码获取信息，然后要么进行渲染（默认行为），要么将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过 var 和 scope 属性实现） |
  |  `<s:nestedPath>`   |            设置嵌入式 path，用于 `<s:bind>` 之中             |
  |     `<s:theme>`     | 根据给定的编码获取主题信息，然后要么进行渲染（默认行为），要么将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过使用 var 和 scope 属性实现） |
  |   `<s:transform>`   |      使用命令对象的属性编辑器转换命令对象中不包含的属性      |
  |      `<s:url>`      | 创建相对于上下文的 URL，支持 URI 模板变量以及 HTML/XML/JS 转义，可以渲染 URL（默认行为），也可以将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过 var 和 scope 属性实现）<br />它是 JSTL 中 `<c:url>` 标签替代者。`<s:url>` 会接受一个相对于 Servlet 上下文的 URL，并在渲染的时候预先添加上 Servlet 上下文路径 |
  |     `<s:eval>`      | 计算符合 Spring 表达式语言语法的某个表达式的值，然后要么进行渲染，要么将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过 var 和 scope 属性实现） |

  信息源

  ```jsp
  <h1>
      <s:message code="spittr.welcome" /> <!-- 将会根据 key 为 spittr.welcome 的信息源来渲染文本，使用text属性指定默认值 -->
  </h1>
  ```

  Spring 有多个信息源的类，都实现了 *MessageSource* 接口，在这些类中，常见的是 *ResourceBundleMessageSource*，它会从一个属性文件中加载信息，这个属性文件的名称是根据基础名称衍生而来

  ```java
  @Bean
  public MessageSource messageSource() {
      ResourceBundleMessageSource messageSource = new ResourceBundleMessageSource();
      // basename 设置为 messages 后，ResourceBundleMessageSource 会在根路径的属性文件 messages.properties 中解析信息
      messageSource.setBasename("messages");
      return messageSource;
  }
  ```

  使用 *ReloadableResourceBundleMessageSource* 能够重新加载信息属性，而不必重新编译或重启应用

  ```java
  @Bean
  public MessageSource messageSource() {
      ReloadableResourceBundleMessageSource messageSource = new ReloadableResourceBundleMessageSource();
      // 设置为在应用的外部查找
      messageSource.setBasename("file:///etc/spittr/message");
      messageSource.setCacheSeconds(10);
      return messageSource;
  }
  ```

  basename 可以设置为类路径下（以 `classpath:` 为前缀）文件系统（以`file:` 为前缀）或Web应用的根路径下（没有前缀）查找属性。指定特定地区信息源，创建对应的地区缩写属性文件  *messages_zh_CN.properties*。

  URL

  ```html
  <!-- 接受一个相对于 Servlet 上下文的 URL，在渲染时预先添加 Servlet 上下文路径 -->
  <a href="<S:url href="/spitter/register" />">Register</a>
  <!-- 使用 <s:url> 创建 URL，并将其赋给一个变量供模板使用,在 js 代码中使用 URL，将 javaScriptEscape 属性设为 true -->
  <s:url href="/spitter/register" var="registerUrl" javaScriptEscape="true"/>
  <a href="${registerUrl}">Register</a>
  <!-- 在 URL 上添加参数 -->
  <s:url href="/spittles" var="spittlesUrl">
  	<s:param name="max" value="20"/>
      <s:param name="count" value="2"/>
  </s:url>
  <!-- 创建嗲有路径参数的 URL -->
  <s:url href="/spitter/{username}" var="spitterUrl">
  	<!-- 当href属性中占位符匹配param参数时，这个参数会插入到占位符的位置。如果参数无法匹配href中的任何占位符，那么这个参数将会作为查询参数 -->
  	<s:param name="username" value="jbauer"/>
  </s:url>
  ```

###### 配置 Tiles 视图解析器

为了在 Spring 中使用 Tiles，需要配置几个 bean：`TilesConfigurer` 它负责定位和加载 Tile 定义并协调生成 Tiles；`TilesViewResovler` 将逻辑视图名解析为 Tile 定义

```java
@Bean
public TilesConfigurer tilesConfigurer() {
    TilesConfigurer tiles = new TilesConfigurer();
    tiles.setDefinitions("/WEB-INF/layout/tiles.xml", "/WEB-INF/views/**/tiles.xml");
    tiles.setCheckRefresh(true);
    return tiles;
}
@Bean
public ViewResolver viewResolver() {
    return new TilesViewResolver();
}
```

使用 XML 定义

```xml
<bean id="tilesConfigurer" class="org.springframework.web.servlet.view.tiles3.TilesConfigurer">
	<property name="definitions">
		<list>
			<value>/WEB-INF/layout/tiles.xml</value>
			<value>/WEB-INF/views/**/tiles.xml</value>
        </list>
    </property>
</bean>
<bean id="viewrResolver" class="org.springframework.web.servlet.view.tiles3.TilesViewResolver"/>
```

###### Thymeleaf 视图解析器

Thymeleaf 模板是原生的，不依赖于标签库。它能在接受原始 HTML 的地方进行编辑和渲染。它没有与 Servlet 规范耦合。

为了要在 Spring 中使用 Thymeleaf，需要配置三个启用 Thymeleaf 与 Spring 集成的 bean：

|          bean           |                             作用                             |
| :---------------------: | :----------------------------------------------------------: |
| `ThymeleafViewResolver` | Thymeleaf 视图解析器（将逻辑视图名称解析为 Thymeleaf 模板视图） |
| `SpringTemplateEngine`  |                模板引擎（处理模板并渲染结果）                |
|   `TemplateResolver`    |           模板解析器（定位与查找 Thymeleaf 模板）            |

使用注解配置

```java
// Thymeleaf 视图解析器
@Bean
public ViewResolver viewResolver(SpringTemplateEngine templateEngine) {
    ThymeleafViewResolver viewResolver = new ThymeleafViewResolver();
    viewResolver.setTemplateEngine(templateEngine);
    return viewResolver;
}

// 模板引擎
@Bean
public TemplateEngine templateEngine(TemplateResolver templateResolver) {
    SpringTemplateEngine templateEngine = new SpringTemplateEngine();
    templateEngine.setTemplateResolver(templateResolver);
    return templateEngine;
}

// 模板解析器
@Bean
public TemplateResolver templateResolver() {
    TemplateResolver templateResolver = new ServletContextTemplateResolver();
    templateResolver.setPrefix("/WEB-INF/templates/");
    templateResolver.setSuffix(".html");
    templateResolver.setTemplateMode("HTML5");
    return templateResolver;
}
```

使用 XML 配置

```xml
<bean id="viewResolver" class="org.thymeleaf.spring3.view.ThymeleafViewResolver"
      p:tempateEngine-ref="templateEngine" />
<bean id="templateEngine" class="org.thymeleaf.spring3.SpringTemplateEngine"
      p:templateResolver-ref="templateResolver" />
<bean id="templateResolver" class="org.thymeleaf.templateresolver.ServletContextTemplateResolver"
      p:prefix="/WEB-INF/templates/"
      p:suffix=".html"
      p:templateMods="HTML5" />
```

`ThymeleafViewResolver` 是 SpringMVC 中 ViewResolver 的一个实现类。它接受一个逻辑视图名称，并将其解析为视图（一个 Thymeleaf 模板）。ThymeleafViewResolver bean 中注入了一个对 SpringTemplateEngine bean 的引用。SpringTemplateEngine 会在 Spring 中启用 Thymeleaf 引擎，用来解析模板，并基于这些模板渲染结果。

*thymeleaf 常用标签*

thymeleaf 很多属性对应标准的 HTML 属性，并具有相同的名称，但是会渲染一些计算后得到的值。

| thymeleaf 属性 |                             作用                             |                           用法                           |                             含义                             |
| :------------: | :----------------------------------------------------------: | :------------------------------------------------------: | :----------------------------------------------------------: |
|    th:href     | 类似 href 属性，可包含 Thymeleaf 表达式，会渲染成一个标准的 href 属性 |                     `@{/js/code.js}`                     |                      计算相对 URL 路径                       |
|    th:class    |                      渲染为 class 属性                       | `th:class="${#fields.hasErrors('firstName')} ? 'error'"` | 如果 first Name 域存在错误，渲染为 error，没有错误则不渲染 class 属性 |
|    th:field    |                          后端域属性                          |            `<input th:field="*{firstName}"/>`            | 将 value 属性设置为 firstName 的值，将 name 的属性设置为 firstName |
|    th:each     |                             迭代                             |        `<th:each="err: ${#fields.errors('*')}">`         |                                                              |
|     th:if      |                             判断                             |           `th:if="${#fields.hasErrors('*')}">`           |                  有错误则渲染，没有则不渲染                  |
|    th:text     |                        计算某个表达式                        |                                                          |                                                              |
|      ${}       |                          变量表达式                          | 使用 spring 时是 SpELl 表达式，基于整个 SpELl 上下文计算 |                                                              |
|      *{}       |                          选择表达式                          |                   基于某个选中对象计算                   |                                                              |
|   th:object    |                           设置对象                           |       `<form method="post" th:object="{spitter}">`       |                   将对象绑定到 form 作用域                   |

