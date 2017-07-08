##PHP开发良好实践
###过滤输入
转义或删除不谙屈戴的字符，在数据到达应用戴的存储层之前过滤。
####html过滤
使用`htmlentities()`函数过滤html，把特殊字符转换成对应的html实体，`htmlentities()`函数用法
```$xslt
htmlentities($input, ENT_QUOTES, 'UTF-8');
```
第一个参数是输入字符串，第二个参数设为ENT_QUOTES常量，转义单引号，第三个参数设为输入字符串的字符集
####SQL查询
在SQL查询中一定不能使用未过滤的输入数据。使用PDO预处理语句。
####过滤用户资料使用
```$xslt
mixed filter_var(mixed $variable [,int $filter = FILTER_DEFAULT [, mixed $options]])
```
删除小鱼ASCII32戴的字符，转义大于ASCII127的字符
```$xslt
$safestring = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_ENCODE_HIGH)
```
####验证数据
把某个FILTER_VALIDATE_*标志传给filter_var()函数，成功返回要验证戴的值，失败返回false
###转义输出
可以使用htmlentities()函数转义输出，第二个参数使用ENT_QUOTES，转义单双引号，第三个参数指定字符集
###密码
####不能知道用户的密码
####不要越是用户的密码
####不要使用email发送密码
应该在电子邮件中发送用于设定或修改密码的URL，web应用通常会生成一个唯一的令牌，这个令牌只在设定或修改密码时
使用一次，例如，我忘记自己在你应用中戴的账户密码，单机忘记密码的链接，转到一个表单，我在这个表单中填写我的电
子邮件地址，请求重设密码，应用生成一个唯一的令牌，并把这个令牌关联得到我的电子邮件地址对应的账户上，然后发送
一封电子邮件到账户的电子邮件地址，这封电子邮件中有一个URL，其中某个URL片段或查询字符串的值是这个唯一的令牌
我访问这个URL，你的应用验证令牌，令牌有限，则重设密码， 重设密码后，令牌设置为失效。
###日期、时间和时区
设置默认时区，在php.ini设置date.timezone = 'Asia/shanghai'或者PRC
也可以在运行时使用date_default_timezone_set()函数设置默认默认时区`date_default_timezone_set('Asia/shanghai')`
####`Datetime` 类
如果没有参数，Datetime类的构造方法创建的三世一个当前日期和时间的实例，如果传递符合PHP时间规则的字符串，则创建对应时间
可以使用`DateTime::createFromFormat('M j, Y H:i:s', 'jan 2, 2014 23:04:12')`创建自定义的格式的DateTime实例
####DateInterval 类
interval 类实例表示长度固定的时间段(两天),或者相对而言的时间段(昨天),DateInterval 实例用于修改 Datetime 实例，Datetime类提供的
add() 和 sub() 方法的参数都是 DateInterval 实例，指定要添加到 DateTime 实例中的时间量，或者要从 DateTime 实例中减去的时间量
实例化DateInterVal类的方法是使用构造方法，构造方法参数是一个字符串，表示间隔规约，间隔规约是一个以字母P开头的字符串，后面跟着一个整数
最后是一个周期标示符(Y(年),M(月),D(日),W(周),H(时),M(分),S(秒)), `new DateInterval('P2D)` 两天的时间段
####DateTimeZone类。
PHP 使用 DateTimeZone 类表示时区，我们把有效的时区标示符传给 DateTimeZone 类
DateTime 类实例化第二个参数可以传入DateTimeZone的实例，则所有值都相对这个时区。不传为默认时区
####DatePeriod类
迭代处理一段时间内反复出现的一系列日期和时间，重复在日程表中记事。DatePeriod 类的构造函数的三个参数为，一个DateTime类实例，表示
迭代开始时的日期和时间，一个DateInterval实例，表示到下一个日期和时间的间隔。一个整数，表示迭代的总次数。第四个参数是可选，用于显示指定
周期的结束日期和时间，如果迭代时项排除起始日期和时间，可以把构造方法的最后一个参数设为DatePeriod::EXCLUDE_START_DATE常量。
DatePeriod实例是迭代器。每次迭代会产生一个DateTime实例。
###数据库
####数据库连接和DSN
实例化PDO类的作用是把PHP类和数据库连接起来。PDO类的构造方法有一个字符串参数，用于指定DSN(数据源名称)，提供数据库连接的详细信息。DSN由驱动器
名称：符号，包含主机明或IP地址，端口号，数据库名，字符集
####预处理语句
预处理语句是 PDOStatement 实例。一般通过 PDO 实例 prepare() 方法获取预处理语句对象。这个方法第一个参数是一个SQL语句字符串，返回值是一个
PDOStatement 对象。然后 PDOStatement 对象调用bindValue() 方法绑定各个sql语句中的占位符的值，如果要指定占位符的类型，则传入第三个参数，
默认绑定数据为字符串。
####查询结果
调用预处理语句 PDOStatement 实例的execute() 方法来执行SQL语句，如果执行的是INSERT、UPDATE、DELETE 语句，调用 execute() 后结束操作。
如果执行的是 SELECT 语句，可以调用预处理语句 fetch(), fetchAll(), fetchColumn(), fetchObject() 方法、获取查询结果。在预处理语句实例
调用fetch() 或 fetchAll() 方法时，这个方法的第一个参数设为 PDO::FETCH_ASSOC 常量，返回一个关联数组，数组的键是数据库的列名，为 
PDO::FETCH_NUM 常量时，返回一个键为数字的数组，数组的键是数据库列在查询结果中的索引。设为 PDO::FETCH_BOTH 常量时，返回一个既有键为列名又
有键为数字的数组。等于合并 PDO::FETCH_ASSOC 和 PDO::FETCH_NUM， 设为 PDO::OBJ 常量时，返回一个对象，对象的属性是数据库的列名
###事务
把一列系数据库语句当成单个逻辑单元(具有原子性)执行。事务的一系列SQL查询要么都成功执行，要么根本不执行，事务的原子性能保证数据的一致性，安全性
持久性。事务可以提升性能，多个查询队列，一次全部执行。把要在事务中运行的SQL语句放在PDO实例的 beginTransaction() 方法和 commit() 方法之间
###多字节字符串
PHP假设字符串中每个字符都是八位字符，占一个字节的内存。如果使用PHP原生的字符串函数处理包含多字节字符的 Unicode 字符串，会造成错误。应使用
mbstring 扩展。使用 mbstring 扩展函数来处理。
####字符编码
一定要知道数据的字符编码，使用 UTF-8 字符编码存储数据，使用 UTF-8 字符编码输出数据。使用 mb_detect_encoding() 和 mb_convert_encoding()
 可以把字符串从一种字符编码转换成另一种字符编码
####输出 UTF-8 数据
在 php.ini 文件中设置 default_charset = "UTF-8"
在 PHP 返回的响应中，Content-Type 首部设置 UTF-8 
`header('Content-Type: application/json;charset=utf-8)`。在 HTML 文档头部加入 meta 标签。`<meta charset="UTF-8"/>`
###流
流作用是使用同意的方式处理文件、网络和数据压缩等公用同一套函数和用法的操作。流三世具有流式行为的资源对象，流可以线性读写。流为 PHP 的很多 IO
函数提供了底层实现， 流函数提供了处理不同流资源的统一接口。