### Web 开发

PHP 是一种脚本语言，主要用于 Web 服务端开发

#### 语法

##### 变量

php 的变量使用时直接赋值，无须提前声明，也不能声明一个空变量，php 支持类型：`boolean`，`Integer`，`Float`，`String`，`Array`，`Object`，`Resource`，`Null`，`Callback/Callable`

|       类型        |                             常量                             | 备注                                                         |
| :---------------: | :----------------------------------------------------------: | ------------------------------------------------------------ |
|      boolean      |         使用常量 TRUE 或 FALSE，两者都不区分大小写；         | 用 `(bool)` 或 `(boolean)` 来强制转换，当运算符，函数或流程控制结构需要一个 `boolean` 参数时，该值会被自动转换。当转换为 `boolean` 时，以下值被认为是假：FALSE、0、0.0、""、"0"、[]、NULL、从空标记生成的 `SimpleXML` 对象；所有其它值都被认为是真（包括任何资源和 NAN） |
|      integer      | `PHP_INT_SIZE` 表示字长，`PHP_INT_MAX` 表示最大值，`PHP_INT_MIN` 表示最小值 | 整形值可以使用十进制，十六进制，八进制，二进制，前面可以加上可选的符号 `-` 或 `+`；要使用八进制数字前必须加 `0`，要使用十六进制，数字前必须加上 `0x`，要使用二进制表达，数字前必须加上 `0b`。不支持无符号 `integer` |
|       float       |                                                              | 永远不要相信浮点数结果精确到了最后一位，也永远不要比较两个浮点数是否相等。如果确实需要更高的精度，应该使用任意精度数学函数或者 gmp 函数，要测试浮点数是否相等，要使用一个仅比该数值大一丁点的最小误差值。该值是计算中所能接受的最小的差别值 |
|      String       |                                                              | 一个字符串就是由一系列的字符组成，其中每个字符等同于一个字节。PHP 只能支持 256 的字符集，因此不支持 Unicode。String 最大可以达到 2GB。字符串可以用单引号，双引号，`heredoc` （类似双引号字符串），`nowdoc` （类似单引号字符串，标识符使用单引号将标识符括起来）String 中字符可以通过一个从 0 开始的下标，用类似 `array` 结构中的方括号包含对应数字来访问和修改 |
|       array       |                                                              | `key` 可以是 `integer` 或 `string`。键名 *"8"* 实际会被储存为 *8*，但是 *"08"* 则不会强制转换；键名 *8.7* 实际会被储存为 *8*，键名 *true* 实际会被储存为 *1* 而键名 *false* 会被储存为 *0*；键名 *null* 实际会被储存为 *""*。数组和对象*不能*被用为键名。value 可以是任意类型，如果在数组定义中多个单元都使用了同一个键名，则只使用了最后一个，之前的都被覆盖了。对于任意 integer，float，string，boolean 和 resource 类型，如果将一个值转换为数组，将得到一个仅有一个元素的数组，其下标为 0，该元素即为此标量的值。(array)$scalarValue* 与 *array($scalarValue)* 完全一样；整数属性不可访问；如果一个 object 类型转换为 array，则结果为一个数组，其单元为该对象的属性。键名将为成员变量名私有变量前会加上类名作前缀；保护变量前会加上一个 '*' 做前缀。这些前缀的前后都各有一个 NULL 字符。这会导致一些不可预知的行为 |
|      object       |                                                              | 如果将一个对象转换为对象，它将不会有任何变化。如果其它任何类型的值被转换成对象，将会创建一个内置类 `stdClass` 的实例。如果该值为 NULL，则新实例为空，对于其他值，会包含进成员变量名 scalar |
|     resource      |                                                              | 保存了到外部资源的一个引用。资源是通过专门的函数来建立和使用的。资源类型变量保存为打开文件，数据库连接，图形画布区域等特殊句柄，因此将其他类型转换为资源没有意义。引用计数系统是 zend 引擎的一部分，可以自动检测到一个资源不再被引用了，这种情况下使用的所有外部资源都会被垃圾回收系统释放。很少需要手工释放内存。持久数据库连接比较特殊，它们不会被垃圾回收系统销毁。 |
|       NULL        |                                                              | 特殊的 NULL 值表示一个变量没有值。NULL 类型唯一可能的值就是 NULL。在下列情况下一个变量被认为是 NULL：被赋值为 NULL，尚未被赋值，被 `unset()` |
| Callback/Callable |                                                              | 5.4 起可用 `callable` 类型指定回调类型 `callback`，一些函数可以接受用户自定义的回调函数作为参数。回掉函数不止可以是简单的函数，还可以是对象的方法。将函数以 `string` 形式传递，可以使用任何内置或用户自定义函数，除了语言结构： `array()`，`echo`，`empty()`，`eval()`，`exit()`，`isset()`，`list()`，`print`，`unset()`。还可以传递匿名函数 |

