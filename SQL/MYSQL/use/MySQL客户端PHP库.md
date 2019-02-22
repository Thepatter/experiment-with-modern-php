## MySQL客户端PHP库

### MySQLI 库

#### PHP 的 MySQL Native 驱动

为了与 MySQL 数据库数据端进行交互，mysql 扩展，mysqli 扩展，PDO MySQL 驱动都使用实现必要的协议的底层库。以前，可用的库只有 MySQL 客户端库和 `libmysql`，`libmysql` 包含的接口没有针对与 PHP 的应用交互进行优化，`libmysql` 是早期为 C 应用程序设计的。`MySQL  Native` 驱动 `mysqlnd` ，作为 `libmysql` 的一个针对 PHP 应用的修改版本被开发。mysql，mysqli 以及 PDO MySQL 驱动都可以各自配置使用 `libmysql` 或者 `mysqlnd`，`mysqlnd` 作为一个专门设计用于 `PHP` 系统的库，它在内存和速度上都比 `libmysql` 有很大提升。MySQL Native 驱动仅仅可以在 MySQL 服务端版本为 4.1.3 及以后版本才可以使用。

#### 连接

MySQL 服务器支持使用不同的传输层进行连接。连接使用 TCP/IP ，Unix 域套接字或 Windows 命名管道。`localhost` 必然会使用 Unix 域套接字。

如果主机值未设置或为空，则客户端将默认为 `localhost` 上的 `Unix` 套接字连接。如果 `socket` 未设置或为空，并且请求了 `Unix` 套接字连接，则尝试连接到 `/tmp/mysql.sock` 上的默认套接字

设置连接选项有三个步骤进行：创建一个连接句柄 `mysqli_init()`，使用设置要求的选项 `mysqli_options()`，建立网络连接 `mysqli_real_connect()`

#### 连接池

mysqli 扩展支持持久数据库连接，这是一种特殊的池连接。默认情况下，脚本打开的每个数据库连接都由用户在运行时显式关闭，或者在脚本结束时自动释放。持久连接不是。如果打开使用相同同户名，密码，套接字，端口和默认数据库的同一服务器的连接，则将其放入池中以供以后重用，以节省连接开销。

每个 PHP 进程都使用自己的 mysql 连接池。根据 Web 服务器部署模型，PHP 进行可以提供一个或多个请求。随后可以由一个或多个脚本使用连接池连接。

#### 持久连接

如果在连接池中找不到主机，用户名，密码，套接字，端口和默认数据库的给定组合的未使用的持久连接，则 `mysqli` 将打开一个新连接。使用 PHP 配置选项，`mysqli.allow_persistent` 启用和禁用持久连接的使用。使用 `mysqli.max_links` 设置脚本打开的连接总数。`mysqli.max_persistent` 限制每个PHP进程的最大持久连接数。

mysqli 扩展支持持久连接的两种解释：状态持久化，以及重用前的状态重置。默认值已重置。在重用持久连接之前，mysqli 扩展隐式调用 `mysqli_change_user()` 来重置状态。持久连接对用户来说就像刚刚打开一样。

mysqli 没有提供一个特殊的方法用于打开持久化连接。 需要打开一个持久化连接时，你必须在 连接时在主机名前增加*p:*。

使用持久化连接也会存在一些风险， 因为在缓存中的连接可能处于一种不可预测的状态。 例如，如果客户端未能正常关闭连接， 可能在这个连接上残留了对库表的锁， 那么当这个连接被其他请求重用的时候，这个连接还是处于 有锁的状态。 所以，如果要很好的使用持久化连接，那么要求代码在和数据库进行交互的时候， 确保做好清理工作，保证被缓存的连接是一个干净的，没有残留的状态

mysqli 扩展的持久化连接提供了内建的清理处理代码。mysqli 所做的清理包括：回滚活动事务，释放表锁，重置会话变量，关闭预处理SQL 语句，关闭处理程序，释放 `GET_LOCK()` 获得的锁。这确保了将连接返回到连接池的时候，处于干净状态，可以被其他客户端进程使用。

自动清理的优点是程序员不再需要担心附加的清理代码，它们会自动调用。缺点是性能会慢一点，因为每次从连接池返回一个连接都需要执行这些清理代码，可以通过在编译是定义 `MYSQLI_NO_CHANGE_USER_ON_PCONNECT` 来关闭

#### 执行语句

