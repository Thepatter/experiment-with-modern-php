### 网络操作

#### curl

https://www.php.net/manual/zh/book.curl.php

#### socket

https://www.php.net/manual/zh/book.sockets.php

##### 协议类型常量

* AF_UNIX(integer) 进程间协议，使用在 Unix 和 Linux 系统上，当客户端和服务端在同一台机器上时使用
* AF_INET(integer) 使用 TCP 或 UDP 来传输，用于 IPV4 地址
* AF_INET6(interger) 使用 TCP 或 UDP 来传输，用于 IPV6 地址，只有在编译时加入 IPV6 支持的时候才有效

##### 协议常量

* SOCK_STREAM(integer) 建立按顺序的、可靠的、数据完整的、基于字节流的连接，使用最多，用TCP来进行传输
* SOCK_DGRAM(integer) 无连接的、固定长度的传输调用，是不可靠的，使用UDP进行连接
* SOCK_SEQPACKET(integer) 建立双线路的可靠连接，发送固定长度的数据包进行传输，必须把这个包完整接收完才能进行读取
* SOCK_RAW(integer) 提供单一的网络访问，使用 ICMP 公共协议 (ping, traceroute 使用该协议)
* SOCK_RDM(integer) 很少使用，在大多数操作系统上没有实现，提供给数据链路层使用，不保证数据包的顺序

##### 内置 socket 函数

* `socket_accept()`

   接受一个 socket 连接
   
   `resource socket_accept (source $socket)`
   
   `socket_create()` 创建后，绑定到名称与 `socket_bind()`，并 `socket_listen()`,这个函数将接受套接字上的连接。一旦成功建立连接，将返回一个新的套接字资源，该资源可用于通信。如果套接字上有多个连接排队，则会使用第一个连接。如果没有挂起的连接，则 `socket_accept()` 将阻塞，直到出现连接。如果 socket 使用 `socket_set_blocking()` 或 `socket_set_nonblock()` 进行了非阻塞，则会返回 false。由 `socket_accept()` 返回的套接字资源可能不会用于接受新的连接。然而，原始的监听套接字仍处于打开状态。可能会被重用
   
   参数：socket 使用 `socket_create()` 创建的有效套接字资源
   
   返回值：成功或 false 错误时返回新的套接字资源。实际的错误代码可以通过调用 `socket_last_error()` 来获取。此错误代码可能会传递给 `socket_strerror()` 以获取错误的文本说明
    
    ```php
      $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      socket_bind($sock, '127.0.0.1', 10000);
      socket_listen($sock, 5);
      $msgsock = socket_accept($sock);
      $buf = socket_read($msgsock, 2048, PHP_NORMAL_READ);
      socket_write($msgsock, $buf, strlen($buf));  
    ```
  
* `socket_bind()`
	
   把 socket 绑定在一个 ip 地址和端口上
   
   `bool socket_bind (resource $socket, string $address [, int $port = 0])`
   
   绑定 address 到 socket 。该操作必须是在使用 `socket_connect()` 或 `socket_listen()` 建立一个连接之前.
   
   参数：socket 用 `socket_create()` 创建的一个有效的套接字资源
   
   ​address 如果套接字是 AF_INET 族，那么 address 必须是一个四点分法的 IP 地址 (127.0.0.1)
   
   ​如果套接字是 AF_UNIX 族，那么 address 是 unix 套接字一部分 （/tmp/my.sock）
   
   ​port (可选) 参数 port 仅仅用于 AF_INET 套接字连接的时候，并且指定连接中需要监听的端口号
   
   返回值：成功时返回 true, 或者在失败时返回 false,错误代码会传入 `socket_last_error()` 如果将此参数传入 `socket_strerror()` 则可以得到错误的文字说明

* `socket_clear_error()`
	
   清除 Socket 的错误或者最后的错误代码

   `void socket_clear_error([resource $socket])`
   
  这个函数清除给定的套接字上的错误代码或是最后一个全局的套接字如果套接字没有指定的话，这个函数允许明确的重置错误代码值 不论是一个套接字或者最后全局错误代码的扩展，这对在检测应用的一部分是否有错误发生是十分有用的
   
  参数：socket 用 socket_crate() 创建的有效的套接字资源
   
* `socket_close()`
	
   关闭一个 Socket 资源
   
   `void socket_close(resource $socket)`
   
   `socket_close()` 会关闭掉给定的 socket 资源。这个函数只针对套接字资源有效，不能用在其他类型的资源类型上
   
   参数：socket 由 `socket_create()` 或者是 `socket_accept()` 创建的有效的套接字资源

* `socket_cmsg_space`

   计算消息缓冲区大小
   
   `int socket_cmsg_space(int $level, int $type [, int $n = 0])`
   
   计算应分配给接收辅助数据的缓冲区的大小

* `socket_connect()`
	
   开始一个 Socket 连接

* `socket_create_listen()`
	
   在指定端口打开一个 Socket 监听

* `socket_create_pair()`
	
   产生一对没有区别的 socket 到一个数组里

* `socket_create()`
	
   产生一个socket

* `socket_get_option()`
	
   获取socket选项

* `socket_getpeername()`
	
   获取远程主机的IP地址

* `socket_getsockname()`
	
   获取本地Socket的IP地址

* `socket_iovec_add()`
	
   添加一个新的向量到一个分散、聚合的数组

* `socket_iovec_alloc()`
	
   创建一个能够发送，接收和读写的输入/输出数据向量数据结构

* `socket_iovec_delete()`
	
   删除一个已经分配的 iovec

* `socket_iovec_fetch()`
	
   返回指定的iovec资源的数据

* `socket_iovec_free()`
	
   释放一个iovec资源

* `socket_iovec_set()`
	
   设置iovec的数据新值

* `socket_last_error()`
	
   获取当前socket的最后错误代码

* `socket_listen()`
	
   监听指定socket的所有连接

* `socket_read()`
	
   读取指定长度的数据

* `socket_readv()`
	
   读取从分散/聚合数组过来的数据

* `socket_recv()`
	
   从socket里结束数据到缓存

* `socket_recvfrom()`
	
   从指定的socket接收数据，如果没有指定则默认为当前socket

* `socket_recvmsg()`
	
   从iovec里接收消息

* `socket_select()`	

   多路选择

* `socket_send()`
	
   发送数据到已连接的socket

* `socket_sendmsg()`
	
   发送消息到socket

* `socket_sendto()`
	
   发送消息到指定地址的socket

* `socket_set_block()`
	
   在socket里设置块模式

* `socket_set_nonblock()`
	
   在socket里设置非块模式

* `socket_set_option()`
	
   设置socket选项

* `socket_shutdown()`

   允许关闭读，写或指定的socket

* `socket_strerror()`
	
   返回指定错误号的详细错误信息

* `socket_write()`
	
   写入数据到socket缓存中

* `socket_weitev()`
	
   写入数据到分散、聚合数组