##### 运算符

支持算术运算符、赋值运算符、位运算符（向任何方向移出去的位都被丢弃。左移时右侧以零填充，符号位被移走意味着正负号不被保留。右移时左侧以符号位填充，意味着正负号被保留）、错误控制运算符

|    例子    |      名称       |                             结果                             |
| :--------: | :-------------: | :----------------------------------------------------------: |
|    -$a     |      取反       |                          $a 的负值                           |
| `$a + $b`  |      加法       |                          sum(a, b)                           |
| `$a - $b`  |      减法       |                          `$a - $b`                           |
| `$a * $b`  |      乘法       |                          `$a * $b`                           |
| `$a / $b`  |       除        |                                                              |
| `$a % $b`  |      取模       |                                                              |
| `$a ** $b` |      求幂       |              `$a = 2, $b = 4` ; `$a ** $b = 16`              |
|     =      |      赋值       | 将右边表达式的值赋给左边，赋值运算将原变量的值拷贝到新变量中（传值赋值） |
|     &      |     按位与      |               将 $a 和 $b 中都为 1 的位设为 1                |
|     \|     |     按位或      |              $a 和 $b 中任何一个为 1 的位设为 1              |
|     ^      |    按位异或     |          $a 和 $b 中一个为 1 另一个为 0 的位设为 1           |
|     ~      |    按位取反     |        ~ $a 将 $a 中为 0 的位设为 1，为 1 的位设为 0         |
|     <<     |      左移       |    $a << $b 将 $a 中的位向左移动 $b 次（每次移动即乘以2）    |
|     >>     |      右移       |    $a >> $b 将 $a 中的位向右移动 $b 次，每次移动即除以 2     |
|     ==     |      等于       |                  如果类型转换后 $a 等于 $b                   |
|    ===     |      全等       |                   $a 等于 $b，且类型也相同                   |
|     !=     |      不等       |                 如果类型转换后 $a 不等于 $b                  |
|     <>     |      不等       |                 如果类型转换后 $a 不等于 $b                  |
|    !==     |     不全等      |            如果 $a 不等于 $b，或者它们的类型不同             |
|     <      |      小于       |                                                              |
|     >      |      大于       |                                                              |
|     <=     |                 |                                                              |
|     >=     |                 |                                                              |
|    <=>     |  太空船运算符   | $a <=> $b 当 $a 小于、等于、大于 $b 时，返回一个小于、等于、大于 0 的整数值，7.0 开始提供 |
|     ??     | NULL 合并操作符 | 从左往右第一个存在且不为 NULL 的操作数，如果都没有定义且不为 NULL，则返回 NULL，7.0 开始提供 |
|    ++$a    |      前加       |                        加一，返回 $a                         |
|    $++     |      后加       |                        返回 $a，加一                         |
|    --$a    |      前减       |                       $a 减一，返回 $a                       |
|    $a--    |      后减       |                   返回 $a，将 $a 的值减一                    |

