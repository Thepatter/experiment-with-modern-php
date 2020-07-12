### Security

#### Spring Security

基于 Spring 的应用提供声明式安全保护的安全性框架，能够在 Web 请求级别和方法调用级别处理身份认证和授权。

##### 过滤 Web 请求

使用 Servlet Filter 提供各种安全性功能。*DelegatingFilterProxy*（把 Filter 的处理逻辑委托给 Spring 应用上下文中所定义的一个代理 Filter ） 是一个特殊的 ServletFilter，它会将工作委托给一个 javax.servlet.Filter 实现类，这个实现类作为一个 Bean 注册在 Spring 应用上下文中。

```xml
<filter>
	<filter-name>springSecurityFilterChain</filter-name>
    <filter-class>
    	org.springframework.web.filter.DelegatingFilterProxy
    </filter-class>
</filter>
```

或继承 AbstractSecurityWebApplicationInitializer 来声明 DelegatingFilterProxy

###### web security

* 使用 @EnableWebSecurity 启用，必须配置在一个实现了 WebSecurityConfigurer 或继承 WebSecurityConfigurerAdapter（重载其 configure 来配置 Filter 链、拦截器、user-detail）的 Bean 中，该注解可以启用任意 Web 应用的安全性功能

  ```java
  @EnableWebSecurity
  public class SecurityConfig extends WebSecurityConfigurerAdapter {
      @Override
      protected void configure(HttpSecurity http) throws Exception {} // 重新 http 拦截器
      @Override
      protected void configure(AuthenticationManagerBuilder auth) throws Exception {} // 重新用户认证
      
  }
  ```

* 取消自动配置，在入口类中去除 security 的自动配置

  ```java
  @SpringBootApplication(exclude = {
      org.springframework.boot.autoconfigure.security.servlet.SecurityAutoConfiguration.class
  })
  public class WebApplication {
      public static void main(String[] args) {
          SpringApplication.run(WebApplication.class, args);
      }
  }
  ```

##### 用户配置

默认会产生一个 basic 保护，初始用户为 user，密码为随机生成在日志中。W

Spring Security 通过覆盖 *WebSecurityConfigurerAdapter* 基础配置类中定义的 configure() 方法来进行配置，支持以下方法定义：

*配置用户详细信息方法*

|                     方法                     |            描述            |
| :------------------------------------------: | :------------------------: |
|           accountExpired(boolean)            |    定义账号是否已经过期    |
|            accountLocked(boolean)            |    定义账号是否已经锁定    |
|                    and()                     |          连接配置          |
|         authorites(GrantedAuthority)         | 授予某个用户一项或多项权限 |
| authorites(List<? extends GrantedAuthority>) | 授予某个用户一项或多项权限 |
|            authorites(String...)             | 授予某个用户一项或多项权限 |
|         credentialsExpired(boolean)          |    定义凭证是否已经过期    |
|              disabled(boolean)               |    定义账号是否已被禁用    |
|               password(String)               |       定义用户的密码       |
|               roles(String...)               | 授予某个用户一项或多项角色 |
|       passwordEncoder(PasswordEncoder)       |       定义密码编码器       |

passwordEncoder() 接受 Spring Security 中 PasswordEncoder 接口实现，Spring Security 自带以下实现：

*自带实现*

|  PasswordEncoder 实现  |          含义          |
| :--------------------: | :--------------------: |
| BCryptPasswordEncoder  | 使用 bcrypt 强哈希加密 |
|  NoOpPasswordEncoder   |     不进行任何转码     |
| Pbkdf2PasswordEncoder  |    使用 PBKDF2 加密    |
| SCryptPasswordEncoder  |  使用 scrypt 哈希加密  |
| StanardPasswordEncoder | 使用 SHA-256 哈希加密  |

###### 内存用户

```java
@Override
protected void configure(AuthenticationManagerBuilder auth) throws Exception {
    auth.inMemoryAuthentication()
        .passwordEncoder(new BCryptPasswordEncoder())
        .withUser("admin")
        .password(new BCryptPasswordEncoder().encode("secret"))
		// 是 authorites() 方法简写，所给定的值都会添加一个 ROLE_ 前缀，等价 authorities("ROLE_USER")
        .roles("USER")  
        .and()
        .withUser("user")
        .password(new BCryptPasswordEncoder().encode("123456"))
        .roles("USER", "MANAGER");
}
```

