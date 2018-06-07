### php 

php socket

#### socket 协议类型常量

* AF_UNIX(integer) 进程间协议，使用在 Unix 和 Linux 系统上，当客户端和服务端在同一台机器上时使用
* AF_INET(integer) 使用 TCP 或 UDP 来传输，用于 IPV4 地址
* AF_INET6(interger) 使用 TCP 或 UDP 来传输，用于 IPV6 地址，只有在编译时加入 IPV6 支持的时候才有效

#### socket 类型常量

* SOCK_STREAM(integer) 建立按顺序的、可靠的、数据完整的、基于字节流的连接，使用最多，用TCP来进行传输
* SOCK_DGRAM(integer) 无连接的、固定长度的传输调用，是不可靠的，使用UDP进行连接
* SOCK_SEQPACKET(integer) 建立双线路的可靠连接，发送固定长度的数据包进行传输，必须把这个包完整接收完才能进行读取
* SOCK_RAW(integer) 提供单一的网络访问，使用 ICMP 公共协议 (ping, traceroute 使用该协议)
* SOCK_RDM(integer) 很少使用，在大多数操作系统上没有实现，提供给数据链路层使用，不保证数据包的顺序

#### php 内置的 socket函数

|          函数          |                          说明                           |
| :--------------------: | :-----------------------------------------------------: |
|    socket_accept()     |                  接受一个 socket 连接                   |
|     socket_bind()      |          把 socket 绑定在一个 ip 地址和端口上           |
|  socket_clear_error()  |          清除 Socket 的错误或者最后的错误代码           |
|     socket_close()     |                  关闭一个 Socket 资源                   |
|    socket_connect()    |                  开始一个 Socket 连接                   |
| socket_create_listen() |             在指定端口打开一个 Socket 监听              |
|  socket_create_pair()  |         产生一对没有区别的 socket 到一个数组里          |
|    socket_create()     |                     产生一个socket                      |
|   socket_get_option    |                     获取socket选项                      |
|  socket_getpeername()  |                  获取远程主机的IP地址                   |
|  socket_getsockname()  |                 获取本地Socket的IP地址                  |
|   socket_iovec_add()   |         添加一个新的向量到一个分散、聚合的数组          |
|  socket_iovec_alloc()  | 创建一个能够发送，接收和读写的输入/输出数据向量数据结构 |
| socket_iovec_delete()  |                删除一个已经分配的 iovec                 |
|   socket_iovec_fetch   |                返回指定的iovec资源的数据                |
|   socket_iovec_free    |                    释放一个iovec资源                    |
|    socket_iovec_set    |                   设置iovec的数据新值                   |
|  socket_last_error()   |              获取当前socket的最后错误代码               |
|    socket_listen()     |                监听指定socket的所有连接                 |
|     socket_read()      |                   读取指定长度的数据                    |
|     socket_readv()     |              读取从分散/聚合数组过来的数据              |
|     socket_recv()      |                从socket里结束数据到缓存                 |
|   socket_recvfrom()    | 从指定的socket接收数据，如果没有指定则默认为当前socket  |
|    socket_recvmsg()    |                    从iovec里接收消息                    |
|    socket_select()     |                        多路选择                         |
|     socket_send()      |                发送数据到已连接的socket                 |
|    socket_sendmsg()    |                    发送消息到socket                     |
|    socket_sendto()     |               发送消息到指定地址的socket                |
|   socket_set_block()   |                  在socket里设置块模式                   |
| socket_set_nonblock()  |                 在socket里设置非块模式                  |
|  socket_set_option()   |                     设置socket选项                      |
|   socket_shutdown()    |              允许关闭读，写或指定的socket               |
|   socket_strerror()    |              返回指定错误号的详细错误信息               |
|     socket_write()     |                 写入数据到socket缓存中                  |
|    socket_weitev()     |                写入数据到分散、聚合数组                 |

#### 常用函数

socket_accept - 接受套接字连接

`rource socket_accept (source $socket)`

