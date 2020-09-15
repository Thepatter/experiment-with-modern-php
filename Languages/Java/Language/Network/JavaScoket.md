### java Socket

#### Socket

##### 客户端 Socket

当客户端的 Socket 构造方法请求与服务器连接时，可能要等待一段时间。在默认情况下，Socket 构造方法会一直等待下去，直到连接成功，或者出现异常。

```java
// 指定连接超时时间
Socket socket = new Socket();
SocketAddress remoteAddr = new InetSocketAddress("localhost", 8000);
socket.connect(remoteAddr, 6000);
```

在一个 Socket 对象中，既包含远程服务器的 IP 和 Prot，也包含本地客户端的 IP 和 Port（客户端默认 IP 为主机 IP，Port 为随机分配）

```java
// 指定客户端 IP 和端口, localAddr 和 localPort 指定本地客户端 IP 和 Port
Socket(InetAddress address, int port, InetAddress localAddr, int localPort)
```





