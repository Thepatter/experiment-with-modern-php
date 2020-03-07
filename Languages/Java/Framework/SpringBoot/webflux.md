### WebFlux

#### 概述

WebFlux 并不会绑定 Servlet API，它构建在 Reactive HTTP API 上，这个 API 与 Servlet API 具有相同的功能，采用了反应式的方式。Spring WebFlux 没有与 Servlet API 耦合，它的运行不需要 Servlet 容器。可以运行在任意非阻塞 Web 容器中，包括 Netty、Undertow、Tomcat、Jetty 或任意 Servlet 3.1 及以上的容器

#### 配置

##### 依赖

mvn

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
	<artifactId>sdpring-boot-starter-webflux</artifactId>
</dependency>
```

WebFlux 的默认嵌入式服务器是 Netty，Spring WebFlux 的控制器方法要接受和返回反应式类型，如 Mono 和 Flux，而不是领域类型和集合。

##### 使用

Spring WebFlux 控制器通常会返回 Mono 和 Flux，允许在事件轮询中处理请求；而 Spring MVC 是基于 Servlet 的，依赖于多线程来处理多个请求



