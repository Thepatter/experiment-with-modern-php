### Spring MVC

#### 配置

##### 注解

###### *DIspatchServlet* 

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
}
```

容器配置 *DispatcherServlet* 流程

1.  在 Servlet 3.0 环境中，容器会在类路径中查找实现 *java.servlet.ServletContainerInitializer* 接口的类，用它来配置 Servlet 容器。
2.  Spring 提供了这个接口的实现 *SpringServletContainerInitializer*，*SpringServletContainerInitializer* 会查找实现 *WebApplicationInitializer* 类并将配置的任务交给它们来完成
3.  Spring 3.2 增加了 *WebApplicationInitializer* 基础实现 *AbstractAnnotationConfigDispatcherServletInitializer*
4.  部署到 Servlet 3.0 容器时，容器会自动发现扩展 *AbstractAnnotationConfigDispatcherServletInitializer* 的类，并用它来配置 Servlet 上下文

上下文加载流程

1.  当 *DispatcherServlet* 启动时，会创建 Spring 应用上下文，并加载配置文件或配置类中所声明的 Bean
2.  spring 会使用 *ContextLoaderLister* 加载应用中的其他 bean（中间层和数据层组件）
3.  *AbstractAnnotationConfigDispatcherServletInitializer* 会同时创建 *DispatcherServlet* 和 *ContextLoaderListener*

###### 启用 SpringMvc

使用 @EnableWebMvc 注解的配置类来启动 Spring MVC

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

###### 配置 *ContextLoaderLister* 

```java
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.ComponentScan.Filter;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.FilterType;
@Configuration
@ComponentScan(basePackages={"apps"}, excludeFilters = {@Filter(type=FilterType.ANNOTATION,value=EnableWebMvc.class)})
public class RootConfig{}
```

#### 使用

##### Controller

###### 请求解析流程

1.  请求会先到达 Spring 的 *DispatcherServlet*，Spring MVC 所有请求都会通过前端控制器  *DispatcherServlet*，单实例的 *DispatcherServlet* 将请求委托给应用程序的其他组件来执行实际处理

2.  *DispatcherServlet* 查询一个或多个处理器映射，来确定请求的下一站，处理器映射会根据请求所携带的 URL 信息来进行决策

3.  *DispatcherServlet* 会将请求发送给选中的控制器，到了控制器，请求会卸下其负载并等待处理器处理这些信息
4.  控制器在完成逻辑处理后，通常会产生一些信息（模型 model），这些信息需要返回给用户并在浏览器上显示，这些信息需要以用户友好的方式进行格式化，一般会是 HTML，信息需要发送给一个视图（view），通常会是 JSP。
5.  控制器将模型数据打包，并且标示出用于渲染输出的视图名。将请求模型视图名（将会用来查找产生结果的真正视图）发送回 *DispatcherServlet*

6.  *DispatcherServlet* 使用视图解析器 viewResolver 将逻辑视图名匹配为一个特定的视图实现

7.  交付模型数据，视图将使用模型数据渲染输出，输出会通过响应对象传递给客户端

可以使用额外的配置来自定义 DispatcherServlet：

* `customizeRegistration()`

  在 `AbstractAnnotationConfigDispatcherServletInitializer` 将 `DispatcherServlet` 注册到 Servlet 容器后，就会调用 `cusomizeRegistration()`，并将 Servlet 注册后得到的 `Registration.Dynamic` 传递进来。

  可以使用 `Registration` 的 `setLoadOnStartup()` 设置 `load-on-startup` 优先级，通过 `setInitParameter()` 设置初始化参数，通过 `setMultipartConfig()` 配置 Servlet3.0 对 `multipart` 的支持

###### 请求静态页面

*Controller*

```JAVA
@Controller
public class IndexController {
	@GetMapping("/")
	public String home() {
        return "index";
    }
}
```

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
        MockMvc mockMvc = MockMvcBuilders.standaloneSetup(indexController).build();
        mockMvc.perform(MockMvcRequestBuilders.get("/"))
                .andExpect(MockMvcResultMatchers.view().name("home"));
    }
}
```

###### 传递模型数据到视图

```

```





###### controller 响应

* 响应视图及传参

    ```java
    @RequestMapping(value = "/{spittleId}", method = RequestMethod.GET)
    public String showSpittle(@PathVariable int spittleId, Model model) {
        model.addAttribute(spittleRepository.findOne(spittleId));
        return "spittle";
    }
    ```

    返回 spring 对应视图，`redirect:` 前缀为重定向规则，`forward:` 前缀为请求该 url

##### 添加其他的 Servlet 和 Filter

