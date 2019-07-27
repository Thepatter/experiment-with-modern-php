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

TLS 由记录协议、握手协议、警告协议、变更密码规范协议、扩展协议等几个子协议组成，综合使用了对称加密、非对称加密、身份认证等密码学技术。浏览器和服务器在使用 TLS 建立连接时需要选择一组恰当的加密算法来实现安全通信，这些算法组合为加密套件（cipher suite），客户端和服务器支持非常多的密码套件，基本形式为 ”密钥交换算法 + 签名算法 + 对称加密算法 + 摘要算法“，如 `ECDHE-RSA-AES256-GCM-SHA384` 即：握手时使用 `ECDHE` 算法进行密钥交换，用 RSA 签名和身份认证，握手后的通透性  