* 错误控制运算符

    PHP 支持一个错误控制运算符 `@`，将其放置在一个 PHP 表达式之前，该表达式可能产生的任何错误信息都被忽略掉。

    如果用 `set_error_handler()` 设定了自定义的错误处理函数，仍然会被调用，但是此错误处理函数可以（并且也应该）调用 `error_reporting()` ，而该函数在出错语句前有 `@` 时将返回 0

    如果激活了 `track_errors`（boolean，默认 `"0"`，如果开启，最后一个错误将永远存在变量 `$php_errormsg` 中），表达式所产生的任何错误信息都被存放在变量 `$php_errormsg` 中。此变量在每次出错时都会被覆盖

* 执行运算符

    支持使用反引号 "\`" 作为执行运算符，PHP 将尝试将反引号中的内容作为 shell 命令来执行，并将其输出信息返回。（可以赋给一个变量而不是简单地丢弃到标准输出）。使用反引号运算符的效果于 `shell_exec()` 相同。

    反引号运算符在激活了安全模式或关闭了 `shell_exec()` 时无效，与其他语言不同，反引号不能在双引号字符串中使用

* 字符串运算符

    连接运算符 `.`，它返回其左右参数连接后的字符串。连接赋值运算符 `.=`，它将右边参数附加到左边的参数之后

* 数组运算符

    | 操作    | 描述   | 含义                                       |
    | ------- | ------ | ------------------------------------------ |
    | a + b   | 联合   | a 和 b 的联合                              |
    | a == b  | 相等   | 如果 a 和 b 具有相同的键值则为 true        |
    | a === b | 全等   | a 与 b 具有相同键值对且顺序类型相同为 true |
    | a != b  | 不等   | a 不等于 b 则为 true                       |
    | a <> b  | 不等   | a 不等于 b 则为 true                       |
    | a != b  | 不全等 | a 不全等 b 则为 true                       |

* 类型运算符

    *instanceof* 用来确定一个变量是否属于某一类或接口的实例

* 三元运算符 `?:`

    表达式 *(expr1) ? (expr2) : (expr3)* 在 expr1 求值为 **`TRUE`** 时的值为 expr2，在 expr1 求值为 **`FALSE`** 时的值为 expr3。

    自 PHP 5.3 起，可以省略三元运算符中间那部分。表达式 *expr1 ?: expr3* 在 expr1 求值为 **`TRUE`** 时返回 expr1，否则返回 expr3

##### SPL 标准库

用于解决典型问题的一组接口与类的集合

###### 数据结构

* SplDoublyLinkedList

    双向链表是一个链接到两个方向的节点列表。当底层结构是双向链表时，迭代器的操作、对两端的访问、节点的添加或删除都具有 `O(1)` 的开销。它为栈和队列提供了一个合适的实现

* SplStack

* SplQueue

* SplHeap

    堆是遵循堆属性的树状结构：每个节点都大于或等于其子级，使用对堆全局的已实现的比较方法进行比较

* SplMaxHeap

* SplMinHeap

* SplPriorityQueue

* SplFixedArray

* SplObjectStorage

* SplFixedArray

* SplObjectStorage（对象存储）

    对象到数据的映射，此映射也可以用作对象集

##### 异常处理

方法里 try catch finally 的执行顺序

*   ```php
    try {} catch{} {} finally{}
    ```

    1.  不论是否出现异常，`finally` 块中的代码都会执行
    2.  当 `try` 和 `catch` 中有 `return` 时，`finally` 仍然会执行
    3.  `finally` 是在 `return` 后面的表达式运算后执行的（此时并没有返回运算后的值，而是先把要返回的值保存起来）待 `finally` 执行完毕后再返回保存的值
    4.  `finally` 里包含 `return`，返回值是 `finally` 中 `return` 代码块的值