按照 `AbstractAnnotationConfigDisoatcherServletInitializer` 定义，它会创建 `DispatcherServlet` 和 `ContextLoaderListener` 基于 Java 的初始化器可以定义任意数量的初始化器类。如果想往 Web 容器中注册其他组件，只需创建一个新的初始化器就可以（实现 Spring 的 WebApplicationInitializer 接口）

```java
public class MyServletInitializer implements WebApplicationInitializer {
    /** 注册servlet **/
    @Override
    public void onStartup(ServletContext servletContext) throw ServletException {
        Dynamic myServlet = servletContext.addServlet("myServlet", MyServlet.class);
        myServlet.addMapping("/custom/***");
        /** 注册 Filter **/
        javax.servlet.FilterRegistration.Dynamic filter = servletContext
    }
    
}
```

##### 编写测试

###### controller 测试返回视图测试

为 spring 的 controller 编写测试，测试返回对应视图

* 测试 post 表单提交

  ```java
  @Test
      public void shouldProcessRegistration() throws Exception {
          SpitterRepository mockRepository = mock(SpitterRepository.class);
          Spitter unsaved = new Spitter("jbauer", "24hours", "Jack", "Bauer", "email@qq.com");
          Spitter saved = new Spitter("jbauer", "24hours", "Jack", "Bauer", "email@qq.com");
          when(mockRepository.save(unsaved)).thenReturn(saved);
          SpitterController controller = new SpitterController(mockRepository);
          MockMvc mockMvc = standaloneSetup(controller).build();
          mockMvc.perform(post("/spitter/register")
                  .param("firstName", "Jack")
                  .param("lastName", "Bauer")
                  .param("username", "jbauer")
                  .param("password", "24hours")
          ).andExpect(redirectedUrl("/spitter/jbauer"));
          verify(mockRepository, atLeastOnce()).save(unsaved);
      }
  ```

* 测试 get 请求响应 list

  ```java
  @Test
  public void shouldShowPageSpittles() throws Exception {
      List<Spittle> expectedSpittles = createSpittleList(50);
      SpittleRepository mockRepository = mock(SpittleRepository.class);
      when(mockRepository.findSpittles(238900, 50)).thenReturn(expectedSpittles);
      SpittleController controller = new SpittleController(mockRepository);
      MockMvc mockMvc = standaloneSetup(controller)
          .setSingleView(new InternalResourceView("/WEB-INF/views/spittles.jsp"))
          .build();
      mockMvc.perform(get("/spittles?max=238900&count=50"))
          .andExpect(view().name("spittles"))
          .andExpect(model().attributeExists("spittleList"))
          .andExpect(model().attribute("spittleList", hasItems(expectedSpittles.toArray())));
  }
  ```



### Web 视图

#### 视图解析流程

在 Spring 中编写的控制器方法都没有直接产生浏览器中渲染的 HTML，这些方法只是将一些数据填充到模型中，然后将模型传递给一个用来渲染的视图。这些方法会返回一个 String 类型的值，这个值是视图的逻辑名称，不会直接引用具体的视图实现。

将控制器中请求处理的逻辑和视图中的渲染实现解耦是 Spring MVC 的一个重要特性。如果控制器中的方法直接负责产生 HTML 的话，就很难在不影响请求处理逻辑的前提下，维护和更新视图。控制器方法和视图的实现会在模型内容上达成一致。Spring MVC 定义了一个名为 ViewResolver 的接口

```java
public interface ViewResolver {
    View resolveViewName(String viewName, Locale locale) throws Exception;
}
```

当给 `resolveViewName` 方法传入一个视图名和 Locale 对象时，它会返回一个 View 实例。

```java
public interface View {
    String getContentType();
    void render(Map<String, ?> model, HttpServletRequest request, HttpServletResponse response) throws Exception;
}
```

View 接口的任务就是接受模型以及 Servlet 的 request 和 response 对象，并将输出结果渲染到 response 中。Spring 提供了多个内置视图解析器的实现：

* `BeanNameViewResolver`

  将视图解析为 Spring 应用上下文中的 bean，其中 bean 的 ID 与视图的名字相同

* `ContentNegotiatingViewResolver`

  通过考虑客户端需要的内容类型来解析视图，委托给另外一个能够产生对应内容类型的视图解析器

* `FreeMarkerViewResolver`

  将视图解析为 FreeMaker 模板

* `InternalResourceViewResolver`

  将视图解析为 Web 应用的内部资源（一般为 JSP）

* `JasperReportsViewResolver`

  将视图解析为 JasperReports 定义

* `ResourceBundleViewResolver`

  将视图解析为资源 bundle

