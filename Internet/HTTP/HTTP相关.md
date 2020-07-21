### HTTP 相关

#### WebSocket

##### 特点

* WebSocket 是一个真正的“全双工”的通信协议，与 TCP 一样，客户端和服务器都可以随时向对方发送数据，
* 采用二进制帧结构，算法，语义与 HTTP 完全不兼容
* 服务器发现方面，WebSocket 采用了 URI 格式：`ws` 和 `wss`，对应端口 80 和 443

##### 帧格式

*WebSocket帧结构定义*

![](../Images/WebSocket帧结构.png)

*长度不固定，最少2个字节，最多14字节*

* 开头两个字节是必须的

  第一个字节的第一位 `FIN` 是消息结束的标志位，相当于 HTTP/2 里的 `END_STREAM`，表示数据发送完毕。一个消息可以拆成多个帧，接收方看到 `FIN` 后，就可以把前面的帧拼起来，组成完整的消息

* FIN 后三位是保留位，目前没有任何意义，必须是 0

* 第一个字节的后 4 位是 `Opcode`，操作码，即帧类型，1 即纯文本，2 是二进制数据，8 关闭连接，9 和 10 分别是连接保活的 PING 和 PONG

* 第二个字节后 7 位是 `Payload len`，即帧内容长度，它是一种变长编码，最少 7 位，最多 7 + 64 位，即额外增加 8 字节，一个 WebSocket 帧最大是 2^64

* Masking-key，掩码密钥，由标志位 `MASK` 决定的，如果掩码就是 4 个字节的随机数，否则就不存在

##### WebSocket 的握手

和 TCP、TLS 一样，WebSocket 也要有一个握手过程，然后才能正式收发数据。WebSocket 的握手是一个标准的 HTTP GET 请求，但要带上两个协议升级的专用头字段：

* `Connection: Upgrade`，表示要求协议升级
* `Upgrade: websocket`，表示要升级成 WebSocket 协议

为了防止普通的 HTTP 消息被意外识别成 WebSocket，握手消息还增加了两个额外的认证用头字段

* `Sec-WebSocket-key`：一个 Base64 编码的 16 字节随机数，作为简单的认证密钥
* `Sec-WebSocket-Version`：协议的版本号，当前必须 13

*websocket握手*

![](../Images/websocket握手.png)

服务器收到 HTTP 请求报文，看到上面几个字段，就知道这是 WebSocket 的升级请求，于是就不走普通的 HTTP 处理流程，而是构造一个特殊的 `101 Switching Protocols` 响应报文，通知客户端。

WebSocket 的握手响应报文也有特殊格式，要用字段 `Sec-WebSocket-Accept` 验证客户端请求报文，同样也是为了防止误连接。流程是将请求头里的 `Sec-WebSocket-Key` 的值，加上一个专用 UUID  `258EAFA5-E914-47DA-95CA-C5AB0DC85B11`，再计算 SHA-1 摘要

```java
encode_base64(
  sha1( 
    Sec-WebSocket-Key + '258EAFA5-E914-47DA-95CA-C5AB0DC85B11' ))
```

客户端收到响应报文，就可以用同样的算法，比较值是否相等，如果相等，说明返回的报文确实是刚才握手时连接的服务器，认证成功，握手完成，后续传输数据即为 WebSocket 格式二进制帧

#### CDN

##### Content Delivery Network

外部加速 HTTP 协议的服务。CDN 的核心原则是『就近访问』。主要适用缓存代理技术，使用『推』或『拉』的手段，把源站的内容逐级缓存到网络的每一个节点上。用户在上网的时候就不直接访问源站，而是访问离他最近的一个 CDN 节点（边缘节点）即缓存了源站内容的代理服务器。

在 CDN 领域里，『内容』即 HTTP 协议的『资源』，比如超文本、图片、视频、应用程序安装包。资源按照是否可缓存分为：

* 静态资源

  数据内容静态不变，任何时候来访问都是一样的，如图片、音频

* 动态资源 

  由服务器实时计算生成的。每次访问不一样。

只有静态资源才能被缓存加速、就近访问，而动态资源只能由源站实时生成，即使缓存了也没有意义。不过，如果动态资源指定了 Cache-Control，允许缓存短暂的时候，那它在这段时间里就变成了『静态资源』，可以被 CDN 缓存加速

##### CDN 负载均衡

CDN 有两个关键组成部分：**全局负载均衡**和**缓存系统**，对应的是 DNS 和缓存代理技术。

