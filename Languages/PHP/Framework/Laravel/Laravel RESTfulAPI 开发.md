## RESTFUL API
### 含义 :表现层状态转移, 用 `URL 定位资源,用 http 动词描述操作`

### 版本控制: 

* 将版本号直接加入 URL 中
```
https://api.laravel.com/v1
https://api.laravel.com/v2
```
* 使用  HTTP 请求头的 Accept 字段进行区分
```
https://api.laravel.com/
    Accept: application/prs.larabbs.v1+json
    Accept: application/prs.larabbs.v2+json
```
### 用 URL 定位资源

在 RESTful 的架构中,所有的一切都表示资源,每一个 URL 都代表资源,资源应当是一个名词,而且大部分情况下是名词的复数,尽量不在 URL 中出现动词
```
GET /issues                 列出所有的 issue
GET /orgs/:org/issues       列出某个项目的 issue
GET /repose/:owener/:repo/issues/:number    获取某个项目的某个 issue
POST /repos/:owner/:repo/issues             为某个项目有创建 issue
PATCH /repos/:owner/:repo/issues/:number    修改某个 issue
PUT /repos/:owner/:repo/issues/:number/lock 锁住某个 issue
DELETE /repos/:owner/:repo/issues/:number/lock 接收某个 issue
```
* 资源的设计可以嵌套,表明资源与资源之间的关系
* 大部分情况下我们访问的是某个 `资源集合`, 想得到 `单个资源` 可以通过资源的 id 或 number 等唯一标识获取
* 某些情况下,资源会是单数形式, 例如 `某个项目某个 issue 的锁`, 每个 issue 只会有一把锁,所以它是单数

### http 动词描述操作

幂等性: 一次和多次请求某一个资源应该具有同样的副作用,一次访问与多次访问对这个资源带来的编号是相同的
GET: 获取资源,单个或多个,  POST 创建资源, PUT 更新资源,客户端提供完整的资源数据, PATCH 更新资源,客户端提供部分的资源数据, DELETE 删除资源
GET, PUT, DELETE 为幂等, POST, PATCH 不幂等

### 资源过滤
提供合理的参数供客户端过滤资源

```
?state=closed 不同状态的资源
?page=2&per_page=100 访问第几页数据, 每页多少条
?sortby=name&order=asc 指定返回结果按照那个属性排序,以及排序顺序
```

### 常用 http 状态码

```
200 OK - 对成功的 GET, PUT, PATCH 或 DELETE 操作进行响应, 也可以被用在不创建新资源的  POST 操作上
201 Greated - 对创建新资源的 POST 操作进行响应, 应该带着指向新资源地址的 Location 头
202 Accepted - 服务器接受了请求, 但还未进行处理,响应中应该包含相应的指示信息,告诉客户端去那里查询关于本次请求的信息
204 No Content - 对不会返回响应体的成功请求进行响应
304 Not Modified - HTTP 缓存 header 生效的时候用
400 Bad Request - 请求异常,比如请求中的 body 无法解析
401 Unauthorized - 没有进行认证或认证非法
403 Forbidden - 服务器已经理解请求,但是拒绝执行
404 Not Found - 请求一个不存在的资源
405 Method Not Allowed - 所请求的  HTTP方法不允许当前认证用户访问
410 Gone - 表示当前请求的资源不再可用, 当调用老版本 API 时候很有用
415 Unsupported Media Type - 如果请求中的内容类型是错误的
422 Unprocessable Entity - 用来表示校验错误
429 Too Many Requests - 由于请求频次到达上限而拒绝访问
```
### 数据响应格式

默认使用 JSON 格式,如果客户端有需求,在 Accept 头中指定需要的格式
```
https://api.larabbs.com/
    Accept: application/prs.larabbs.v1+json
    Accept: application/prs.larabbs.v1+xml
```

错误数据,默认结构
```
'message' => ':message',    // 错误的具体描述
'errors' => ':errors',      // 参数的具体错误描述, 422 等状态
'code' => ':code',          // 自定义的异常码
'status_code' => ':status_code'     // http 状态码
'debug' => ':debug'                 // debug 信息,非生产环境
```

### 调用频率限制

在响应头信息中加入核实的信息,告诉客户端当前的限流情况

* X-RateLimit-Limit: 100  最大访问次数
* X-RateLimit-Remaining: 93  剩余访问次数
* X-RateLimit-Reset: 1511231211 访问次数重置时间