###### 数据库

```java
@Autowrid private DataSource dataSource;
// 定义数据源
protected void configure(AuthenticationManagerBuilder auth) throws Exception {
    auht.jdbcAuthentication()
        .dataSource(dataSource)
        // 替换查询时，所有查询都使用用户名作为唯一参数
        .usersByUsernameQuery("select username,password,true from Spitter where username = ?")
        .authoritiesByUsernameQuery("select username, 'ROLE_USER' from Spitter where username = ?")
        // 可接受 PasswordEncoder 接口任意实现
        .passwordEncoder(new BCryptPasswordEncoder().encode("secret"));
}
```

###### 自定义

1. 指定实体实现 Spring Security 的 UserDetailsService 接口

2. 实现 UserDetailsService 接口的 UserDetails loadUserByUsername(String username) throws UsernameNotFoundException 方法

3. 覆写配置类的 configure 方法

   ```
   protected void configure(AuthenticationManagerBuilder auth) throws Exception {
   	auth.userDetailsService(new MyUserService());
   }
   ```

##### Web 拦截

###### url 路径请求保护

*保护路径的配置方法*

|            方法            |                             用途                             |
| :------------------------: | :----------------------------------------------------------: |
|       access(String)       |      如果给定的 SpELl 表达式计算结果为 true，就允许访问      |
|        anonymous()         |                       允许匿名用户访问                       |
|      authenticated()       |                     允许认证过的用户访问                     |
|         denyAll()          |                      无条件拒绝所有访问                      |
|    fullyAuthenticated()    | 如果是完整认证（不是通过 Remember-me 功能认证的），就允许访问 |
| hasAnyAuthority(String...) |        如果用户具有给定权限中的某一个的话，就允许访问        |
|   hasAnyRole(String...)    |          如果用户具备给定角色的某一个话，就允许访问          |
|    hasAuthority(String)    |             如果用户具备给定权限的话，就允许访问             |
|    hasIpAddress(String)    |             如果请求来自给定 IP 地址，就允许访问             |
|      hasRole(String)       |                 如果具备给定角色，就允许访问                 |
|           not()            |                   对其他访问方法的结果求反                   |
|        permitAll()         |                        无条件允许访问                        |
|        rememberMe()        |        如果用户是通过 Remeber-me 功能认证，就允许访问        |

通过重载 configure(HttpSecurity) 方法来实现请求拦截，调用 HttpSecurity.authorizeRequests()，然后调用该方法所返回的对象的方法来配置请求级别的安全性细节，antMatchers() 方法中设定的路径支持 Ant 风格的通配符。这些规则会按照给定的顺序发挥作用。将具体的请求路径放在前面，而不具体的路径放在最后面，如果不具体路径放前面会覆盖掉具体的路径配置。

```java
// 保护 web 请求
@Override
protected void configure(HttpSecurity http) throws Exception {
    http.authorizeRequests()
        .antMatchers("/design", "/orders") // 需要验证，支持 ant 风格匹配，regexMatchers() 支持正则
        .hasRole("USER")
        .anyRequest().permitAll();
}
```

###### 指定请求通道

HttpSecurity 对象的 requiresChannel 方法可以声明 url 模式所要求的通道（requiresSecure() 需要 https，requiresInsecure() 不需要 https）

```java
@Override protected void configure(HttpSecurity http) throws Exception {
	http.authorizeRequests()
		.antMatchers("/spitter/me").hasRole("SPITTER")
        .antMatchers(HttpMethod.POST, "/spittles").hasRole("SPITTER")
        .anyRequest().permitAll()
        .and()
        .requiresChannel()
        .antMatchers("/spitter/form").requiresSecure() // 需要 Https
}
```

###### 跨站请求伪造

Spring Security 3.2 开始，默认会启用 CSRF 防护，Spring Security 通过一个同步 token 的方式来实现 CSRF 防护的功能，它将会拦截状态变化的请求（非 GET 、HEAD、OPTIONS、TRACE）并检查 CSRF 的 token，如果请求中不包含 CSRF token 或 token 不能与服务器端的 token 相匹配，请求将会失败，并抛出 CsrfException 异常。

