### markdown 语法

#### 基础语法

H1 ～ H6

```
#    				H1
##     			H2
###					H3
####				H4
#####				H5
######			H6
```

字体加粗

```
**初体内容**
```

斜体

```
*斜体内容*
_斜体内容_
```

图片

```
![](图片路径)
```

链接

```
[链接](链接url)
```

代码

```
​```language
​```
```

表格

```
| 编号 | 产品 | 描述 ｜
```

流程图

```
[flow]
st=>start: Start
e=>end: End
接收用户名和密码=>operation: 接收用户名和密码
使用用户名查询数据库=>operation: 使用用户名查询数据库
查询数据库=>condition: 查询数据库
登录成功=>operation: 生成token
存储到内存数据库=>operation: 存储到redis(key-value)
发布到队列=>operation: 发布到rabbimtmq队列
成功响应=>operation: 响应token
登录失败=>inputoutput: 响应失败
st->接收用户名和密码->使用用户名查询数据库->查询数据库
查询数据库(yes)->登录成功->存储到内存数据库->发布到队列->成功响应->e
查询数据库(no)->登录失败->e
```



```flow
st=>start: Start
e=>end: End
接收用户名和密码=>operation: 接收用户名和密码
使用用户名查询数据库=>operation: 使用用户名查询数据库
查询数据库=>condition: 查询数据库
登录成功=>operation: 生成token
存储到内存数据库=>operation: 存储到redis(key-value)
发布到队列=>operation: 发布到rabbimtmq队列
成功响应=>operation: 响应token
登录失败=>inputoutput: 响应失败
st->接收用户名和密码->使用用户名查询数据库->查询数据库
查询数据库(yes)->登录成功->存储到内存数据库->发布到队列->成功响应->e
查询数据库(no)->登录失败->e
```

时序图

```
Rabbit->Client: 推送用户任务到队列
Client->MySql: 写入 mysql 落盘
MySql->Client: 成功
Client->Rabbit: 消息确认
MySql-->Client: 失败
Client-->Rabbit: 消息拒绝
```

```sequence
Rabbit->Client: 推送用户任务到队列
Client->MySql: 写入 mysql 落盘
MySql->Client: 成功
Client->Rabbit: 消息确认
MySql-->Client: 失败
Client-->Rabbit: 消息拒绝
```







