*java.net.Socket*
```java
Socket(String host, int port);

// 构建一个套接字，用来连接给定的主机和端口

Socket();

// 创建一个还未被连接的套接字

void connect(SocketAddress address);

// 将该套接字连接到给定的地址

void connect(SocketAddress address, int timeoutInMilliseconds);

// 将套接字连接到给定的地址。如果在给定的时间内没有响应，则返回

void setSoTimeout(int timeoutInMilliseconds);

// 设置该套接字上读请求的阻塞时间。如果超出给定时间，则抛出一个 `InterruptedIOException` 异常

boolean isConnected();

// 如果该套接字已被连接，则返回 `true`

boolean isClosed();

// 如果套接字已经被关闭，则返回 `true`

InputStream getInputStream();

OutputStream getOutputStream();

// 获取可以从套接字中读取或写入的数据流

void shutdownOutput();

// 将输出流设为 “流结束”

void shutdownInput();

// 将输入流设为“流结束”

boolean isOutputShutdown();

// 如果输入已经关闭，则返回 `true`

boolean isInputShutdown();

// 如果输入已经关闭，则返回  `true`

```