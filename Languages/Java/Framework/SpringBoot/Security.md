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

##### 使用

spring security 的基础配置类

```java
@Configuration
@EnableWebSecurity
public class SecurityConfig extends WebSecurityConfigurerAdapter {}
```

Spring Security 为配置用户存储提供了多个可选方案，通过覆盖 *webSecurityConfigurerAdapter* 基础配置类中定义的 configure() 方法来进行配置，支持：

###### 内存用户

覆写 config 方法

```java
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
```

###### JDBC

```java
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
```

###### LDAP

###### 自定义

1. 指定实体实现 Spring Security 的 UserDetails 接口
2. 实现 UserDetailsService 接口的 UserDetails loadUserByUsername(String username) throws UsernameNotFoundException 方法
3. 覆写配置类的 configure 方法





