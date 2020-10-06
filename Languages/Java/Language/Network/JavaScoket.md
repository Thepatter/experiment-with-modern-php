### java Socket

#### Socket 编程

##### 单播

###### *Socket*

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

###### *ServerSocket*

使用 accept 阻塞获取客户端连接

```java
void echoServer {
  	ServerSocket serverSocket = new ServerSocket(8888);
  	Socket socket;
  	while (true) {
        socket = serverSocket.accept();
        DataInputStream dataInputStream = new DataInputStream(socket.getInputStream());
        DataOutputStream dataOutputStream = new DataOutputStream(socket.getOutputStream());
        System.out.println("client say: " + dataInputStream.readUTF());
        dataOutputStream.writeUTF("服务端say hello");
        socket.close();
  	}
}
```

##### 组播

会向加入组的所有成员发送消息，支持公网内传播

###### *MulticastSocket*

实现组播功能

```java
private static final int port = 8000;
private static final String address = "228.0.0.5";
void groupSend() throws IOException, InterruptedException{
  InetAddress group = InetAddress.getByName(address);
  MulticastSocket multicastSocket = new MulticastSocket(port);
  multicastSocket.joinGroup(group);
  while (true) {
    String message = "Hello from node";
    byte[] buffer = message.getBytes();
    DatagramPacket datagramPacket = new DatagramPacket(buffer, buffer.length, group, port);
    multicastSocket.send(datagramPacket);
    Thread.sleep(1000);
  }
}
void receiveGroup() throws IOException {
  InetAddress group = InetAddress.getByName(address);
  MulticastSocket multicastSocket = new MulticastSocket(port);
  multicastSocket.joinGroup(group);
  byte[] bytes = new byte[1024];
  while (true) {
    DatagramPacket datagramPacket = new DatagramPacket(bytes, bytes.length);
    multicastSocket.receive(datagramPacket);
    String s = new String(datagramPacket.getData(), 0, datagramPacket.getLength());
    System.out.println("receive from send: " + s);
  }
}
```

##### 广播

只能在局域网内传播

###### *DatagramSocket*

实现广播功能

```java
void broadSender() throws IOException {
  InetAddress inetAddress = InetAddress.getByName("192.168.3.255");
  DatagramSocket datagramSocket = new DatagramSocket();
  String str = "hello";
  DatagramPacket datagramPacket = new DatagramPacket(str.getBytes(), str.getBytes().length, inetAddress, 9999);
  datagramSocket.send(datagramPacket);
  datagramSocket.close();
}
void broadReceiver() throws IOException {
  DatagramSocket datagramSocket = new DatagramSocket(9999);
  byte[] buffer = new byte[5];
  DatagramPacket datagramPacket = new DatagramPacket(buffer, buffer.length);
  datagramSocket.receive(datagramPacket);
  System.out.println(new String(buffer));
}
```

