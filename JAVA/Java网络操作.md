## Java 操作网络

### 用 Java 连接到服务器

#### 套接字连接

```java
Socket s = new Socket("time-a.nist.gov", 13);
InputStream inStream = s.getInputStream();
```

第一行代码用于打开一个套接字，是网络软件中的一个抽象概念，负责启动该程序内部和外部之间的通信。将远程地址和端口号传递给套接字的构造器，如果连接失败，将抛出一个 `UnKnownHostException` 异常；如果存在其他问题，将抛出一个 `IOException` 异常。 `UnknowHostException` 是 `IOException` 的一个子类

一旦套接字被打开，`java.net.Socket` 类中的 `getInputStream` 方法就会返回一个 `InputStream` 对象，该对象可以像其他任何流对象一样使用。

在比较复杂的网络程序中，客户端发生请求数据给服务器，而服务器可能在响应结束时并不立刻断开连接。`socket` 类非常简单易用，因为 Java 库隐藏了建立网络连接和通过连接发送数据的复杂过程。实际上，`java.net` 包提供的编程接口与操作文件时所使用的接口基本相同

#### 套接字超时

从套接字读取信息时，在有数据可供访问之前，读操作将会被阻塞。如果此时主机不可达，那么应用将要等待很长的时间，并且因为受底层操作系统的限制而最终会导致超时。调用 `setSoTimeout` 方法设置这个超时值（单位毫秒）。

如果哦已经为套接字设置了超时值，并且之后的读操作和写操作在没有完成之前就超过了时间限制，那么这些操作就会抛出 `SocketTimeoutException` 异常。可以捕获这个异常，并对超时做出反应。另外还有一个超时问题是必须解决的。`Socket(String host, int port)` 会一直无限期地阻塞下去，直到建立了到主机的初始连接为止。可以通过先构建一个无连接的套接字，然后再使用一个超时来进行连接的方式解决这个问题

```java
Socket s = new Socket();
s.connect(new InetSocketAddress(host, port), timeout)
```

#### 因特网地址

`IPv4` 因特网地址是由 4 个字节组成（`IPv6`）是 16 个字节，如果需要在主机名和因特网地址之间进行转换，那么可以使用 `InetAddress` 类。只要主机操作系统支持 `IPv6` 格式的因特网地址，`java.net` 包也可以支持它。

静态的 `getByName` 方法可以返回某个主机的 `InetAddress` 对象

```java
InetAddress address = InetAddress.getByName("time-a.nist.gov");
```

调用 `getAddress` 方法来访问这些字节

```java
byte[] addressBytes = address.getAddress();
```

可以使用静态的 `getLocalHost` 方法来得到本地主机的地址

```java
InetAddress address = InetAddress.getLocalHost();
```

### 实现服务器

#### 服务器套接字

`ServerSocket` 类用于建立套接字

```java
// 建立一个负责监控端口 8189 的服务器
ServerSocket server = new ServerSocket(8189);
```

调用 `accept()` 来等待服务

```java
// 返回一个表示连接已经建立的 socket 对象
Socket incoming = s.accept();
// 得到输入输出流，服务发送给服务器输出流的所有信息都会成为客户端程序的输入，同时来自客户端程序的所有输出都会被包含在服务器输入流中
InputStream inStream = incoming.getInputStream();
OutputStream outStream = incoming.getOutputStream();
```

关闭连接的套接字

```java
incoming.close();
```

每当程序建立一个新的套接字连接，当调用 `accept()` 时，将会启动一个新的线程来处理服务器和该客户端之间的连接，而主程序将立即返回并等待下一个连接。

```java
while (true) {
    Socket incoming = s.accept();
    Runable r = new ThreadedEchoHandler(incoming);
    Thread t = new Thread(r);
    t.start();
}
class ThreadedEchoHandler implements Runable
{
	public void run()
    {
    	try (InputStream inStream = incoming.getInputStream(); OutputStream outStream = incoming.getOutputStream()) {
    		
            } catch(IOException e) {
            	
            }
    }
}
```

#### 半关闭

半关闭提供了一种能力：套接字连接的一段可以终止其输出，同时仍旧可以接收来自另一端的数据（典型情况下，向服务器传输数据，但是一开始并不知道要传输多少数据。在向文件写数据时，只需在数据写入后关闭文件即可。但是，如果关闭一个套接字，那么与服务器连接将立刻断开，因而也就无法读取服务器的响应了）。使用半关闭的方法可以解决上述问题。可以通过关闭一个套接字的输出流来表示发送给服务器的请求数据已经结束，但是必须保持输入流处于打开状态

```
try (Socket socket = new Socket(host, port)) {
    Scanner in = new Scanner(socket.getInputStream(), StandardCharsets.UTF_8);
    PrintWriter writer = new PrintWriter(socket.getOutputStream();
    writer.print();
    writer.flush();
    socket.shutdownOutput();
    while(in.hasNextLine() != null {
        String line = in.nextLine();
    }
}
```

### 可中断套接字

当连接到一个套接字时，当期线程将会被阻塞直到建立连接或产生超时为止。同样地，当通过套接字读写数据时，当前线程也会被阻塞直到操作成功或产生超时为止。在交互式应用中，也会考虑为用户提供一个选项，用以取消那些看似不会产生结果的连接。但是，当线程因套接字无法响应而发生阻塞时，则无法通过调用 `interrupt` 来解除阻塞。

