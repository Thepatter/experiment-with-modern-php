## MongoDB 基本使用

### shell 的基本操作

* 启动 shell 客户端 `mongo`
* 连接其他 `MongoDB` 实例 `mongo some-host:port/db`

* 创建 `db.blog.insert(post)` `post` 为一个文档类型变量

* 批量插入 `db.foo.batchInsert([{"foo": "bar"}, {"hello": "world"}, {"zyw": "we"}])`,如果插入过程中有一个文档插入失败,那么之前的所有文档都会成功插入,而这个文档及之后所有文档全部插入失败,可以使用 `continueOnError` 选项来忽略错误并继续执行后续插入

* 读取 `db.blog.find()`, `db.blog.findOne()`,

* 更新 `db.blog.update(限定条件,新的文档)`

  `"$inc"` 修改器增加 `key` 的值  `{"$inc": {"key", 1}}`, 如果 `key` 不存在则创建,增加数值可为负数

  `"$set"` 修改器修改 `key` 的值(任何 `MongoDB` 运行的值), 如果 `key` 不存在则创建它 `{"$set": {"kye", "value"}}`

  `"$unset"` 修改器删除 `key `  , `{"$unset": {"favorite_book": 1}}`

  数组修改器

  `"$push"` 会向已有的数组末尾加入一个元素,要是没有就创建一个新的数组

  `"$addToSet"` 来保证数组内的元素不会重复

  `"$pop"` 从数组任何一端删除元素 `{"$pop": {"key": 1}}` 从数组末尾删除一个元素, `{"$pop": {"key": -1}}` 从头部删除一个元素

  `"$pull"` 基于特定条件删除元素 `{"$pull": {"todo": "laundry"}}`

* 更新插入 `upsert` `update(限定条件,新的文档, true)`

* 删除 `db.blog.remove({title: "My Blog Post"})` 永久删除数据.不加条件则删除集合内的所有文档,但不会删除集合,清空集合 `db.blog.drop()`

### MongoDB 数据类型

* null 用于表示空值或者不存在的字段 `{"x": null}`
* 布尔 `true`, `false` ` {"x": true}`
* 数值,默认64位浮点数值 `{"x": 3.14}`
* 字符串
* 日期 `{"x": new Date()}`
* 正则表达式 `{"x":  /foobar/i}`
* 数组   `{"x": ["a", "b"]}`
* 内嵌文档  `{"x": {"foo": "bar"}}`
* 对象id , 12 字节的 id, 文档的唯一标记 `{"x": ObjectId()}`
* 二进制数据
* 代码

 __MongoDB 中存储的文档必须有一个 `_id` 键,可以为任何类型,默认是 `ObjectId` 对象,在一个集合里面,每个文档都有唯一的 `_id`, 确保集合里面每个文档都能被唯一标识 __

__`ObjectId` 使用 12 字节的存储空间,是一个由 24 个十六进制数组组成的字符串(每个字节可以储存两个十六进制数字) 前 4 个字节是时间戳秒,接下来的 3 个字节是所在主机的唯一标识符,通常是机器主机名的hash值,接下来两个字节是产生 `ObjectId` 进程的进程标识符,最后 3 个字节是自动增加的计算器__

### `mongoimport` 导入数据集

```
$ mongoimport --db test --collection zips --file zips.json --drop
$ mongoimport --db test --collection restaurants --file primer-dataset.json --drop
```