*   ```php
    try {} catch(){} finally{} return;
    ```

    1.  程序按照顺序依次执行 `try` 中代码，如果不出现异常，执行 `finally` 代码，然后执行 `return` 块中的代码；
    2.  `try` 中出现异常则执行 `catch` 中的代码后，执行 `finally` 代码，执行最后的 `return` 代码

*   ```php
    try {return;} catch() {} finally {} return;
    ```

    1.  程序执行 `try` 块中的 `return` 之前的代码，如果出现异常则执行 `catch` 里的代码，然后执行 `finally` 里面的代码，最后，执行 `return` 代码；
    2.  如果执行 `try` 块中 `return` 之前的代码没有出现异常，则将返回值保存起来，执行 `finally` 里代码，然后返回 `try` 中 `return` 代码值。

*   ```php
    try {} catch() {return;} finally{} return;
    ```

    1.  程序先执行 `try`，如果遇到异常执行 `catch` 块，然后将 `catch` 块中的 `return` 值保存起来，执行 `finally` 里代码，然后返回 `catch` 块的 `return` 值，最后的 `return` 不会执行；
    2.  如果程序执行 `try` 没有异常，则会执行 `finally` 语句，然后执行最后的 `return` 语句

*   ```php
    try {return;} catch() {} finally {return;}
    try {} catch() {return;} finally {return;}
    ```

    1.  程序只会返回 `finally` 的 `return` 语句
    
#### 文件加载

php 不存在类加载，一个文件中可以定义类和过程，所以 zend 加载以文件为基础，运行时会将文件全部加载进内存，编译成 zend 能执行的中间码，支持在运行时通过函数动态引入文件

##### PSR-4 自动加载器策略

PSR-4 描述在运行时查找并加载 PHP 类，接口和性状，只建议如何使用文件系统目录结构和 PHP 命名空间组织代码（把命名空间的前缀和文件系统中的目录对应起来），自动加载器依赖 PHP 命名空间和文件系统目录结构查找并加载 PHP 类，接口和性状

###### PSR-4 自动加载器

符合 PSR-4 规范代码有个命名空间前缀对应于文件系统中的基目录，这个命名空间前缀中的自命名空间对应与这个基目录里的子目录。

*实现自动加载器，这个自动加载器会根据PSR-4自动加载器策略查找并加载类，接口和性状（http://bit.ly/php-fig）*

```php
/**
 * @param string $class 完全限定的类名
 * $return void
 */
spl_autoload_register(function ($class) {
    // 项目的命名空间前缀
    $prefix = 'Foo\\Bar\\';
    // 这个命名空间前缀对应的基目录
    $base_dir = __DIR__ . '/src/';
    // 参数传入的类使用这个命名空间前缀吗？
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // 不使用,交给注册的下一个自动加载器处理
        return ;
    }
    // 获取去掉前缀后的类名
    $relative_class = substr($class, $len);
    // 把命名空间前缀替换成基目录,在去掉前缀的类名中，把命名空间分隔符替换成目录分隔符，然后加 .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    // 如果文件存在，将其导入
    if (file_exists($file)) {
        require $file;
    }
});  
```

#### web 相关

##### 开发方式

一般使用框架进行开发，框架会提供基本的路由映射、数据库、会话等操作的封装，基本只需关注逻辑即可

###### 面向对象

在 index 中进行面向对象开发，涉及路由映射处理（`$_REQUEST[URI]`），因为 PHP 依赖一些全局函数设置相关运行时状态，很难做到百分百面向对象，即会夹杂一些面向过程的调用

###### 面向过程

面向过程，单个文件处理逻辑，路由映射到具体文件，跳转到具体文件方式， `window.location='logic.php'` 

##### 会话

###### session

PHP 以扩展（已编入核心）方式支持 session，并进行相关配置，程序中使用 `$_SESSION` 全局变量来支持值得存取，默认存储在文件中，可以自行实现 `SessionHandlerInterface` 来定义存储位置、存储格式内容等（默认会使用 php 内置序列化方式进行存储），并使用 `session_set_save_handler()` 来进行注册

