## 使用 Web 容器

JavaEE Web 应用程序运行在 JavaEE 应用服务器和 Web 容器中。大多数 Web 容器只实现了 Servlet、JSP 和 JSTL 规范。每个应用服务器都包含了一个 Web 容器，用于管理 Servlet 的生命周期、将请求 URL 映射到对应的 Servlet 代码、接收和响应 Http 请求管理过滤器链。每种容器都有自己的优点和不足。也可以同时使用多种不同的 Web 容器：

* Apache Tomcat

  是目前最常见和最流行的 Web 容器。内存小，配置简单。提供了 Servlet、JSP、EL、WebSocket 规范。

* GlassFish

  完整 JavaEE 应用服务器的实现。提供了 JavaEE 规范的所有特性，包括 Web 容器。

* JBoss WildFly

  支持 EJB 和一些 JavaEE 应用服务器。

* Jetty