`socket_create()` 创建后，绑定到名称与 `socket_bind()`，并 `socket_listen`,这个函数将接受套接字上的连接。一旦成功建立连接，将返回一个新的套接字资源，该资源可用于通信。如果套接字上有多个连接排队，则会使用第一个连接。如果没有挂起的连接，则 `socket_accept()` 将阻塞，直到出现连接。如果 socket 使用 `socket_set_blocking()` 或 `socket_set_nonblock()` 进行了非阻塞，则会返回 false。由 `socket_accept()`返回的套接字资源可能不会用于接受新的连接。然而，原始的监听套接字仍处于打开状态。可能会被重用

参数：socket 使用 `socket_create()` 创建的有效套接字资源

返回值：成功或 false 错误时返回新的套接字资源。实际的错误代码可以通过调用 socket_last_error() 来获取。此错误代码可能会传递给 `socket_strerror()` 以获取错误的文本说明

备注：简单 socket Echo Server 会建立一个单进程阻塞的 echo server 对实际业务几乎无作用

```php
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, '127.0.0.1', 10000);
socket_listen($sock, 5);
$msgsock = socket_accept($sock);
$buf = socket_read($msgsock, 2048, PHP_NORMAL_READ);
socket_write($msgsock, $buf, strlen($buf));
```

socket_bind - 给套接字绑定名字

`bool socket_bind (resource $socket, string $address [, int $port = 0])`

绑定 address 到 socket 。该操作必须是在使用 `socket_connect()`或`socket_listen()` 建立一个连接之前.

参数：socket 用 socket_create() 创建的一个有效的套接字资源

​	    address 如果套接字是 AF_INET 族，那么 address 必须是一个四点分法的 IP 地址 (127.0.0.1)

​			   如果套接字是 AF_UNIX 族，那么 address 是 unix 套接字一部分 （/tmp/my.sock）

​	    port (可选) 参数 port 仅仅用于 AF_INET 套接字连接的时候，并且指定连接中需要监听的端口号

返回值：成功时返回 true, 或者在失败时返回 false,错误代码会传入 `socket_last_error()` 如果将此参数传入 `socket_strerror()` 则可以得到错误的文字说明

socket_clear_error - 清楚套接字或者最后的错误代码上的错误

`void socket_clear_error([resource $socket])`

这个函数清除给定的套接字上的错误代码或是最后一个全局的套接字如果套接字没有指定的话，这个函数允许明确的重置错误代码值 不论是一个套接字或者最后全局错误代码的扩展，这对在检测应用的一部分是否有错误发生是十分有用的

参数：socket 用 `socket_crate()` 创建的有效的套接字资源

socket_close - 关闭套接字资源

`void socket_close(resource $socket)`

socket_close() 会关闭掉给定的 socket 资源。这个函数只针对套接字资源有效，不能用在其他类型的资源类型上

参数：socket 由 `socket_create()` 或者是 `socket_accept()` 创建的有效的套接字资源

socket_cmsg_space -计算消息缓冲区大小

`int socket_cmsg_space(int $level, int $type [, int $n = 0]) `

计算应分配给接收辅助数据的缓冲区的大小

socket_connect - 开启一个套接字连接

`bool socket_connect (resource $socket, $string $address [, int $port = 0])`

用 `socket_create()` 创建的有效的套接字资源来连接到 address

参数：socket, address 如果参数 socket 是 AF_INET, 那么参数 address 则可以是一个点分四组表示法的 IPV4 地址；如果支持 IPV6 并且 socket 是 AF_INET6，那么 address 也可以是有效的 IPV6 地址（::1）如果套接字类型为 AF_UNIX ，那么 address 也可以是一个 Unix 套接字，port 仅仅用于 AF_INET 和 AF_INET6 套接字连接的时候。