全局负载均衡（Global Server Load Balance）GSLB，它是 CDN 的大脑，主要职责是当用户接入网络的时候在 CDN 专网中挑选出一个最佳节点提供服务，解决的是用户如何找到最近的边缘节点，对整个 CDN 网络进行“负载均衡”。

GSLB 最常见的实现方式是 “DNS负载均衡”，原来没有 CDN 的时候，权威 DNS 返回的是网站自己服务器的实际 IP 地址，浏览器收到 DNS 解析结果后直连网站。但加入 CDN 后，权威 DNS 返回的不是 IP 地址，而是一个 CNAME（Canonical Name）别名记录，指向的就是 CDN 的 GSLB。因为没能获取 IP 地址，于是本地 DNS 就会向 GSLB 再发起请求，这样就进入了 CDN 的全局负载均衡系统，开始调度，依据：

* 看用户的 IP 地址，查表得到地理位置，找相对最近的边缘节点
* 看用户所在的运营商网络，找相同网络的边缘节点
* 检查边缘节点的负载情况，找负载较轻的节点
* 检查节点的服务能力，带宽，响应时间等

GSLB 会根据这些因素，用算法，找出一个最合适的边缘节点。把这个节点的 IP 地址返回给用户，用户就可以就近访问 CDN 的缓存代理

##### CDN 缓存代理

缓存系统是 CDN 的另一个关键组成部分，衡量 CDN 服务质量的指标：“命中率”和“回源率”

* 命中

  指用户访问的资源恰好在缓存系统里，可以直接返回给用户

* 回源

  缓存里没有，必须使用代理的方式回源站取

#### HTTP Security

##### 安全威胁

###### XSS 跨站脚本攻击

攻击者可以利用这种漏洞在网站上注入恶意的客户端代码,即利用浏览器对文档源的信息,在展示文档时植入特定脚本代码来获取敏感信息或重写 HTML 内容

在以下情况,容易发生 XSS 攻击:

1.  数据从一个不可靠的链接进入到一个 Web 应用程序
2.  没有过滤掉恶意代码的动态内容被发送给 web 用户

