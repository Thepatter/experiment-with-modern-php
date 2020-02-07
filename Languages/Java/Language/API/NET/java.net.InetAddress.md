*java.net.InetAddress*
```java
static InetAddress getByName(String host);

static InetAddress[] getAllByName(String host);

// 为给定的主机名创建一个 `InetAddress` 对象，或者一个包含了该主机名所对应的所有因特网地址的数组

static InetAddress getLocalHost();

// 为本地主机创建一个 `InetAddress` 对象

byte[] getAddress();

// 返回一个包含数字型地址的字节数组

String getHostAddress();

// 返回一个由十进制数组成的字符串，各数字间用圆点符号隔开

String getHostName();

// 返回主机名

```