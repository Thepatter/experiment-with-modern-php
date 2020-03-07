### java Socket

#### NIO socket

##### 概述

在 NIO 中，涉及网络连接的通道是 SocketChannel 负责连接传输，另一个是 ServerChannel 负责连接的监听

NIO 的 *SocketChannel* 传输通道与 OIO 中的 *Socket* 类对应

NIO 中的 *ServerSocketChannel* 监听通道，对应与 OIO 的 *ServerSocket* 类，

*ServerSocketChannel* 应用于服务器端，*SocketChannel* 同时处于服务器端和客户端，都支持非阻塞模式。