为了中断套接字操作，可以使用 `java.nio` 包提供的一个特性 `SocketChannel` 类

```java
SocketChannel channel = SocketChannel.open(new InetSocketAddress(host, port))
```

通道并没有与之相关联的流，实际上，它拥有的 `read` 和 `write` 方法都是通过使用 `Buffer` 对象来实现的。`ReadableByteChannel` 接口和 `WritableByteChannel` 接口都声明了这两个方法

如果不想处理缓冲区，可以使用 `Scanner` 类从 `SocketChannel` 中读取信息，因为 `Scanner` 有一个带 `ReadableByteChannel` 参数的构造器

```java
Scanner in = new Scanner(channel, "UTF-8");
```

通过调用静态方法 `Channels.newOutputStream` ，可以将通道转换成输出流

```java
OutputStream outStream = Channels.newOutputStream(channel);
```

当线程正在执行打开、读取或写入操作时，如果线程发生中断，那么这些操作将不会陷入阻塞，而是以抛出异常的方式结束

### 获取 Web 数

#### URL 和 URI

`URL` 和 `URLConnection` 类封装了大量复杂的实现细节，这些细节涉及如何从远程站点获取信息。

使用一个字符串构建一个 URL 对象

```java
URL url = new URL(urlString);
```

如果只是想获得该资源的内容，可以使用 `URL` 类中的 `openStream` 方法。该方法将产生一个 `InputStream` 对象，然后就可以按照一般的用法来使用这个对象了。

```java
InputStream inStream = url.openStream();
Scanner in = new Scanner(inStream, StandardCharsets.UTF_8);
```

`java.net` 包对统一资源定位符（URL）和统一资源标识符（URI）作了非常有用的区分

`URI` 是个纯粹的语法结构，包含用来指定 `web` 资源的字符串的各种组成部分。`URL` 是 `URI` 的一个特例，包含了用于定位 `web` 资源的足够信息。其他 URI，如 `mailto:cay@horstman.com` 则不属于定位符，因为根据该标识符无法定位任何数据。这样的 URI 为 URN (统一资源名称)

在 Java 类库中，URI 类并不包含任何用于访问资源的方法，它的唯一作用就是解析。但是，`URL` 类可以打开一个到达资源的流。因此，`URL` 类只能作用于那些 Java 类库知道该如何处理的模式，如 `http:`、`Https:`、`ftp:`、`file:`、`jar:`

`URI` 规范给出了标记这些标识符的规则。一个 `URI` 具有以下句法

```
[scheme:]schemeSpecificPar[#fragment]
```

`[...]` 表示可选部分，并且 `:` 和 `#` 可以被包含在标识符内

包含 `scheme:` 部分的 `URI` 称为绝对 `URI`。否则，称为相对 `URI`

如果绝对 `URI` 的 `schemeSpecificPart` 不是以 `/` 开头的，即它是不透明的。如 `mailto:cay@qq.com`

所有绝对的透明 `URI` 和所有相对 `URI` 都是分层的，一个分层 `URI` 的 `schemeSpecificPart` 具有以下结构

```uri
[//authority][path][?query]
```

`[...]` 表示可选的部分。对于那些基于服务器的 `URI`，`authority` 部分以下形式

```
[user-info@]host[:port]
```

`port` 必须是一个整数。

`URI` 类的作用之一是解析标识符并将它分解成各种不同的组成部分。`URI` 类的另一个作用是处理绝对标识符和相对标识符

#### 使用 URLConnection 获取信息

如果想从某个 `web` 资源获取更多信息，应该使用 `URLConnection` 类，通过它能够得到比基本的 `URL` 类更多的控制功能。当操作一个 `URLConnection` 对象时，使用如下步骤

* 调用 `URL` 类中的 `openConnection` 方法获得 `URLConnection` 对象

  ```java
  URLConnection connection = url.openConnection();
  ```

* 使用以下方法来设置任意的请求属性

  `setDoInput` 、`setDoOupt`、`setIfModifiedSince`、`setUseCaches`、`setAllowUserInteraction`、`setRequestProperty`、`setConnectTimeout`、`setReadTimeout` 等方法

* 调用 `connect` 方法连接远程资源

  ```java
  connection.connect();
  ```

* 除了与服务器建立套接字连接外，该方法还可以向服务器查询头信息 `getHeaderFieldKey` 和 `getHeaderField` 这两个方法枚举了消息头的所有字段。`getHeaderFields` 方法返回一个包含了消息头中所有字段的标准 `Map` 对象。以下方法可以查询各标准字段

  `getContentType`、`getContentLength`、`getContentEncoding`、`getDate`、`getExpiration`、`getLastModified`

* 访问资源数据，使用 `getInputStream` 方法获取一个输入流用以读取信息（这个输入流与 URL 类中的 `openStream` 方法所返回的流相同）。

有几个方法可以在与服务器建立连接之前设置连接属性，其中最重要的是 `setDoInput` 和 `setDoOuptut`。在默认情况下，建立的连接只产生从服务器读取信息的输入流，并不产生任何执行写操作的输出流。如果想获得输出流。需要调用

```java
connection.setDoOutput(true);
```

`setRequestProperty` 可以用来设置对特定协议起作用的任何“键值对”。

