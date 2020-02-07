*java.net.ServerSocket*
```java
ServerSocket(int port);

// 创建一个监听端口的服务器套接字

Socket accept();

// 等待连接。该方法阻塞，当前线程直到建立连接为止。该方法返回一个 `Socket` 对象，程序可以通过这个对象与连接中的客户端进行通信

void close();

// 关闭服务器套接字

```