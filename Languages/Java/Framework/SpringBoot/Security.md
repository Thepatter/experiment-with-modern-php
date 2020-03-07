### Security

#### Spring Security

##### 配置

###### mvn 依赖

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
    <artifactId>spring-boot-starter-security</artifactId>
</dependency>
```

默认会产生一个 basic 保护，初始用户为 user，密码为随机生成在日志中。

###### 使用

spring security 的基础配置类

```java
@Configuration
@EnableWebSecurity
public class SecurityConfig extends WebSecurityConfigurerAdapter {
    // 指定内存用户
    @Override
    protected void configure(AuthenticationManagerBuilder auth) throws Exception {
        auth.inMemoryAuthentication()
                .passwordEncoder(new BCryptPasswordEncoder())
                .withUser("admin")
                .password(new BCryptPasswordEncoder().encode("secret"))
                .authorities("ROLE_USER")
                .and()
                .withUser("zyw")
                .password(new BCryptPasswordEncoder().encode("123456"))
                .authorities("ROLE_USER");

    }
    // 保护 web 请求
    @Override
    protected void configure(HttpSecurity http) throws Exception {
        http.authorizeRequests()
                .antMatchers("/design", "/orders") // 需要验证
                .hasRole("ROLE_USER")
                .antMatchers("/", "/**").permitAll();
    }
}
```







