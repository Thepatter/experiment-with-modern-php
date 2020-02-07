*java.net.InetSocketAddress*
```java
InetSocketAddress(String hostname, int port);

// 用给定的主机和端口参数创建一个地址对象，并在创建过程中解析主机名。如果主机名不能被解析，那么该地址对象的 `unresolved` 属性将被设为 `true`

boolean isUnresolved();

// 如果不能解析该地址对象，则返回 `true`

```