#### 数据库操作

PHP 紧密得与 MySQL 进行绑定，操作数据库时依赖于系统库进行操作，即没有实现语言级别的库，如使用 Oracle 时，不但需要 Oracle 扩展，还需要 Oracle-Runtime 运行环境。

##### PDO

PDO 底层使用各个数据库的扩展操作各个数据库，在其上进行了封装屏蔽各个数据库的差异性，较原始扩展低 10% ~ 15% 左右

###### 操作事项

* `top` 和 `limit` 不支持预处理语句，必须拼接
* `fetchAll(PDO::FETCH_ASSOC)`  获取关联数组结果集

#### Mysql

##### 设置

###### 驱动选项

底层扩展的客户端库

*   `libmysql`

    默认，是早期为 C 应用设计的，没有针对 PHP 应用进行优化

*   mysqlnd

    编译时指定 `--enable-mysqlnd` 开启，针对了 PHP 应用进行了优化。使用时要求服务器版本为 4.1.3 及以后

###### 配置相关

*php.ini*

```ini
# 每个进程允许的最大连接数
mysqli.max_links => Unlimited => Unlimited 
# 可以建立的最大持久连接数，0 无限制
mysqli.max_persistent => Unlimited => Unlimited
# 允许使用持久连接
mysqli.allow_persistent => On => On
# 指定持久连接清理机制，开启会隐式清理
mysqli.rollback_on_cached_plink => Off => Off
# 连接配置
mysqli.default_host => no value => no value
mysqli.default_user => no value => no value
mysqli.default_pw => no value => no value
mysqli.default_port => 3306 => 3306
mysqli.default_socket => no value => no value
# 如果连接断开，自动重连，mysqlnd 驱动会忽略此设置
mysqli.reconnect => Off => Off
# 允许使用 LOAD DATA 语句访问本地文件
mysqli.allow_local_infile => Off => Off
```

用户无法通过 API 调用或运行时配置设置来设置 `MYSQL_OPT_READ_TIMEOUT`。

###### 连接选项

* 使用 `localhost` 连接数据库时候，底层使用的是 `UNIX socket`, 使用 `IP` 地址连接数据库时使用的是 `TCP/IP`，设置连接选项有三个步骤进行：创建一个连接句柄 `mysqli_init()`，使用设置要求的选项 `mysqli_options()`，建立网络连接 `mysqli_real_connect()`

* 如果链接未提供参数，默认使用 PHP 设置里的参数。如果主机值未设置或为空，则客户端库将默认使用 `localhost` 的 `unix` 套接字，如果 `socket` 未设置或空，并且请求了 `unix` 套接字连接，则尝试连接到 `/tmp/mysql.sock`上的默认套接字

