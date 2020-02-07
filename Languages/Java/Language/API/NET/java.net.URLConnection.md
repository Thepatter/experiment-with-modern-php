*java.net.URLConnection*
```java
void setDoUnput(boolean doInput);

boolean getDoInput();

// 如果 `doInput` 为 `true`，那么用户可以接收来自该 `URLConnection` 的输入

void setDoOutput(boolean doOutput);

boolean getDoOutput();

// 如果 `doOutput` 为 `true`，那么用户可以将输出发送到该 `URLConnection`

void setIfModifiedSince(long time);

long getIfModifiedSince();

// 属性 `ifModifiedSince` 用于配置该 `URLConnection` 对象，使它只获取那些自从某个给定时间以来被修改过的数据。调用方法时需要传入的 `time` 参数指的是从格林尼治时间开始计算的秒数

void setUseCaches(boolean useCaches);

boolean getUseCaches();

// 如果 `useCaches` 为 `true`，那么数据可以从本地缓存中得到。`URLConnection` 本身并不维护这样一个缓存，缓存必须由浏览器之类的外部程序提供

void setAllowUserInteraction(boolean allowUserInteraction);

boolean getAllowUserInteraction();

// 如果 `allowUserInteraction` 为 `true`，那么可以查询用户的口令。`URLConnection` 本身并不提供这种查询功能。查询必须由浏览器或浏览器插件之类的外部程序实现

void setConnectTimeout(int timeout);

int getConnectTimeout();

// 设置或得到连接超时时限（毫秒）。如果在连接建立之前就已经达到了超时的时限，那么相关联的输入流的 `connect` 方法就会抛出一个 `SocketTimeoutException` 异常

void setReadTimeout(int timeout);

int getReadTimeout();

// 设置读取数据的超时时限（毫秒）。如果在一个读操作成功之前就已经达到了超时的时限，那么 `read` 方法就会抛出一个 `SocketTimeoutException` 异常

void setRequestProperty(String key, String value);

// 设置请求头的一个字段

Map<String, List<String>> getRequestProperties();

// 返回请求头属性的一个映射表。相同的键对应的所有值被放置在同一个列表中

String getHeaderFieldKey(int n);

// 得到响应头等 n 个字段的键。如果 n 小于等于 0 或大于响应头字段的总数，则该方法返回 null 值

int getContentLength();

// 如果内容长度可获得，则返回该长度值，否则返回 -1

String getContentType();

// 获取内容的类型，如 `text/plain` 或 `image/gif`

String getContentEncoding();

// 获取内容的编码机制，如 `gzip`。这个值不太常用，因为默认的 `identity` 编码机制并不是用 `Content-Encoding` 头来设定的

long getDate();

long getExpiration();

long getLastModified();

// 获取创建日期，过期日以及最后一次被修改的日期。这些日期是标准时间开始的秒数

InputStream getInputStream();

OutputStream getOutputStream();

// 返回从资源读取信息或向资源写入信息的流

Object getContent();

// 选择适当的内容处理器，以便读取资源数据并将它转换成对象。该方法对于读取诸如 `text/plain` 或 `image/gif` 之类的标准内容类型并没有什么用处，除非安装了自定义内容处理器

```