* `TilesViewResolver`

  将视图解析为 Apache Tile 定义，其中 tile ID 与视图名称相同，有两个不同的 TileViewResolver 实现，分别对应 Tiles2.0 和 Tiles3.0

* `UrlBasedViewResolver`

  直接根据视图的名称解析视图，视图的名称会匹配一个物理视图的定义

* `VelocityLayoutViewResolver`

  将视图解析为 Velocity 布局，从不同的 Velocity 模板中组合页面

* `VelocityViewResolver`

  将视图解析为 Velocity 模板

* `XmlViewResolver`

  将视图解析为特定 XML 文件中的 bean 定义，类似于 BeanNameViewResolver

* `XsltViewResolver`

  将视图解析为 XSLT 转换后的结果

#### JSP 视图

Spring 提供了两种支持 JSP 视图的方式：

##### InternalResourceViewResolver

`InternalResourceViewResolver` 会将视图名解析为 JSP 文件。如果在 JSP 页面中使用 JSTL，InternalResourceViewResolver 能够将视图名解析为 JstlView 形式的 JSP 文件，从而将 JSTL 本地化和资源 bundle 变量暴露给 JSTL 的格式化（formatting）和信息（message）标签。它遵循一种约定，会在视图名上添加前缀和后缀，进而确定一个 Web 应用中视图资源的物理路径。

使用 `@Bean` 注解配置

```java
@Bean
public ViewResolver viewResolver() {
    InternalResourceViewResolver resolver = new InternalResourceViewResolver();
    resolver.setPrefix("/WEB-INF/views/");
    resolver.setSuffix(".jsp");
    return resolver;
}
```

使用 XML 的 spring 配置

```xml
<bean id="viewResolver" class="org.springframework.web.servlet.view.InternalResourceViewResolver"
      p:perfix="/WEB-INF/views/"
      p:suffix=".jsp" />
```

当逻辑视图名中包含斜线时，这个斜线也会带到资源的路径名中

JSTL 的格式化标签需要一个 Locale 对象，以便于恰当地格式化地域相关的值，信息标签可以借助 Spring 的信息资源和 Locale，从而选择适当的信息渲染到 HTML 之中。通过解析 JstlView，JSTL 能够获得 Locale 对象以及 Spring 中配置的信息资源。

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

##### spring 提供的 JSP 标签库

Spring 提供了两个 JSP 标签库，一个用于表单到模型的绑定，另一个提供了通用的工具类特性

将表单绑定到模型

Spring 的表单绑定 JSP 标签库包含了 14 个标签，大多数都用来渲染 HTML 中的表单标签，与原生 HTML 标签的区别在于它们会绑定模型中的一个对象，能够根据模型中对象的属性填充值。标签库中还包含了一个为用户展现错误的标签，它会将错误信息渲染到最终的 HTML 中，使用表单绑定库，需要在 JSP 页面中声明

```jsp
<%@ taglib uri="http://www.springframework.org/tags/form" prefix="sf" %>
```

* `<sf:checkbox>`

  渲染成一个 HTML `<input>` 标签，其中 type 属性设置为 checkbox

* `<sf:checkboxes>`

  渲染成多个 HTML `<input>` 标签，其中 type 属性设置为 checkbox

* `<sf:errors>`

  在一个 HTML `<span>` 中渲染输入域的错误

* `<sf:form>`

  渲染成一个 HTML `<form>` 标签，并为其内部标签暴露绑定路径，用于数据绑定

* `<sf:hidden>`

  渲染成一个 HTML `<input>` 标签，其中 type 属性为 hidden

* `<sf:label>`

  渲染成一个 HTML `<label>` 标签

* `<sf:option>`

  渲染成一个 HTML `<option>` 标签，其 selected 属性根据所绑定的值进行设置

* `<sf:options>`

  按照绑定的集合、数组或 Map，渲染成一个 HTML `<option>` 标签的列表

* `<sf:password>`

  渲染成一个 HTML `<input>` 标签，type 属性为 password

* `<sf:radiobutton>`

  渲染一个 HTML `<input>` 标签，type 属性设置为 radio

* `<sf:radiobuttons>`

  渲染成毒功而 HTML `<input>` 标签，其 type 属性设置为 radio

* `<sf:select>`

  渲染为一个 HTML `<select>` 标签

* `<sf:textarea>`

  渲染为一个 HTML `<textarea>` 标签

```jsp
<%@ taglib prefix="sf" uri="http://www.springframework.org/tags/form" %>
<sf:form method="POST" commandName="spitter">
    First Name: <sf:input path="firstName"/><br>
    Last Name: <label><sf:input path="lastName"/><br>
    Username: <label><sf:input path="username"/></label><br>
    Password: <sf:password path="password"/><br>
    Email: <label><sf:input type="email" path="email"/></label><br>
    <input type="submit" value="Register">
</sf:form>
```