恶意内容一般包括 [JavaScript](https://developer.mozilla.org/zh-CN/docs/Glossary/JavaScript)，但是，有时候也会包括 HTML，FLASH 或是其他浏览器可执行的代码。XSS 攻击的形式千差万别，但他们通常都会：将 cookies 或其他隐私信息发送给攻击者，将受害者重定向到由攻击者控制的网页，或是经由恶意网站在受害者的机器上进行其他恶意操作。

XSS 攻击可以分为3类：

*   存储型（持久型）

    注入型脚本永久存储在目标服务器上,当浏览器请求数据时,脚本从服务器上传回并执行

*   反射型（非持久型）

    当用户点击一个恶意链接，或者提交一个表单，或者进入一个恶意网站时，注入脚本进入被攻击者的网站。Web服务器将注入脚本，比如一个错误信息，搜索结果等 返回到用户的浏览器上。由于浏览器认为这个响应来自"可信任"的服务器，所以会执行这段脚本

*   DOM 型

    通过修改原始的客户端代码，受害者浏览器的 DOM 环境改变，导致有效载荷的执行。也就是说，页面本身并没有变化，但由于DOM环境被恶意修改，有客户端代码被包含进了页面，并且意外执行

##### CSP 内容安全策略

CSP 是一个额外的安全层，用于检测并削弱某些特定类型的攻击，包括跨站脚本 XSS 和数据注入攻击。CSP 被设计成完成向后兼容（除 CSP2 在向后兼容有明确提及的不一致）不支持 CSP 的浏览器也能与实现了 CSP 的服务器正常合作，不支持 CSP 的服务器也能与支持 CSP 的浏览器正常合作（浏览器会忽略，默认为网页内容使用标准的同源策略）

需要服务器返回 `Content-Security-Policy` 头，或者使用 meta 元素配置

```html
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src https://*; child-src 'none';">
# 禁用不安全内联/动态执行,只允许通过 https 加载资源
Content-Security-Policy: default-src https:
```

CSP通过指定有效域——即浏览器认可的可执行脚本的有效来源——使服务器管理者有能力减少或消除XSS攻击所依赖的载体。一个CSP兼容的浏览器将会仅执行从白名单域获取到的脚本文件，忽略所有的其他脚本 (包括内联脚本和HTML的事件处理属性)。作为一种终极防护形式，始终不允许执行脚本的站点可以选择全面禁止脚本执行

###### Content-Security-Policy

HTTP 响应头允许站点管理者控制用户代理能够为指定的页面加载哪些资源。除少数例外情况，设置的政策主要涉及指定服务器的源和脚本结束点。主要防止 XSS

```
Content-Security-Policy: <policy-directive>; <policy-directive>
```

fetch 指令用来控制某些具体类型的资源可以从那些来源被加载,所有指令的值都会回落到 default-src,如果某个 fetch 指令在 CSP 头部中未定义,那么用户代理会寻找 default-src 指令的值来替代

*fetch 指令来控制某些可能被加载的确切的资源类型的位置*

|     指令     |                             描述                             |
| :----------: | :----------------------------------------------------------: |
|  child-src   |                                                              |
| connect-src  |                 限制能通过脚本接口加载的 URL                 |
| default-src  |                   为其他取指令提供备用服务                   |
|   font-src   |                   设置允许勇敢 @font-face                    |
|  frame-src   | 设置允许通过类似 <frame> 和 <iframe> 标签加载的内嵌内容的源地址 |
|   img-src    |                    限制图片和图标的源地址                    |
| manifest-src |                   限制应用声明文件的源地址                   |
|  media-src   |  限制通过 <audio>,<video>,<track> 标签加载的媒体文件源地址   |
|  object-src  | 限制 <object>,<embed>,<applet> 标签的源地址,建议设为 'none'  |
| prefetch-src |                指定预加载或预渲染的允许源地址                |
|  script-src  |                   限制 JavaScript 的源地址                   |
|  style-src   |                       限制 css 文件源                        |
|  webrtc-src  |                 指定 WebRTC 连接的合法源地址                 |
|  worker-src  |         限制 worker,ShardWorker,ServiceWorker 脚本源         |

*文档指令管理文档属性或 worker 环境应用的策略*

|     指令      |                            描述                            |
| :-----------: | :--------------------------------------------------------: |
|   base-uri    |          限制在 DOM 中 <base> 元素可以使用的 URL           |
| plugin-types  | 通过限制可以加载的资源类型来限制那些插件可以被嵌入到文档中 |
|    sandbox    |       类似<iframe>,sandbox 属性,为请求的资源启用沙盒       |
| disown-opener |             确保资源在导航的时候能够脱离父页面             |

*导航指令管理用户能打开的链接或表单可提交的链接*

|      指令       |                             描述                             |
| :-------------: | :----------------------------------------------------------: |
|   form-action   | 限制被用来作为给定上下文的表单提交的目标 URL 即 from 的 action 属性 |
| frame-ancestors | 指定可能嵌入页面的有效父项 <frmae>,<iframe>,<object>,<embed>,<applet> |
|  navigation-to  | 限制文档可以通过(a,from,window.location,window.open,etc.)方法的 URL |

*报告指令控制 CSP 违规的报告过程*

| 指令       | 描述                                       |
| ---------- | ------------------------------------------ |
| report-uri | 当出现可能违反 CSP 操作时,让客户端提交报告 |
| report-to  |                                            |

*其他指令*

|           指令            |                             描述                             |
| :-----------------------: | :----------------------------------------------------------: |
|  block-all-mixed-content  |      当使用 HTTPS 加载页面时阻止使用 HTTP 加载任何资源       |
|         referrer          | 指定会离开当前页面的跳转链接的 referer header 信息(应使用 Referrer-Policy 替代) |
|      require-sri-for      |            需要使用 SRI 作用于页面上的脚本或样式             |
| upgrade-insecure-requests | 让浏览器把一个网站所有的不安全 URL 当做已经被安全的 URL 链接替代 |

###### Content-Security-Policy-Report-Only

响应头允许通过监视(但不强制执行)效果来实验策略.这些违规报告包含通过 HTTP 请求发送到指定 URI 的 JSON 文档 POST

```
Content-Security-Policy-Report-Only: <policy-directive>; <policy-directive>
Content-Security-Policy-Report-Only: default-src https:; report-uri /csp-violation-report-endpoint/
```

Content-Security-Policy 头指令也可应用于该指令,Content-Security-Policy 的 report-uri 指令需要和该 header 一起使用,否则不会起作用

违规报告

```json
{
  "csp-report": {
      // 发生违规的文档 URI
    "document-uri": "http://example.com/signup.html",  
      // 发生违规的文档 referrer
    "referrer": "",
      // 被内容安全政策阻塞加载的资源的 URI,如果被阻塞的 URI 于文档 URI 不同源,则被阻塞的 URI 被截断为只包含 schema,host,port,其他源则只包含来源
    "blocked-uri": "http://example.com/css/style.css",
      // 被违反的策略名
    "violated-directive": "style-src cdn.example.com",
      // CSP HTTP 头原始策略
    "original-policy": "default-src 'none'; style-src cdn.example.com; report-uri /_/csp-reports",
      // 执行或报告即是因为 CSP 头报告还是因为 CSPRO 头报告
    "disposition": "report"
  }
}
```

##### HPKP 公钥锁定

HTTP公钥锁定（HPKP）是一种安全功能，它告诉Web客户端将特定加密公钥与某个Web服务器相关联，以降低使用伪造证书进行MITM攻击的风险

为确保 TLS 会话中使用的服务器公钥的真实性，此公钥将包装到 X.509 证书中，该证书通常由证书颁发机构（CA）签名。诸如浏览器之类的Web客户端信任许多这些 CA，它们都可以为任意域名创建证书。如果攻击者能够攻击单个CA，则他们可以对各种TLS连接执行 MITM 攻击。 HPKP 可以通过告知客户端哪个公钥属于某个 Web 服务器来规避 HTTPS 协议的这种威胁

HPKP是首次使用信任（TOFU）技术。 Web服务器第一次通过特殊的HTTP标头告诉客户端哪些公钥属于它，客户端会在给定的时间段内存储此信息。当客户端再次访问服务器时，它希望证书链中至少有一个证书包含一个公钥，其指纹已通过HPKP已知。如果服务器提供未知的公钥，则客户端应向用户发出警告

###### 启用

需要在通过HTTPS访问站点时返回 Public-Key-Pins HTTP标头：

```
# pin-sha256 引用的字符串是 Base64 编码的主体公钥信息指纹,当前规范要去包含第二个备用密钥
# max-age 浏览器应记住仅使用其中一个已定义的密钥访问此站点的时间(以秒为单位)
# includeSubDomains 可选,如果指定了此可选参数,则此规则也适用于所有站点的子域
# report-uri 可选,如果指定了此可选参数,则会将指纹验证失败报告给给定的 URL
Public-Key-Pins: pin-sha256="base64=="; max-age=expireTime [; includeSubDomains][; report-uri="reportURI"]

Public-Key-Pins: 
  pin-sha256="cUPcTAZWKaASuYWhhneDttWpY3oBAkE3h2+soZS7sWs="; 
  pin-sha256="M8HztCzM3elUxkcjR2S5P4hhyBNf6lHkmjAHKhpGPWE="; 
  max-age=5184000; includeSubDomains; 
  report-uri="https://www.example.org/hpkp-report"
```

###### 服务器配置

*   nginx

    ```nginx
    add_header Public-Key-Pins 'pin-sha256="base64+primary=="; pin-sha256="base64+backup=="; max-age=5184000; includeSubDomains' always;
    ```

*   apache

    ```apache
    Header always set Public-Key-Pins "pin-sha256=\"base64+primary==\"; pin-sha256=\"base64+backup==\"; max-age=5184000; includeSubDomains"
    ```

##### 浏览器同源策略

用于限制一个 origin 的文档或者它加载的脚本如何能与另一个源的资源进行交互,它能帮助阻隔恶意文档,减少可能被攻击的媒介

###### 同源定义

如果两个 URL 的协议,端口,主机都相同的话,则这两个URL是同源

###### 源更改

满足某些限制条件的情况下,页面可以修改它的源,脚本可以将 `document.domain` 的值设置为其当前域或其当前域的父域.如果将其设置为其当前域的父域,则这个较短的父域将用于后续源检查,端口号由浏览器另行检查.任何对 `document.domain` 的赋值操作,包括 `document.domain = document.domain` 都会导致端口号被重写为 null.

使用 `document.domain` 来允许子域安全访问其父域时，需要在父域和子域中设置 document.domain 为相同的值。这是必要的，即使这样做只是将父域设置回其原始值。不这样做可能会导致权限错误

###### 跨源网络访问

同源策略控制不同源之间的交互,在使用 XMLHttpRequest 或 img 标签时则会受到同源策略的约束

*   跨域写操作(Cross-origin writes)

    一般是被允许,例如 links 重定向及表单提交

*   跨域资源嵌入(Cross-origin embedding)

    一般是被允许(`<script src=""></script>`,`<link rel="stylesheet" href="">`(由于 CSS 的松散的语法规则,CSS 跨域需要设置一个正确的 HTTP 头部 Content-Type),`<img>`,`<video>`,`<object>`,`<embed>`,`<applet>`, @font-face 引入的字体,`<iframe>` 载入的任何资源)

