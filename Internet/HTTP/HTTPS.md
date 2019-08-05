## HTTPS

### SSL/TLS

#### 通信安全

如果通信过程具备了：机密性、完整性、身份认证、不可否认

* 机密性（Secrecy/Confidentiality）

  指对数据的保密，只能由可信的人访问，对其他人是不可见的秘密，即除了通信方外其他人不可见

* 完整性（Integrity）

  指数据在传输过程中没有被篡改

* 身份认证（Authentication）

  确认对方的真实身份，保证消息只能发送给可信方

* 不可否认（Non-repudiation/Undeniable)

  不能否认已经发生过的行为

#### HTTPS 协议

HTTPS 协议规定了协议名为 `HTTPS`，默认端口号 443，请求-应答，报文结构，请求方法，URI，头字段，连接管理等都完全沿用 HTTP。HTTPS 将 HTTP 下层的传输协议由 `TCP/IP` 换成了 `SSL/TLS`，由**`HTTP over TCP/IP`** 变成了 **`HTTP over SSL/TLS`**，让 HTTP 运行在安全的 SSL/TLS 协议上，收发报文不再使用**`Socket API`**，而是调用专门的安全接口

*HTTPS通信流程* 

![](../Images/HTTPS通信流程.png)

#### SSL/TLS

SSL 即安全套阶层（Secure Socket Layer)，在 OSI 模型中处于第 5 层（会话层），由 v2 和 v3 两个版本，SSL 发展到 v3 时被互联网工程组 IETF 在 1999 年标准化为 TLS（Transport Layer Security），版本号从 1.0 算，TLS 1.0 即 SSLv3.1。TSL 发展出三个版本，2006 1.1，2008 1.2，2018 1.3。目前应用广泛的是 TLS 1.2，之前的 1.0，1.1 各大浏览器将在 2020 年左右停止支持。

TLS 由记录协议、握手协议、警告协议、变更密码规范协议、扩展协议等几个子协议组成，综合使用了对称加密、非对称加密、身份认证等密码学技术。浏览器和服务器在使用 TLS 建立连接时需要选择一组恰当的加密算法来实现安全通信，这些算法组合为加密套件（cipher suite），客户端和服务器支持非常多的密码套件，基本形式为 ”密钥交换算法 + 签名算法 + 对称加密算法 + 摘要算法“，如 `ECDHE-RSA-AES256-GCM-SHA384` 即：握手时使用 `ECDHE` 算法进行密钥交换，用 RSA 签名和身份认证，握手后的通信使用 AES 对称算法，密钥 长度 256 位，分组模式 GCM，摘要算法 SHA384 用于消息认证和产生随机数

### TLS 协议的组成

TLS 包含几个子协议，比较常用的有记录协议、警报协议、握手协议、变更密码规范协议

* 记录协议（Record Protocol）

  规定了 TLS 收发数据的基本单位：记录（record）。类似 TCP 里的 segment，所有的其他子协议都需要通过记录协议发出。但多个记录数据可以在一个 TCP 包里一次性发出，也不需要像 TCP 那样返回 ACK

* 警报协议（Alert Protocol）

  向对方发出警报信息，类似 HTTP 协议里的状态码。收到警报后，另一方可以选择继续，也可以终止连接

* 握手协议（Handshake Protocol）

  是 TLS 里最复杂的子协议，浏览器和服务器会在握手过程中协商 TLS 版本号、随机数、密码套件等信息，然后交换证书和密钥参数，最终双方协商得到会话密钥，用于后续的混合加密系统

* 变更密码规范协议（Change Cipher Spec Protocol），就是一个通知，告诉对方，后续的数据都将使用加密保护，在它之前数据都是明文的

*TLS握手过程*

![](../Images/TLS握手过程.png)


### 配置 HTTPS

#### 申请证书

* 申请证书时应当同时申请 RSA 和 ECDSA 两种证书，在 nginx 里配置成双证书验证，这样服务器可以自动选择快速的椭圆曲线证书，同时也兼容只支持 RSA 的客户端

* 如果申请 RSA 证书，密钥至少要 2048 位，摘要算法应选择 SHA-2

#### 配置 HTTPS

配置 web 服务器，在 443 端口开启 HTTPS 服务

```nginx
listen 443 ssl;
ssl_certificate rsa.crt; # rsa2048 cert
ssl_ceritificate_key rsa.key; # rsa2048 private key
ssl_ceritificate  ecc.crt; # ecdsa cert
ssl_ceritificate _key  ecc.key; # ecdsa private key
# 强制只支持 TLS1.2 以上的协议，打开 Session Ticket 会话复用
ssl_protocols TLSv1.2 TLSv1.3;
ssl_session_timeout 5m;
ssl_session_tickets on;
ssl_session_ticket_key ticket.key;
# 密码套件配置
ssl_prefer_server_ciphers   on;
ssl_ciphers ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-CHACHA20-POLY1305:ECDHE+AES128:!MD5:!SHA1;
# 添加 HSTS 头
add_header Strict-Transport-Security max-age=15768000; #182.5days
```