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
|    socket_accept()     |                  接收一个 socket 连接                   |
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