语句执行结果可以立即检索，由客户端缓冲或逐行读取。客户端结果集缓冲允许服务器尽可能早地释放与语句结果相关联的资源。一般来说，客户端是慢速消耗结果集。`mysqli_query()` 结合了语句执行和结果集缓冲。如果客户端内存是紧缺资源并且不需要尽可能早地释放服务器资源以保持服务器负载的低，则可以使用无缓冲的结果。在读取所有行之前，无法滚动浏览无缓冲的结果 

```php
// 无缓冲结果
$mysqli->real_query("select id from test order by id asc);
$res = $mysqli->use_result();
while ($row = $res->fetch_assoc()) {}
```

#### 结果集值数据类型

`mysqli_query()`，`mysqli_real_query()`，`mysqli_multi_query()` 函数被用于执行非准备语句。在 `MySQL` 客户端服务器协议级别，命令 `COM_QUERY` 和文本协议用于语句执行。使用文本协议，MySQL 服务器在发送之前将结果集的所有数据转换为字符串。无论SQL结果集列数据类型如何，都将完成此转换。mysql 客户端库将所有列值作为字符串接收。不再进行客户端转换以将列转换回其基本类型。相反，所有值都以 PHP 字符串的形式提供。

如果使用 `mysqlnd` 库，且使用了连接设置选项 `$mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1)`，`mysqlnd` 库将检查结果集元数据列类型并将数字 SQL 列转换为 PHP 数字，如果 PHP 数据类型值范围允许的话。

#### 预处理

预处理语句或参数化语句用于以高效率重复执行相同的语句。预处理语句执行包括两个阶段：准备和执行。在准备阶段，将语句模版发送到数据库服务器。服务器执行语法检查并初始化服务器内部资源以供以后使用。

```php
// 准备
if(!($stmt = $mysqli->prepare("INSERT INTO test(id) values (?)")) {
 	echo $mysqli->error;  
}
```

准备语句之后是执行。在执行期间，客户端绑定参数值并将它们发送到服务器。服务器从语句模版创建语句，并使用先前创建的内部资源执行绑定值

```php
// 绑定并执行
$id = 1;
if (!$stmt->bind_param("i", $id)) {
    $stmt->error;
}
if (!$stmt->execute()) {
    $stmt->error;
}
```

准备好的声明可以重复执行。每次执行时，都会评估绑定变量的当前值并将其发送到服务器。该语句不会再次解析。语句模版不会再次传输到服务器

```php
// insert 准备一次，执行多次
$mysqli = new mysqli("example.com", "user", "password", "database");
if (!($stmt = $mysqli->prepare("INSERT INTO test(id) VALUES (?)"))) {
    echo $mysqli->error;
}
$id = 1;
if (!$stmt->bind_param("i", $id)) {
    $stmt->error;
}
$id = 1;
if (!$stmt->bind_param("i", $id)) {
    $stmt->error;
}
if (!$stmt->execute()) {
    $stmt->error;
}
for ($id = 2; $id < 5; $id++) {
    if (!$stmt->execute()) {
        $stmt->error;
    }
}
```

每个准备好的语句占用服务器资源。声明应在使用后立即明确关闭。如果没有显示关闭，则在PHP释放语句句柄时将关闭该语句。使用预准备语句并不总是执行语句的最有效方式。仅执行一次的预准备语句会导致客户端-服务器往返次数超过未准备好的语句。

#### 结果集值数据类型

MySQL客户端服务器协议为预处理语句和非预处理语句定义了不同的数据传输协议。**预处理的语句使用二进制协议。MySQL 服务器以二进制格式“按原样”发送结果集数据。在发送之前，结果不会序列化为字符串。客户端库不仅接收字符串，相反，他们将接收二进制数据并尝试将值转换为适当的PHP数据类型。**

**默认情况下，非预处理语句将所有结果作为字符串返回。可以使用连接选项更改默认值。如果使用连接选项，则与预处理语句没有差异。**

#### 使用绑定变量获取结果

可以通过绑定输出变量或通过请求 `mysqli_result` 对象来检索预处理语句的结果。输出变量必须在语句执行后绑定。必须为语句结果集的每一列绑定一个变量

```mysql
// 输出变量绑定
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

预处理语句默认返回无缓冲的结果集。如果客户端无法获取所有结果或客户端在获取所有数据之前关闭语句，则必须由 mysqli 隐式提取数据。也可以使用 `mysqli_stmt_store_result()` 缓冲预处理语句的结果。

也可以通过 `mysqli_result` 接口检索结果，而不是使用绑定结果。`mysqli_stmt_get_result()` 返回缓冲的结果集

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
```

