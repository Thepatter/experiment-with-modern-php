### 异步消息

#### JMS

##### 概述

JMS 是一个 Java 标准，定义了使用消息代理的通用 API，借助 JMS，所有遵从规范的实现都使用通用的接口。Spring 通过基于模板的抽象为 JML 功能提供了支持。Spring 提供了消息驱动 POJO 的理念（一个简单的 Java 对象，能够以异步的方式响应队列或主题上到达的消息）

##### 配置

必须将 JMS 客户端添加到项目的构建文件中，

* 使用 Apache ActiveMQ

  ```xml
  <dependency>
      <groupId>org.springframework.boot</groupId>
      <artifactId>spring-boot-starter-activemq</artifactId>
  </dependency>
  ```

  ```properties
  # 代理 URL
  spring.activemq.broker-url = tcp://...
  # 代理用户可选
  spring.activemq.user
  # 代理的密码可选
  spring.activemq.password
  ```

* 使用 ActiveMQ Artemis

  ```xml
  <dependency>
  	<groupId>org.springframework.boot</groupId>
  	<artifactId>spring-boot-starter-artemis</artifactId>
  </dependency>
  ```

  Artemis 是重新实现的下一代 ActiveMQ。默认情况下，Spring 会假定 Artermis 代理在 Localhost 的 61616 端口

  ```properties
  # 代理主机
  spring.artemis.host
  # 代理端口
  spring.artemis.port
  # 用来访问代理的用户（可选）
  spring.artemis.user
  # 用来访问代理的密码（可选）
  spring.artemis.password
  ```

#### Rabbitmq

##### 配置

```xml
<dependency>
	<groupId>org.springframework.boot</groupId>
	<artifactId>spring-boot-starter-amqp</artifactId>
</dependency>
```

添加 AMQP starter 到构建文件之后，将会触发自动配置功能。

```properties
# 逗号分割的 RabbitMQ 代理地址列表
spring.rabbitmq.addresses
# 代理的主机，默认 localhost
spring.rabbitmq.host
# 代理端口默认 5672
spring.rabbitmq.port
# 用户名
spring.rabbitmq.username
# 密码
spring.rabbitmq.password
```

##### 使用

Spring 对 rabbitmq 消息支持的核心是 RabbitTemplate。

```java
// 发送原始的消息
void send(Message message) throws AmqpException;
void send(String routingKey, Message message) throws AmqpException;
void send(String exchange, String routingKey, Message message)
                    throws AmqpException;

// 发送根据对象转换而成的消息
void convertAndSend(Object message) throws AmqpException;
void convertAndSend(String routingKey, Object message)
                    throws AmqpException;
void convertAndSend(String exchange, String routingKey,
                    Object message) throws AmqpException;

// 发送根据对象转换而成的消息并且带有后期处理的功能
void convertAndSend(Object message, MessagePostProcessor mPP)
                    throws AmqpException;
void convertAndSend(String routingKey, Object message,
                    MessagePostProcessor messagePostProcessor)
                    throws AmqpException;
void convertAndSend(String exchange, String routingKey,
                    Object message,
                    MessagePostProcessor messagePostProcessor)
                    throws AmqpException;
```

```java
// 接收消息
Message receive() throws AmqpException;
Message receive(String queueName) throws AmqpException;
Message receive(long timeoutMillis) throws AmqpException;
Message receive(String queueName, long timeoutMillis) throws AmqpException;
// 接收由消息转换而成的对象
Object receiveAndConvert() throws AmqpException;
Object receiveAndConvert(String queueName) throws AmqpException;
Object receiveAndConvert(long timeoutMillis) throws AmqpException;
Object receiveAndConvert(String queueName, long timeoutMillis) throws AmqpException;
// 接收由消息转换而成的类型安全的对象
<T> T receiveAndConvert(ParameterizedTypeReference<T> type) throws AmqpException;
<T> T receiveAndConvert(String queueName, ParameterizedTypeReference<T> type)
     throws AmqpException;
<T> T receiveAndConvert(long timeoutMillis, ParameterizedTypeReference<T>
     type) throws AmqpException;
<T> T receiveAndConvert(String queueName, long timeoutMillis,
     ParameterizedTypeReference<T> type) throws AmqpException;
```

@RabbitListener 注解

通过注解声明要监听的消息