返回值：成功时返回 TRUE, 或者在失败时返回**FALSE**。 错误代码会传入 [socket_last_error()](http://php.net/manual/zh/function.socket-last-error.php) ，如果将此参数传入 [socket_strerror()](http://php.net/manual/zh/function.socket-strerror.php) 则可以得到错误的文字说明。

socket_create_listen - 在端口上打开套接字以接受连接

`rource socket_create_listen (int  $port, [, int $backlog = 128])`

socket_create_listen() 在给定端口 AF_INET 上的所有本地接口上创建一个新类型的套接字资源，用于等待新连接，此功能旨在简化创建只侦听接收新连接的新套接字的任务。

参数：port 在其上侦听所有接口的端口，backlog 参数定义了未连接队列可能增长到的最大长度。 SOMAXCONN 可以作为 backlog 参数传递。

返回值：在成功时返回一个新的套接字资源，失败或错误返回 false

备注：如果要创建一个只监听某个接口的套接字，需要使用 socket_create(), socket_bind() 和 socket_listen();

如果不指定端口或 0，则将选择一个随机空闲端口，要在同一台机器上的客户机、服务器之间使用端口作为ipc,可以使用（减去错误检查）

socket_create_pair -  创建一对不可区分的套接字并将它们存储在一个数组中

`bool socket_create_pair (int $domain, int $ype, int $protocol, array &$fd)`

socket_create_pair() 创建两个连接的和不可区分的套接字，并将它们存储在 fd, 此功能通常用于 IPC

参数：domain: 指定套接字使用的协议族。type: 套接字使用的通信类型。protocol:在返回的套接字上进行通信时，该 protocol 参数设置的特定协议domain.可以使用 getprotobyname() 通过名称检索正确的值。如果所需的协议是 TCP,或者 UDP 是相应的常量 SOL_TCP,并且 SOL_UDP 也可以使用，fd: 引用一个将插入两个套接字资源的数组

返回值：成功时返回 true, 失败时返回 false

socket_create - 创建一个套接字(通讯节点)

`resource socket_create (int $domain, int $type, int $protocol)`

创建并返回一个套接字，也称作通讯节点。一个典型的网络连接由 2 个套接字构成，一个运行在客户端，另一个运行在服务端

参数：domain 参数指定那个协议用在当前套接字上

AF_INET 	    IPV4 网络协议。TCP 和 UDP 都可以使用此协议

AF_INET6   IPV6 网络协议。TCP 和 UDP 都可以使用此协议

AF_UNIX    本地通讯协议。具有高性能和低成本的 IPC

type: 用于选择 套接字使用的类型

SOCK_STREAM  提供一个顺序化的、可靠的、全双工的、基于连接的字节流。支持数据传送流量控制机制。TCP 协议即基于这种流式套接字

SOCK_DGRAM  提供数据报文的支持。(无连接，不可靠、固定最大长度).UDP协议即基于这种数据报文套接字

SOCK_SEQPACKET 提供一个顺序化的、可靠的、全双工的、面向连接的、固定最大长度的数据通信；数据端通过接收每一个数据段来读取整个数据包

SOCK_RAW  提供读取原始的网络协议。这种特殊的套接字可用于手工构建任意类型的协议。一般使用这个套接字来实现 ICMP 请求（例如 ping）

SOCK_RDM  提供一个可靠的数据层，但不保证到达顺序。一般的操作系统都未实现此功能。

protocol：是设置指定 domain 套接字下的具体协议。这个值可以使用 getprotobyname() 函数进行读取。如果所需的协议是 TCP 或 UDP, 可以直接使用常量 SOL_TCP 和 SOL_UDP

icmp Internet Control Message Protocol 主要用于网关和主机报告错误的数据通信。例如“ping”命令（在目前大部分的操作系统中）就是使用 ICMP 协议实现的。

udp User Datagram Protocol 是一个无连接的、不可靠的、具有固定最大长度的报文协议。由于这些特性，UDP 协议拥有最小的协议开销。

tcp Transmission Control Protocol 是一个可靠的、基于连接的、面向数据流的全双工协议。TCP 能够保障所有的数据包是按照其发送顺序而接收的。如果任意数据包在通讯时丢失，TCP 将自动重发数据包直到目标主机应答已接收。因为可靠性和性能的原因，TCP 在数据传输层使用 8bit 字节边界。因此，TCP 应用程序必须允许传送部分报文的可能

返回值：socket_create() 正确时返回一个套接字，失败时返回 false.要读取错误代码，可以调用 socket_last_error(),这个错误代码可以通过 socket_strerror() 读取文总的错误说明