#### 转义和 SQL 注入

绑定变量与查询分开发送到服务器，因此不会干扰它。在解析语句模版之后，服务器直接在执行点使用这些值。绑定参数不需要进行转义，因为它们永远不会直接替换为查询字符串，必须向服务器提供绑定变量类型的提示，以创建适当的转换。

这种分离有时被认为是防止 SQL 注入的唯一安全功能，但如果所有值都正确格式化，则可以使用非预处理语句实现相同程度的安全性。（正确的格式化与转义不同，并且涉及比简单转义更多的逻辑。）因此，预处理语句对于数据库安全来说更方便

#### 存储过程

存储过程可以有 `IN`，`INOUT`，`OUT` 参数，具体取决于 MySQL 版本。mysqli 接口对于不同类型的参数没有特殊的概念使用 `mysqli->query(call procedure_name())` 调用

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
            var_dump($res->fetch_all());
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

#### 事务API支持

可以使用 SQL 或 API 调用来控制事务。

```php
// 设置自动提交模式
$mysqli->autocommit(false);
// 或者
$mysqli->query('SET AUTOCOMMIT = 0');
```

#### 元数据

MySQL 结果集包含元数据。可以通过 mysqli_result 接口访问

```php
$res = $mysqli->query("SELECT 1 as _one, 'hello' as _two from dual);
// 获取元数据
$res->fetch_fields();
// 预处理语句结果集元数据
$stmt = $mysqli->prepare("SELECT 1 as _one, 'hello' as _two from dual);
$stmt->execute();
$res = $stmt->result_metadata();
$res->fetch_fields();
```

#### MySQL 8

在 7.1.16 之前的 PHP 或 7.2.4 之前的 php7.2，需要将 MySQL 8 Server 的默认密码插件设置为 `mysql_native_password`，否则会报客户端未知的服务器请求的身份验证方法 [caching_sha2_password] 的错误，即使在 `caching_sha2_password` 未使用是也如此

MySQL 8 默认使用 `caching_sha2_password`，这是一个旧的 PHP （mysqlnd）版本无法识别的插件。在 `my.cnf` 设置 `default_authentication_plugin = mysql_native_password` 来修改。该 `caching_sha2_password` 插件将在未来 PHP 版本中支持。`mysql_xdevapi` 扩展支持它

#### 配置参数

`php.ini` 中的配置

* `mysqli.allow_local_infile` 整型，从 PHP 的角度来看，允许使用 LOAD DATA 语句访问本地文件
* `mysqli.allow_persistent` 整型，启用 `mysqli_connect()` 创建持久连接的功能
* `mysqli.max_persistent` 整型，可以建立的最大持久连接数。设置 0 表示无限制
* `mysqli.max_links` 整数，每个进程的最大 MySQL 连接数
* `mysqli.default_port` 整数，如果未指定其他端口，则在连接数据库服务器时使用的默认 TCP 端口号。如果未指定 `default`，将从 `MYSQL_TCP_PORT` 环境变量，`/etc/services` 中的 `mysql-tcp` 条目或编译时 `MYSQL_PORT` 常量中获取该端口。win32 将只使用 MYSQL_PORT 常量
* `mysqli.default_socket` 字符串，如果未指定其他套接字名字，则在连接到本地数据库服务器时使用的默认套接字名称，不适用安全模式
* `mysqli.default_host` 字符串，如果未指定其他主机，则在连接到数据库服务器时使用的默认用户名。不适用安全模式
* `mysqli.default_pw` 字符串，如果未指定其他密码，则在连接到数据库服务器时使用的默认密码，不适用安全模式
* `mysqli.reconnect` 整数，如果连接丢失，则自动重新连接（mysqlnd 驱动程序会忽略此 php.ini 设置）
* `mysqli.rollback_on_cached_plink` 布尔，如果启用此选项，则关闭持久连接将回滚此连接的任何事务，然后再将其放回到持久连接池中。否则，只有在重用连接或实际关闭连接时，才会回滚挂起的事务

用户无法通过 API 调用或运行时配置设置来设置 `MYSQL_OPT_READ_TIMEOUT`。

### PDO 数据对象扩展

PDO 数据对象为 PHP 访问数据库定义了一个轻量级的一致接口。PDO 扩展自身并不能实现任何数据库功能：必须使用一个具体数据库 PDO 驱动来访问数据库服务。PDO 不提供数据库抽象层，不会重新 SQL，也不会模拟缺失的特性。 