所有的表单必须在一个 _csrf 域中提交 token，而且这个 token 必须要与服务器端计算并存储的 token 一致。

* 使用 thymeleaf  只有 form 标签的 action 属性添加了 thymeleaf 命名空间前缀（th:action）会自动渲染一个 _csrf 的隐藏域，或显式添加

  ```html
  <input type="hidden" name="_csrf" th:value="${_csrf.token}"/>
  ```

* 使用 jsp 

  ```html
  <input type="hidden" name="${_csrf.parameterName}" value="${_csrf.token}"/>
  ```

* 使用 Spring 的表单绑定标签，sf:form 标签会自动添加隐藏的 csrf token 标签

取消 csrf 防护

```java
@Override protected void configure(HttpSecurity http) throws Exception {
	http...and().csrf().disable();
}
```

##### 认证用户

###### 默认登录页

如果使用默认的 Spring Security 配置在不重写 configure(HttpSecurity) 方法前提下，会得到一个简单的登录页面。重写可以使用 formLogin() 方法重新获取该登录页面

```java
@Override
protected void configure(HttpSecurity http) throws Exception {
    http.formLogin()
        .and()
        .authorizeRequests()
        .antMatchers("/admin").hasRole("USER")
        .anyRequest().permitAll();
}
```

###### 自定义登录页

通过在 formLogin() 中指定 loginPage 来实现自定义登录页，默认 SpringSecurity 会在 /login 路径监听请求并且预期用户名和密码输入域的名称为 username 和 password

```java
@Override
protected void configure(HttpSecurity http) throws Exception {
    http.formLogin()
        .and()
        .formLogin()
		.loginPage("/login") // 登录页面，需在 controller 中配置 @GetMapping("/login")
		.loginProcessingUrl("/authenticate")  // 登录请求请求处理
		.usernameParameter("user")  // 配置用户名密码域
		.passwordParameter("pwd")
		.defaultSuccessUrl("/desigin");  // 配置默认成功页，defaultSuccessUrl 方法传递第二个参数 true 时会强制跳转
        .authorizeRequests()
        .antMatchers("/admin").hasRole("USER")
        .anyRequest().permitAll();
}
```

###### 退出

在 HttpSecurity 对象上调用 logout 方法会创建一个过滤器，会拦截对 /logout 的请求

```java
@Override
protected void configure(HttpSecurity http) throws Exception {
    http.formLogin()
        .and()
        .authorizeRequests()
        .antMatchers("/admin").hasRole("USER")
        .anyRequest().permitAll()
        .and()
        .logout()
        .logoutSuccessUrl("/");
}
```

###### Remember-me

在 HttpSecurity 对象上调用 rememberMe() 即可配置 remember-me 相关功能，通过在 cookie 中存储一个 token（存储在 cookie 中的remember-me 包含用户名、密码、过期时间和一个私钥——在写入 cookie 前都进行了 MD5 哈希）完成，默认 2 周有效期，使用时 input name 为 remember-me

```java
@Override protected void configure(HttpSecurity http) throws Exception {
	http.formLogin()
		.and()
		.rememberMe()
		.tokenValiditySeconds(2419200)  // token 时长
		.key("token");   // token 私钥 key，默认 SpringSecured
}
```

###### 获取认证用户

可以通过以下方法获取当前用户

* 注入 Principal 对象到控制器方法中

  ```java
  @PostMapping public String me(Principal pricipal) {
      Username = pricipal.getName();
  }
  ```

* 注入 Authentication 对象到控制器方法中

  ```java
  @PostMapping public String me(Authentication authentication) {
  	User user = (User) authentication.getPrincipal();  // 返回 object
  }
  ```

* 使用 SecurityContextHolder 来获取安全上下文

  ```java
  Authentication authentication = SecurityContextHolder.getContext().getAuthentication();
  User user = (User) authentication.getPricipal();
  ```

  可以应用在应用程序的任何地方，不限于控制器的处理器方法

* 使用 @AuthenticationPricipal 注解来标注方法

  ```java
  @PostMapping public String me(@AuthenticationPrincipal User user) {}
  ```

  