* 连接池

    mysql` 扩展支持持久数据库连接，这是一种特殊的连接池。默认情况下，脚本打开的每个数据库连接都由用户在运行时显式关闭，或者在脚本结束时自动释放。使用持久连接时，如果打开使用用户名、套接字、端口、默认数据库的同一服务器连接，则将其放入池中以供以后重用。每个 `php` 进程都使用自己的 `mysqli` 连接池

* 持久连接

    打开持久连接在主机名前加 `p:`，在重用持久连接之前，mysqli 扩展隐式调用 `mysqli_change_user()` 来重置状态。持久连接对用户来说就像刚刚打开一样，自动清理的优点是程序员不再需要担心附加的清理代码，它们会自动调用。缺点是性能会慢一点，因为每次从连接池返回一个连接都需要执行这些清理代码，可以通过在编译是定义 `MYSQLI_NO_CHANGE_USER_ON_PCONNECT` 来关闭

* `mysqli` 使用预处理语句防止`sql`注入, `mysqli::query()`, 需要自行处理 sql 注入，转义参数

MySQL 数据类型是二进制时，预处理语句绑定参数必须使用 null 占位，并使用 send_long_data 插入对应位置的二进制数据

```php
$stmt = $mysqli->prepare("INSERT INTO messages (message) VALUES (?)");
$null = NULL;
$stmt->bind_param("b", $null);
$fp = fopen("messages.txt", "r");
while (!feof($fp)) {
    // 第一个参数为二进制位置索引，第二个参数为值
    $stmt->send_long_data(0, fread($fp, 8192));
}
fclose($fp);
$stmt->execute();
```

###### MySQL 8

在 7.1.16 之前或 7.2.4 之前使用 MySQL 8，需要将服务器的默认密码插件设置为 `mysql_native_password`，否则会报客户端未知的服务器请求的身份验证方法 [caching_sha2_password] 的错误，即使未使用 `caching_sha2_password`

MySQL 8 默认使用 `caching_sha2_password`，这是一个旧的 PHP （mysqlnd）版本无法识别的插件。在 `my.cnf` 设置 `default_authentication_plugin = mysql_native_password` 来修改。该 `caching_sha2_password` 插件将在未来 PHP 版本中支持。`mysql_xdevapi` 扩展支持它

##### 执行语句

###### 执行语句

`mysqli_query()`，`mysqli_real_query()`，`mysqli_multi_query()` 函数被用于执行非准备语句。使用 `real_query` 获取无缓冲结果，按行读取。`mysqli_query` 默认缓冲结果集

```php
# 使用 real_query 获取无缓冲结果集
$mysqli->real_query("select id from test order by id asc);
$res = $mysqli->use_result();
while ($row = $res->fetch_assoc()) {}
```

###### 预处理语句

预处理语句默认返回无缓冲的结果集，可以使用 `mysqli_stmt_store_result()` 缓冲预处理语句的结果。

预处理语句执行包括两个阶段：准备和执行。在准备阶段，将语句模版发送到数据库服务器。服务器执行语法检查并初始化服务器内部资源以供以后使用。预处理绑定参数可以使用 `$this->param` 对象属性来绑定参数。绑定参数时，必须是当前作用域下声明的变量。

```php
// insert 准备一次，执行多次
$mysqli = new mysqli("example.com", "user", "password", "database");
if (!($stmt = $mysqli->prepare("INSERT INTO test(id) VALUES (?)"))) {
    echo $mysqli->error;
}
for ($id = 2; $id < 5; $id++) {
    if (!$stmt->bind_param("i", $id)) {
      	break;
    }
    if (!$stmt->execute()) {
        $stmt->error;
    }
}
```

预处理语句会占用服务器资源，应在使用后立即明确关闭。如未显式关闭，PHP 释放语句句柄时将关闭该语句。使用预准备语句并不总是执行语句的最有效方式。仅执行一次的预准备语句会导致客户端-服务器往返次数超过未准备的语句

###### 获取结果

*   使用输出变量绑定获取结果

    ```php
    // 输出变量必须在语句执行后绑定。必须为语句结果集的每一列绑定一个变量
    $mysqli = new mysqli("example.com", "user", "password", "database");
    if (!($stmt = $mysqli->prepare("SELECT id, label FROM test"))) {
        echo $mysqli->error;
    }
    if (!$stmt->execute()) {
        echo $mysqli->error;
    }
    $out_id = null;
    $out_label = null;
    if (!$stmt->bind_result($out_id, $out_label)) {
        $stmt->error;
    }
    $result = [];
    while ($stmt->fetch()) {
        $result[] = [$out_id, $out_label];
    }
    ```

*   使用 `mysqli_result` 获取结果，`mysqli_stmt_get_result()` 返回缓冲的结果集

    ```php
    $mysqli = new mysqlid("example.com", "user", "password", "database");
    $stmt = $mysqli->prepare("SELECT id, label FROM test");
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        // 获取全部结果，以关联数组返回
        var_dump($res->fetch_all(MYSQLI_ASSOC));
        // 逐条获取每一行数据，以关联数组返回
        $results = [];
        for ($row_no = 0; $row_no < $res->num_rows; $row_no++) {
            $res->data_seek($row_no);
            $results[] = $res->fetch_assoc();
        }
        $res->close();
    }
    # 获取元数据
    $res = $mysqli->query("SELECT 1 as _one, 'hello' as _two from dual);
    // 获取元数据
    $res->fetch_fields();
    // 预处理语句结果集元数据
    $stmt = $mysqli->prepare("SELECT 1 as _one, 'hello' as _two from dual);
    $stmt->execute();
    $res = $stmt->result_metadata();
    $res->fetch_fields();
    ```

###### 结果集数据类型

MySQL 客户端服务器协议为预处理语句和非预处理语句定义了不同的数据传输协议。

*   预处理的语句使用二进制协议。MySQL 服务器以二进制格式按原样发送结果集数据。在发送之前，结果不会序列化为字符串。客户端库不仅接收字符串，相反，他们将接收二进制数据并尝试将值转换为适当的 PHP 数据类型

*   默认情况下，非预处理语句将所有结果作为字符串返回。服务器在发送之前将结果集的所有数据转换为字符串，无论 SQL 结果集列数据类型如何，都将完成此转换。客户端库将所有列值作为字符串接收。不再进行客户端转换以将列转换回其基本类型，所有值都以 PHP 字符串的形式提供

    可以使用连接选项更改默认值。如果使用 `mysqlnd` 库，且使用了连接设置选项 `$mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1)`，`mysqlnd` 库将检查结果集元数据列类型并将数字 SQL 列转换为 PHP 数字，如果 PHP 数据类型值范围允许的话

###### 存储过程

存储过程可以有 `IN`，`INOUT`，`OUT` 参数，具体取决于 MySQL 版本。mysqli 接口对于不同类型的参数没有特殊的概念使用 `mysqli->query(call procedure_name())` 调用存储过程。

`IN` 参数，输入参数随 CALL 语句提供。要正确转义

```php
// 创建存储过程
$mysqli->query("create procedure p (IN id_val INT) begin insert into test(id) values(id_val); end;");
// 调用存储过程
$mysqli->query("call p(1)");
```

`INOUT/OUT` 参数使用会话变量访问

```mysql
$mysqli->query('create procedure p(OUT msg VARCHAR(50)) begin select "Hi!" INTO msg; end;');
if ($mysqli->query("SET $msg = ''") & $mysqli->query("CALL p(@msg")) {
    $res = $mysqli->query("SELECT @msg as _p_out");
    $row = $res->fetch_assoc();
    echo $row['_p_out'];
}
```

存储过程可以返回结果集。无法使用 `mysqli_query()` 获取，使用 `mysqli_real_query()` 或 `mysqli_multi_query()` 获取从存储过程返回的结果集。

```php
// 从存储过程中获取结果
if ($mysqli->multi_query("CALL p()")) {
    do {
        if ($res = $mysqli->store_result()) {
            var_dump($res->fetch_all(MYSQL_ASSOC));
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
}
```

使用预处理语句从存储过程获取结果，不需要特殊处理。预处理和未处理的语句类似

```php
if (!($stmt = $mysqli->prepare("CALL p()"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
do {
    if ($res = $stmt->get_result()) {
        printf("---\n");
        var_dump(mysqli_fetch_all($res));
        mysqli_free_result($res);
    } else {
        if ($stmt->errno) {
            echo "Store failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }
} while ($stmt->more_results() && $stmt->next_result());
```

使用绑定处理存储过程

```php
if ($stmt = $mysqli->prepare("CALL p()")) {
    $stmt->execute();
    do {
    $id_out = NULL;
    if (!$stmt->bind_result($id_out)) {
        echo "Bind failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    while ($stmt->fetch()) {
        echo "id = $id_out\n";
    }
} while ($stmt->more_results() && $stmt->next_result());
}
```