Spring 通用的标签库

```jsp
<%@ taglib uri="http://www.springframework.org/tags" prefix="s" %>
```

* `<s:bind>`

  将绑定属性的状态导出到一个名为 status 的页面作用域属性中，与 `<s:path>` 组合使用获取绑定属性的值

* `<s:escapeBody>`

  将标签体中的内容进行 HTML 或 Js 转义

* `<s:hasBindErrors>`

  指定模型对象（在请求属性中）是否有绑定错误，有条件地渲染内容

* `<s:htmlEscape>`

  为当前页面设置默认的 HTML 转义值

* `<s:message>`

  根据给定的编码获取信息，然后要么进行渲染（默认行为），要么将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过 var 和 scope 属性实现）

  ```jsp
  <h1>
      <s:message code="spittr.welcome" />
  </h1>
  ```

  `<s:message>` 将会根据 key 为 `spittr.welcome` 的信息源来渲染文本。Spring 有多个信息源的类，都实现了 `MessageSource` 接口，在这些类中，常见的是 `ResourceBundleMessageSource`，它会从一个属性文件中加载信息，这个属性文件的名称是根据基础名称衍生而来。如 `@Bean` 配置 `ResourceBundleMessageSource`

  ```java
  @Bean
  public MessageSource messageSource() {
      ResourceBundleMessageSource messageSource = new ResourceBundleMessageSource();
      messageSource.setBasename("messages");
      return messageSource;
  }
  ```

  将其设置 messages 后，ResourceBundleMessageSource 就会试图在根类路径的属性文件`messages.properties`中解析信息。

  使用 `ReloadableResourceBundleMessageSource` 能够重新加载信息属性，而不必重新编译或重启应用

  ```java
  @Bean
  public MessageSource messageSource() {
      ReloadableResourceBundleMessageSource messageSource = new ReloadableResourceBundleMessageSource();
      messageSource.setBasename("file:///etc/spittr/message");
      messageSource.setCacheSeconds(10);
      return messageSource;
  }
  ```

  basename 可以设置为类路径下 `classpath:`、文件系统`file:`或Web应用的根路径下（没有前缀）查找属性。指定地区 `messages_zh_CN.properties`。可使用 `text` 属性指定默认值

* `<s:nestedPath>`

  设置嵌入式 path，用于 `<s:bind>` 之中

* `<s:theme>`

  根据给定的编码获取主题信息，然后要么进行渲染（默认行为），要么将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过使用 var 和 scope 属性实现）

* `<s:transform>`

  使用命令对象的属性编辑器转换命令对象中不包含的属性

* `<s:url>`

  创建相对于上下文的 URL，支持 URI 模板变量以及 HTML/XML/JS 转义，可以渲染 URL（默认行为），也可以将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过 var 和 scope 属性实现）

  它是 JSTL 中 `<c:url>` 标签替代者。`<s:url>` 会接受一个相对于 Servlet 上下文的 URL，并在渲染的时候预先添加上 Servlet 上下文路径

* `<s:eval>`

  计算负荷 Spring 表达式语言语法的某个表达式的值，然后要么进行渲染，要么将其设置为页面作用域，请求作用域，会话作用域或应用作用域的变量（通过 var 和 scope 属性实现）

#### Apache Tiles 布局引擎

##### 配置 Tiles 视图解析器

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

#### Thymeleaf 视图解析器

Thymeleaf 模板是原生的，不依赖于标签库。它能在接受原始 HTML 的地方进行编辑和渲染。它没有与 Servlet 规范耦合。

##### 配置 Thymeleaf

为了要在 Spring 中使用 Thymeleaf，需要配置三个启用 Thymeleaf 与 Spring 集成的 bean：

* `ThymeleafViewResolver`

  将逻辑视图名称解析为 Thymeleaf 模板视图

* `SpringTemplateEngine`

  处理模板并渲染结果

* `TemplateResolver`

  加载 Thymeleaf 模板

使用 Java 配置

```java
@Bean
public ViewResolver viewResolver(SpringTemplateEngine templateEngine) {
    ThymeleafViewResolver viewResolver = new ThymeleafViewResolver();
    viewResolver.setTemplateEngine(templateEngine);
    return viewResolver;
}

@Bean
public TemplateEngine templateEngine(TemplateResolver templateResolver) {
    SpringTemplateEngine templateEngine = new SpringTemplateEngine();
    templateEngine.setTemplateResolver(templateResolver);
    return templateEngine;
}

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

